/**
 * Travel-Z Visa Admin JavaScript
 */
(function($) {
    'use strict';

    $(document).ready(function() {

        // 1. WP Media Uploader for PDF Document
        $('#travelz_upload_pdf_btn').on('click', function(e) {
            e.preventDefault();
            var pdfFrame = wp.media({
                title: (window.travelzAdmin && window.travelzAdmin.choosePdf) || 'Select or Upload Visa PDF',
                button: {
                    text: (window.travelzAdmin && window.travelzAdmin.usePdf) || 'Use this PDF'
                },
                multiple: false,
                library: {
                    type: 'application/pdf'
                }
            });

            pdfFrame.on('select', function() {
                var attachment = pdfFrame.state().get('selection').first().toJSON();
                $('#travelz_visa_pdf_url').val(attachment.url);
            });

            pdfFrame.open();
        });

        // 2. WP Media Uploader for Flag / Icon
        $('#travelz_upload_flag_btn').on('click', function(e) {
            e.preventDefault();
            var imgFrame = wp.media({
                title: (window.travelzAdmin && window.travelzAdmin.chooseFlag) || 'Select or Upload Country Flag',
                button: {
                    text: (window.travelzAdmin && window.travelzAdmin.useFlag) || 'Use as Flag'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            imgFrame.on('select', function() {
                var attachment = imgFrame.state().get('selection').first().toJSON();
                $('#travelz_visa_flag_url').val(attachment.url);
            });

            imgFrame.open();
        });

        // 2b. WP Media Uploader for Hero Background Image (Settings Page)
        $('#travelz_upload_hero_bg_btn').on('click', function(e) {
            e.preventDefault();
            var heroFrame = wp.media({
                title: 'Select or Upload Hero Background Image',
                button: {
                    text: 'Use as Hero Background'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            heroFrame.on('select', function() {
                var attachment = heroFrame.state().get('selection').first().toJSON();
                $('#travelz_hero_bg_image').val(attachment.url);
                $('#travelz-hero-bg-preview').html('<img src="' + attachment.url + '" style="width: 100%; height: 100%; object-fit: cover;" alt="" />');
                $('#travelz_remove_hero_bg_btn').show();
            });

            heroFrame.open();
        });

        $('#travelz_remove_hero_bg_btn').on('click', function(e) {
            e.preventDefault();
            $('#travelz_hero_bg_image').val('');
            $('#travelz-hero-bg-preview').html('<span style="font-size: 11px; color: #94a3b8; text-align: center; padding: 4px;">Default (Clean White)</span>');
            $(this).hide();
        });

        // 3. Visa Fees Table Repeater
        $('#travelz-add-fee-btn').on('click', function(e) {
            e.preventDefault();
            var $tbody = $('#travelz-fees-body');
            var index = $tbody.find('.travelz-fee-row').length;

            var rowHtml = '<tr class="travelz-fee-row">' +
                '<td><input type="text" name="travelz_fees[' + index + '][label]" value="" class="widefat" placeholder="e.g. Double entry" required /></td>' +
                '<td><input type="text" name="travelz_fees[' + index + '][amount]" value="" class="widefat" placeholder="e.g. 10900 BDT" required /></td>' +
                '<td><input type="text" name="travelz_fees[' + index + '][note]" value="" class="widefat" placeholder="Optional note" /></td>' +
                '<td style="text-align: center;"><button type="button" class="button button-link-delete travelz-remove-fee-btn"><span class="dashicons dashicons-trash"></span></button></td>' +
                '</tr>';

            $tbody.append(rowHtml);
        });

        $(document).on('click', '.travelz-remove-fee-btn', function(e) {
            e.preventDefault();
            var $tbody = $('#travelz-fees-body');
            if ($tbody.find('.travelz-fee-row').length > 1) {
                $(this).closest('.travelz-fee-row').remove();
            } else {
                $(this).closest('.travelz-fee-row').find('input').val('');
            }
        });

        // 4. FAQs Repeater
        $('#travelz-add-faq-btn').on('click', function(e) {
            e.preventDefault();
            var $wrapper = $('#travelz-faqs-wrapper');
            var index = $wrapper.find('.travelz-faq-item').length;

            var faqHtml = '<div class="travelz-faq-item" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-bottom: 10px;">' +
                '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">' +
                '<strong>FAQ #' + (index + 1) + '</strong>' +
                '<button type="button" class="button button-link-delete travelz-remove-faq-btn"><span class="dashicons dashicons-trash"></span></button>' +
                '</div>' +
                '<input type="text" name="travelz_faqs[' + index + '][q]" value="" class="widefat" placeholder="Question..." style="margin-bottom: 6px;" />' +
                '<textarea name="travelz_faqs[' + index + '][a]" rows="2" class="widefat" placeholder="Answer..."></textarea>' +
                '</div>';

            $wrapper.append(faqHtml);
        });

        $(document).on('click', '.travelz-remove-faq-btn', function(e) {
            e.preventDefault();
            $(this).closest('.travelz-faq-item').remove();
        });

    });
})(jQuery);
