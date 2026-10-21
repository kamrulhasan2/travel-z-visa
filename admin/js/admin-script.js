/**
 * Travel-Z Visa Admin JavaScript
 * Non-coder friendly interactivity
 */
(function($) {
    'use strict';

    $(document).ready(function() {

        // 1. Workspace Tab Switching
        $(document).on('click', '.travelz-wtab-btn', function(e) {
            e.preventDefault();
            var targetTab = $(this).data('tab');

            $('.travelz-wtab-btn').removeClass('active');
            $(this).addClass('active');

            $('.travelz-tab-panel').removeClass('active');
            $('#' + targetTab).addClass('active');
        });

        // 2. Quick Preset Tags Auto-Fill
        $(document).on('click', '.travelz-tag-btn', function(e) {
            e.preventDefault();
            var targetInputSelector = $(this).closest('.travelz-quick-tags').data('target');
            if (targetInputSelector) {
                $(targetInputSelector).val($(this).text().trim()).trigger('change');
            }
        });

        // 3. Quick Fee Presets (Adds row automatically)
        $(document).on('click', '.travelz-preset-fee', function(e) {
            e.preventDefault();
            var label = $(this).data('label');
            var amount = $(this).data('amount');
            var $tbody = $('#travelz-fees-body');
            var index = $tbody.find('.travelz-fee-row').length;

            // If first row is empty, overwrite it
            var $firstRow = $tbody.find('.travelz-fee-row').first();
            if ($tbody.find('.travelz-fee-row').length === 1 && !$firstRow.find('input[name*="[label]"]').val()) {
                $firstRow.find('input[name*="[label]"]').val(label);
                $firstRow.find('input[name*="[amount]"]').val(amount);
                return;
            }

            var rowHtml = '<tr class="travelz-fee-row">' +
                '<td><input type="text" name="travelz_fees[' + index + '][label]" value="' + label + '" class="travelz-input-regular" required /></td>' +
                '<td><input type="text" name="travelz_fees[' + index + '][amount]" value="' + amount + '" class="travelz-input-regular" required /></td>' +
                '<td><input type="text" name="travelz_fees[' + index + '][note]" value="" class="travelz-input-regular" placeholder="Optional note" /></td>' +
                '<td style="text-align: center;"><button type="button" class="travelz-del-btn travelz-remove-fee-btn"><span class="dashicons dashicons-trash"></span></button></td>' +
                '</tr>';

            $tbody.append(rowHtml);
        });

        // 4. Visa Fees Manual Add
        $('#travelz-add-fee-btn').on('click', function(e) {
            e.preventDefault();
            var $tbody = $('#travelz-fees-body');
            var index = $tbody.find('.travelz-fee-row').length;

            var rowHtml = '<tr class="travelz-fee-row">' +
                '<td><input type="text" name="travelz_fees[' + index + '][label]" value="" class="travelz-input-regular" placeholder="e.g. Double entry" required /></td>' +
                '<td><input type="text" name="travelz_fees[' + index + '][amount]" value="" class="travelz-input-regular" placeholder="e.g. 10900 BDT" required /></td>' +
                '<td><input type="text" name="travelz_fees[' + index + '][note]" value="" class="travelz-input-regular" placeholder="Optional note" /></td>' +
                '<td style="text-align: center;"><button type="button" class="travelz-del-btn travelz-remove-fee-btn"><span class="dashicons dashicons-trash"></span></button></td>' +
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

        // 5. WP Media Uploader for PDF Document
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
                $('#travelz-pdf-filename-display').text(attachment.filename || attachment.title || 'PDF Document');
                $('#travelz-pdf-status-text').text('PDF Document is connected and will display in the Flipbook.');
                $('#travelz-pdf-card').addClass('has-file');

                // Add or update preview & remove buttons if not present
                if (!$('#travelz-preview-pdf-btn').length) {
                    var btnsHtml = '<a href="' + attachment.url + '" target="_blank" class="button" id="travelz-preview-pdf-btn"><span class="dashicons dashicons-visibility"></span> View PDF</a> ' +
                        '<button type="button" class="button button-link-delete" id="travelz-remove-pdf-btn">Remove</button>';
                    $('.travelz-pdf-actions').append(btnsHtml);
                } else {
                    $('#travelz-preview-pdf-btn').attr('href', attachment.url);
                }
                $('#travelz_upload_pdf_btn').html('<span class="dashicons dashicons-upload"></span> Change / Replace PDF');
            });

            pdfFrame.open();
        });

        // Remove PDF
        $(document).on('click', '#travelz-remove-pdf-btn', function(e) {
            e.preventDefault();
            $('#travelz_visa_pdf_url').val('');
            $('#travelz-pdf-filename-display').text('No PDF file selected yet');
            $('#travelz-pdf-status-text').text('Please click the button to select or upload a PDF from your computer.');
            $('#travelz-pdf-card').removeClass('has-file');
            $('#travelz-preview-pdf-btn').remove();
            $(this).remove();
            $('#travelz_upload_pdf_btn').html('<span class="dashicons dashicons-upload"></span> Select / Upload PDF');
        });

        // 6. WP Media Uploader for Country Flag
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
                $('#travelz-flag-preview').html('<img src="' + attachment.url + '" alt="Flag" />');
                if (!$('#travelz-remove-flag-btn').length) {
                    $('#travelz_upload_flag_btn').after('<button type="button" class="button button-link-delete" id="travelz-remove-flag-btn">Remove</button>');
                }
            });

            imgFrame.open();
        });

        // Remove Flag
        $(document).on('click', '#travelz-remove-flag-btn', function(e) {
            e.preventDefault();
            $('#travelz_visa_flag_url').val('');
            $('#travelz-flag-preview').html('<span class="dashicons dashicons-flag" style="font-size: 36px; color: #cbd5e1;"></span>');
            $(this).remove();
        });

        // 7. FAQs Repeater
        $('#travelz-add-faq-btn').on('click', function(e) {
            e.preventDefault();
            var $wrapper = $('#travelz-faqs-wrapper');
            var index = $wrapper.find('.travelz-faq-card').length;

            var faqHtml = '<div class="travelz-faq-card">' +
                '<div class="travelz-faq-card-header">' +
                '<span class="travelz-faq-num"><span class="dashicons dashicons-editor-help"></span> প্রশ্ন #' + (index + 1) + ' (Question)</span>' +
                '<button type="button" class="travelz-del-btn travelz-remove-faq-btn" title="মুছে ফেলুন"><span class="dashicons dashicons-trash"></span></button>' +
                '</div>' +
                '<div class="travelz-faq-fields">' +
                '<input type="text" name="travelz_faqs[' + index + '][q]" value="" class="travelz-input-regular" placeholder="প্রশ্ন লিখুন (e.g. Do I need an interview for China Visa?)" />' +
                '<textarea name="travelz_faqs[' + index + '][a]" rows="2" class="travelz-textarea" placeholder="উত্তর লিখুন..."></textarea>' +
                '</div>' +
                '</div>';

            $wrapper.append(faqHtml);
        });

        $(document).on('click', '.travelz-remove-faq-btn', function(e) {
            e.preventDefault();
            $(this).closest('.travelz-faq-card').remove();
            // Re-index remaining FAQs
            $('#travelz-faqs-wrapper .travelz-faq-card').each(function(i) {
                $(this).find('.travelz-faq-num').html('<span class="dashicons dashicons-editor-help"></span> প্রশ্ন #' + (i + 1) + ' (Question)');
            });
        });

    });
})(jQuery);
