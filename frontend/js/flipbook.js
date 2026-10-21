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
        var scale = 1.0;
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        var $canvasArea = $('#travelz-flipbook-canvas-area');

        // Create page sheet
        var $sheet = $('<div class="travelz-page-sheet"></div>');
        $sheet.append(canvas);

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
            $('#travelz-native-pdf-embed').show();
            $('#travelz-fb-current-page, .travelz-cur-page-num').text('1');
            $('#travelz-fb-total-pages, .travelz-tot-page-num').text('1');
        }

        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function(page) {
                var viewport = page.getViewport({ scale: scale });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                var renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                var renderTask = page.render(renderContext);

                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
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
            queueRenderPage(pageNum);
        }

        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            playFlipSound();
            queueRenderPage(pageNum);
        }

        // Initialize document loading
        loadPdfJs(function() {
            window.pdfjsLib.getDocument(pdfUrl).promise.then(function(doc) {
                pdfDoc = doc;
                $('#travelz-fb-total-pages, .travelz-tot-page-num').text(pdfDoc.numPages);
                $canvasArea.empty().append($sheet);

                // Auto-adjust scale based on viewport width
                var containerWidth = $('#travelz-book-viewport').width() || 600;
                scale = Math.min(1.4, Math.max(0.7, (containerWidth / 650)));

                renderPage(pageNum);
            }).catch(function(err) {
                console.warn('PDF.js render error, falling back to iframe embed:', err);
                showFallbackEmbed();
            });
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
            if (pdfDoc) {
                if (scale >= 2.0) return;
                scale += 0.2;
                queueRenderPage(pageNum);
            } else {
                var $iframe = $('#travelz-native-pdf-embed');
                if ($iframe.length) {
                    $iframe.height(Math.min(1200, $iframe.height() + 120));
                }
            }
        });

        $('#travelz-fb-zoom-out').on('click', function(e) {
            e.preventDefault();
            if (pdfDoc) {
                if (scale <= 0.6) return;
                scale -= 0.2;
                queueRenderPage(pageNum);
            } else {
                var $iframe = $('#travelz-native-pdf-embed');
                if ($iframe.length) {
                    $iframe.height(Math.max(450, $iframe.height() - 120));
                }
            }
        });

        // Fullscreen toggle
        $('#travelz-fb-fullscreen').on('click', function(e) {
            e.preventDefault();
            $wrapper.toggleClass('is-fullscreen');
            var isFull = $wrapper.hasClass('is-fullscreen');
            if (isFull) {
                $('body').css('overflow', 'hidden');
                scale = 1.3;
                $('#travelz-native-pdf-embed').css('height', 'calc(100vh - 120px)');
            } else {
                $('body').css('overflow', '');
                scale = 1.0;
                $('#travelz-native-pdf-embed').css('height', '600px');
            }
            if (pdfDoc) {
                queueRenderPage(pageNum);
            }
        });

        // Keyboard arrows
        $(document).on('keydown', function(e) {
            if ($wrapper.hasClass('is-fullscreen')) {
                if (e.keyCode === 37) onPrevPage(); // Left arrow
                if (e.keyCode === 39) onNextPage(); // Right arrow
                if (e.keyCode === 27) { // Escape
                    $wrapper.removeClass('is-fullscreen');
                    $('body').css('overflow', '');
                    scale = 1.0;
                    if (pdfDoc) queueRenderPage(pageNum);
                }
            }
        });

    });
})(jQuery);
