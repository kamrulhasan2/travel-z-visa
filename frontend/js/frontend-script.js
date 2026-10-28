/**
 * Travel-Z Visa Frontend Script
 */
(function($) {
    'use strict';

    $(document).ready(function() {

        // 1. Checklist Tabs
        $('.travelz-tab-btn').on('click', function(e) {
            e.preventDefault();
            var targetId = $(this).data('target');
            var $parent = $(this).closest('.travelz-checklist-section');

            $parent.find('.travelz-tab-btn').removeClass('active');
            $(this).addClass('active');

            $parent.find('.travelz-tab-pane').removeClass('active');
            $('#' + targetId).addClass('active');
        });

        // 2. FAQs Accordion
        $('.travelz-accordion-header').on('click', function(e) {
            e.preventDefault();
            var $item = $(this).closest('.travelz-accordion-item');
            var $body = $item.find('.travelz-accordion-body');
            var isOpen = $item.hasClass('open');

            if (isOpen) {
                $item.removeClass('open');
                $body.slideUp(200);
                $(this).attr('aria-expanded', 'false');
            } else {
                $item.addClass('open');
                $body.slideDown(200);
                $(this).attr('aria-expanded', 'true');
            }
        });

        // 3. Quick Search / Autocomplete in Country Switcher
        var $quickInput = $('#travelz-quick-search');
        var $quickResults = $('#travelz-quick-results');

        if ($quickInput.length) {
            var $select = $('#travelz-country-dropdown');
            var countries = [];

            $select.find('option').each(function() {
                var url = $(this).val();
                var text = $(this).text().trim();
                if (url && text) {
                    countries.push({ title: text, url: url });
                }
            });

            $quickInput.on('input', function() {
                var val = $(this).val().toLowerCase().trim();
                if (val.length < 1) {
                    $quickResults.hide().empty();
                    return;
                }

                var matches = countries.filter(function(c) {
                    return c.title.toLowerCase().indexOf(val) > -1;
                });

                if (matches.length > 0) {
                    var html = '<ul>';
                    matches.forEach(function(m) {
                        html += '<li data-url="' + m.url + '"><span class="dashicons dashicons-location" style="color: #0ebac5; font-size: 16px;"></span> ' + m.title + '</li>';
                    });
                    html += '</ul>';
                    $quickResults.html(html).show();
                } else {
                    $quickResults.html('<div style="padding: 10px 14px; font-size: 13px; color: #94a3b8;">No matching country</div>').show();
                }
            });

            $(document).on('click', '#travelz-quick-results li', function() {
                var url = $(this).data('url');
                if (url) {
                    window.location.href = url;
                }
            });

            $('#travelz-quick-search-btn').on('click', function() {
                var first = $quickResults.find('li').first();
                if (first.length && first.data('url')) {
                    window.location.href = first.data('url');
                } else {
                    var val = $quickInput.val().toLowerCase().trim();
                    var found = countries.find(function(c) { return c.title.toLowerCase() === val; });
                    if (found) {
                        window.location.href = found.url;
                    }
                }
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.travelz-instant-search-wrap').length) {
                    $quickResults.hide();
                }
            });
        }

        // 4. Region Filters on Visa Hub Page
        $('.travelz-region-btn').on('click', function(e) {
            e.preventDefault();
            $('.travelz-region-btn').removeClass('active');
            $(this).addClass('active');

            // Smoothly center the active pill in horizontal scroll on mobile
            var container = $(this).closest('.travelz-region-filters')[0];
            if (container && container.scrollWidth > container.clientWidth) {
                var btnLeft = this.offsetLeft;
                var btnWidth = this.offsetWidth;
                var containerWidth = container.clientWidth;
                container.scrollTo({
                    left: btnLeft - (containerWidth / 2) + (btnWidth / 2),
                    behavior: 'smooth'
                });
            }

            var region = $(this).data('region');
            filterCountriesGrid();
        });

        // 5. Hub Search Input Filter & Explore Button
        $('#travelz-hub-filter-input').on('input', function() {
            filterCountriesGrid();
        });

        $('.travelz-hub-search-btn').on('click', function(e) {
            e.preventDefault();
            var $grid = $('#travelz-countries-grid');
            if ($grid.length) {
                $('html, body').animate({
                    scrollTop: $grid.offset().top - 90
                }, 400);
            }
        });

        function filterCountriesGrid() {
            var activeRegion = $('.travelz-region-btn.active').data('region') || 'all';
            var query = ($('#travelz-hub-filter-input').val() || '').toLowerCase().trim();
            var visibleCount = 0;

            $('.travelz-country-card').each(function() {
                var title = $(this).data('title') || '';
                var regions = ($(this).data('regions') || '').split(' ');

                var matchesRegion = (activeRegion === 'all') || (regions.indexOf(activeRegion) > -1);
                var matchesQuery = (query === '') || (title.indexOf(query) > -1);

                if (matchesRegion && matchesQuery) {
                    $(this).fadeIn(200);
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });

            if (visibleCount === 0) {
                $('#travelz-no-results-msg').show();
            } else {
                $('#travelz-no-results-msg').hide();
            }
        }

    });
})(jQuery);
