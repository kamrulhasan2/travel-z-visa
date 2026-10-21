/**
 * Travel-Z Flipbook & Document Engine
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        var $wrapper = $('#travelz-flipbook-container');
        if (!$wrapper.length) {
            return;
        }

        var pdfUrl = $wrapper.data('pdf');
        if (!pdfUrl) {
            return;
        }

        var pdfDoc = null;
        var pageNum = 1;
        var pageRendering = false;
        var pageNumPending = null;
        var currentRenderTask = null;

        // Discrete zoom levels: 60%, 80%, 100%, 125%, 150%, 175%, 200%
        var zoomLevels = [0.6, 0.8, 1.0, 1.25, 1.5, 1.75, 2.0];
        var zoomIndex = 2; // Default 1.0 (100%)

        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        var $canvasArea = $('#travelz-flipbook-canvas-area');
        var $stage = $('#travelz-flipbook-stage');

        // Create page sheet
        var $sheet = $('<div class="travelz-page-sheet"></div>');
        $sheet.append(canvas);

        function updateZoomUI() {
            var factor = zoomLevels[zoomIndex];
            var percentText = Math.round(factor * 100) + '%';
            $('#travelz-fb-zoom-val').text(percentText);

            $('#travelz-fb-zoom-out').prop('disabled', zoomIndex <= 0);
            $('#travelz-fb-zoom-in').prop('disabled', zoomIndex >= zoomLevels.length - 1);
        }

        function updatePanCursor() {
            setTimeout(function() {
                var el = $stage[0];
                if (!el) return;
                if (el.scrollWidth > el.clientWidth + 5 || el.scrollHeight > el.clientHeight + 5) {
                    $stage.addClass('can-pan');
                } else {
                    $stage.removeClass('can-pan');
                }
            }, 60);
        }

        // Sound synthesizer using Web Audio API (No external sound file required!)
        function playFlipSound() {
            if (window.travelzVisaData && !window.travelzVisaData.soundEnabled) {
                return;
            }
            try {
                var AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                var ctxAudio = new AudioContext();
                var osc = ctxAudio.createOscillator();
                var gain = ctxAudio.createGain();

                osc.type = 'sine';
                osc.frequency.setValueAtTime(300, ctxAudio.currentTime);
                osc.frequency.exponentialRampToValueAtTime(80, ctxAudio.currentTime + 0.12);

                gain.gain.setValueAtTime(0.15, ctxAudio.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctxAudio.currentTime + 0.12);

                osc.connect(gain);
                gain.connect(ctxAudio.destination);

                osc.start();
                osc.stop(ctxAudio.currentTime + 0.12);
            } catch (e) {
                // Ignore audio errors
            }
        }

        // Load PDF.js dynamically
        function loadPdfJs(callback) {
            if (window.pdfjsLib) {
                callback();
                return;
            }

            var script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js';
            script.onload = function() {
                window.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
                callback();
            };
            script.onerror = function() {
                // Fallback to native embed
                showFallbackEmbed();
            };
            document.head.appendChild(script);
        }

        function showFallbackEmbed() {
            $canvasArea.hide();
            $('#travelz-iframe-zoom-container').show();
            $('#travelz-native-pdf-embed').show();
            $('#travelz-fb-current-page, .travelz-cur-page-num').text('1');
            $('#travelz-fb-total-pages, .travelz-tot-page-num').text('1');
            applyIframeZoom();
        }

        function renderPage(num) {
            if (!pdfDoc) return;
            pageRendering = true;

            // If a previous renderTask is in progress, cancel it cleanly
            if (currentRenderTask) {
                try {
                    currentRenderTask.cancel();
                } catch (e) {}
            }

            pdfDoc.getPage(num).then(function(page) {
                var unscaledViewport = page.getViewport({ scale: 1.0 });

                // Calculate base scale against the fixed stage boundaries (not the stretched viewport)
                var stageW = $stage.innerWidth() - 48; // padding 24+24
                var stageH = $stage.innerHeight() - 48;

                if (stageW < 200) stageW = 500;
                if (stageH < 200) stageH = 580;

                // At 100% zoom (factor 1.0), the page fits comfortably inside the fixed screen
                var scaleW = stageW / unscaledViewport.width;
                var scaleH = stageH / unscaledViewport.height;
                var baseScale = Math.min(scaleW, scaleH);
                baseScale = Math.max(0.4, Math.min(baseScale, 1.8));

                // Multiply by discrete zoom factor
                var factor = zoomLevels[zoomIndex];
                var effectiveScale = baseScale * factor;
                var viewport = page.getViewport({ scale: effectiveScale });

                // HiDPI / Retina canvas support for ultra-sharp rendering
                var outputScale = window.devicePixelRatio || 1;
                canvas.width = Math.floor(viewport.width * outputScale);
                canvas.height = Math.floor(viewport.height * outputScale);

                // CSS display size matches viewport width & height
                var cssWidth = Math.floor(viewport.width);
                var cssHeight = Math.floor(viewport.height);

                $(canvas).css({
                    width: cssWidth + 'px',
                    height: cssHeight + 'px'
                });

                $sheet.css({
                    width: cssWidth + 'px',
                    height: cssHeight + 'px'
                });

                var transform = outputScale !== 1 ? [outputScale, 0, 0, outputScale, 0, 0] : null;

                var renderContext = {
                    canvasContext: ctx,
                    transform: transform,
                    viewport: viewport
                };

                currentRenderTask = page.render(renderContext);

                currentRenderTask.promise.then(function() {
                    pageRendering = false;
                    currentRenderTask = null;
                    updatePanCursor();
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                }).catch(function(err) {
                    if (err && err.name === 'RenderingCancelledException') {
                        // Render was cancelled by a new zoom or page turn; ignore and proceed
                        pageRendering = false;
                        currentRenderTask = null;
                        if (pageNumPending !== null) {
                            renderPage(pageNumPending);
                            pageNumPending = null;
                        }
                    } else {
                        console.warn('PDF.js render error:', err);
                        pageRendering = false;
                        currentRenderTask = null;
                    }
                });
            }).catch(function(err) {
                console.warn('Get page error:', err);
                pageRendering = false;
            });

            // Update page counters
            $('#travelz-fb-current-page, .travelz-cur-page-num').text(num);
            $('#travelz-fb-prev, .travelz-footer-nav-btn.travelz-fb-prev').prop('disabled', num <= 1);
            $('#travelz-fb-next, .travelz-footer-nav-btn.travelz-fb-next').prop('disabled', num >= pdfDoc.numPages);
        }

        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        function onPrevPage() {
            if (pageNum <= 1) return;
            pageNum--;
            playFlipSound();
            $stage.scrollTop(0);
            queueRenderPage(pageNum);
        }

        function onNextPage() {
            if (!pdfDoc || pageNum >= pdfDoc.numPages) return;
            pageNum++;
            playFlipSound();
            $stage.scrollTop(0);
            queueRenderPage(pageNum);
        }

        function applyIframeZoom() {
            var $iframe = $('#travelz-native-pdf-embed');
            if (!$iframe.length) return;

            var factor = zoomLevels[zoomIndex];
            var baseHeight = $stage.innerHeight() || 620;
            var baseWidth = $stage.innerWidth() || 540;

            if (factor > 1.0) {
                var newWidth = Math.round(baseWidth * factor);
                var newHeight = Math.round(baseHeight * factor);
                $iframe.css({
                    'width': newWidth + 'px',
                    'height': newHeight + 'px',
                    'min-width': newWidth + 'px',
                    'margin': '0 auto',
                    'display': 'block'
                });
            } else {
                $iframe.css({
                    'width': '100%',
                    'height': baseHeight + 'px',
                    'min-width': '100%',
                    'margin': '0 auto',
                    'display': 'block'
                });
            }

            // Update viewer parameter if supported
            try {
                var currentSrc = $iframe.attr('src');
                if (currentSrc) {
                    var baseSrc = currentSrc.split('#')[0];
                    $iframe.attr('src', baseSrc + '#toolbar=0&navpanes=0&zoom=' + Math.round(factor * 100));
                }
            } catch (e) {}

            updatePanCursor();
        }

        function applyZoom() {
            updateZoomUI();

            if (pdfDoc) {
                queueRenderPage(pageNum);
            } else {
                applyIframeZoom();
            }
        }

        // Load document via PDF.js; if CORS fails, retry via local proxy before falling back to iframe
        function loadDocument(url, isProxyRetry) {
            window.pdfjsLib.getDocument({
                url: url,
                withCredentials: false
            }).promise.then(function(doc) {
                pdfDoc = doc;
                $('#travelz-fb-total-pages, .travelz-tot-page-num').text(pdfDoc.numPages);
                $canvasArea.empty().append($sheet);
                renderPage(pageNum);
                updateZoomUI();
            }).catch(function(err) {
                if (!isProxyRetry && window.travelzVisaData && window.travelzVisaData.ajaxUrl) {
                    var proxyUrl = window.travelzVisaData.ajaxUrl + '?action=travelz_pdf_proxy&pdf=' + encodeURIComponent(pdfUrl);
                    loadDocument(proxyUrl, true);
                } else {
                    console.warn('PDF.js render error, falling back to iframe embed:', err);
                    showFallbackEmbed();
                }
            });
        }

        // Initialize document loading
        loadPdfJs(function() {
            loadDocument(pdfUrl, false);
        });

        // Event listeners
        $('.travelz-fb-prev').on('click', function(e) {
            e.preventDefault();
            onPrevPage();
        });

        $('.travelz-fb-next').on('click', function(e) {
            e.preventDefault();
            onNextPage();
        });

        $('#travelz-fb-zoom-in').on('click', function(e) {
            e.preventDefault();
            if (zoomIndex < zoomLevels.length - 1) {
                zoomIndex++;
                applyZoom();
            }
        });

        $('#travelz-fb-zoom-out').on('click', function(e) {
            e.preventDefault();
            if (zoomIndex > 0) {
                zoomIndex--;
                applyZoom();
            }
        });

        // Smooth Drag-to-Pan inside stage when content overflows
        var isDragging = false;
        var startX = 0;
        var startY = 0;
        var startScrollLeft = 0;
        var startScrollTop = 0;

        $stage.on('mousedown', function(e) {
            if (e.which !== 1) return; // Left click only
            var el = $stage[0];
            if (!el || (el.scrollWidth <= el.clientWidth && el.scrollHeight <= el.clientHeight)) {
                return;
            }
            if ($(e.target).closest('button, a, input').length) return;

            isDragging = true;
            $stage.addClass('is-dragging');
            startX = e.pageX;
            startY = e.pageY;
            startScrollLeft = $stage.scrollLeft();
            startScrollTop = $stage.scrollTop();
            e.preventDefault();
        });

        $(document).on('mousemove', function(e) {
            if (!isDragging) return;
            var dx = e.pageX - startX;
            var dy = e.pageY - startY;
            $stage.scrollLeft(startScrollLeft - dx);
            $stage.scrollTop(startScrollTop - dy);
        });

        $(document).on('mouseup mouseleave', function() {
            if (isDragging) {
                isDragging = false;
                $stage.removeClass('is-dragging');
            }
        });

        // Debounced window resize handler
        var resizeTimer = null;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (pdfDoc && !pageRendering) {
                    renderPage(pageNum);
                }
            }, 250);
        });

        // Fullscreen toggle
        $('#travelz-fb-fullscreen').on('click', function(e) {
            e.preventDefault();
            $wrapper.toggleClass('is-fullscreen');
            var isFull = $wrapper.hasClass('is-fullscreen');
            if (isFull) {
                $('body').css('overflow', 'hidden');
                zoomIndex = Math.min(zoomLevels.length - 1, 2); // 100% on enter fullscreen
            } else {
                $('body').css('overflow', '');
                zoomIndex = 2; // reset to 100%
            }
            setTimeout(function() {
                applyZoom();
            }, 50);
        });

        // Keyboard arrows & shortcuts
        $(document).on('keydown', function(e) {
            if ($wrapper.hasClass('is-fullscreen')) {
                if (e.keyCode === 37) onPrevPage(); // Left arrow
                if (e.keyCode === 39) onNextPage(); // Right arrow
                if (e.keyCode === 27) { // Escape
                    $wrapper.removeClass('is-fullscreen');
                    $('body').css('overflow', '');
                    zoomIndex = 2;
                    setTimeout(function() {
                        applyZoom();
                    }, 50);
                }
                if (e.key === '+' || e.key === '=') {
                    if (zoomIndex < zoomLevels.length - 1) {
                        zoomIndex++;
                        applyZoom();
                    }
                }
                if (e.key === '-' || e.key === '_') {
                    if (zoomIndex > 0) {
                        zoomIndex--;
                        applyZoom();
                    }
                }
            }
        });

        // Initialize Zoom UI on load
        updateZoomUI();

    });
})(jQuery);
