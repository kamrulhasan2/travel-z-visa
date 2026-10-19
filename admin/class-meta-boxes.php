<?php
/**
 * Custom Meta Boxes for Visa Post Type
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TravelZ_Visa_Meta_Boxes {

    /**
     * Initialize.
     */
    public static function init() {
        add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
        add_action( 'save_post_' . TravelZ_Visa_Post_Type::POST_TYPE, array( __CLASS__, 'save_meta' ), 10, 2 );
    }

    /**
     * Register meta boxes.
     */
    public static function add_meta_boxes() {
        // 1. Overview & Key Details
        add_meta_box(
            'travelz_visa_overview_box',
            __( 'Visa Overview & Pricing', 'travel-z-visa' ),
            array( __CLASS__, 'render_overview_box' ),
            TravelZ_Visa_Post_Type::POST_TYPE,
            'normal',
            'high'
        );

        // 2. Requirement Document & Flipbook
        add_meta_box(
            'travelz_visa_document_box',
            __( 'Requirement Document & Flipbook Viewer', 'travel-z-visa' ),
            array( __CLASS__, 'render_document_box' ),
            TravelZ_Visa_Post_Type::POST_TYPE,
            'normal',
            'high'
        );

        // 3. Document Checklist (SEO Booster)
        add_meta_box(
            'travelz_visa_checklist_box',
            __( 'Document Checklist (Categories for SEO)', 'travel-z-visa' ),
            array( __CLASS__, 'render_checklist_box' ),
            TravelZ_Visa_Post_Type::POST_TYPE,
            'normal',
            'default'
        );

        // 4. FAQs
        add_meta_box(
            'travelz_visa_faqs_box',
            __( 'Frequently Asked Questions (FAQ Schema)', 'travel-z-visa' ),
            array( __CLASS__, 'render_faqs_box' ),
            TravelZ_Visa_Post_Type::POST_TYPE,
            'normal',
            'default'
        );

        // 5. Sidebar: Country & Query Options
        add_meta_box(
            'travelz_visa_sidebar_box',
            __( 'Inquiry & Quick Actions', 'travel-z-visa' ),
            array( __CLASS__, 'render_sidebar_box' ),
            TravelZ_Visa_Post_Type::POST_TYPE,
            'side',
            'default'
        );
    }

    /**
     * Render Overview & Pricing Box.
     */
    public static function render_overview_box( $post ) {
        wp_nonce_field( 'travelz_visa_meta_nonce', 'travelz_visa_nonce' );

        $visa_type        = get_post_meta( $post->ID, '_travelz_visa_type', true );
        $duration         = get_post_meta( $post->ID, '_travelz_visa_duration', true );
        $processing_time  = get_post_meta( $post->ID, '_travelz_visa_processing_time', true );
        $fees             = get_post_meta( $post->ID, '_travelz_visa_fees', true );
        $fees             = is_array( $fees ) ? $fees : array();
        ?>
        <div class="travelz-meta-field-group">
            <div class="travelz-meta-row">
                <div class="travelz-col-6">
                    <label for="travelz_visa_type"><strong><?php esc_html_e( 'Type of Visa', 'travel-z-visa' ); ?>:</strong></label>
                    <input type="text" id="travelz_visa_type" name="travelz_visa_type" value="<?php echo esc_attr( $visa_type ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. Tourist Sticker visa, E-Visa', 'travel-z-visa' ); ?>" />
                    <p class="description"><?php esc_html_e( 'The category or type of this visa.', 'travel-z-visa' ); ?></p>
                </div>
                <div class="travelz-col-6">
                    <label for="travelz_visa_duration"><strong><?php esc_html_e( 'Visa Duration / Validity', 'travel-z-visa' ); ?>:</strong></label>
                    <input type="text" id="travelz_visa_duration" name="travelz_visa_duration" value="<?php echo esc_attr( $duration ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. 3 months single Entry', 'travel-z-visa' ); ?>" />
                    <p class="description"><?php esc_html_e( 'Visa validity period and entry type.', 'travel-z-visa' ); ?></p>
                </div>
            </div>

            <div class="travelz-meta-row">
                <div class="travelz-col-12">
                    <label for="travelz_visa_processing_time"><strong><?php esc_html_e( 'File Processing Time (Travel Z)', 'travel-z-visa' ); ?>:</strong></label>
                    <input type="text" id="travelz_visa_processing_time" name="travelz_visa_processing_time" value="<?php echo esc_attr( $processing_time ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. 10-15 working days (Approx.)', 'travel-z-visa' ); ?>" />
                    <p class="description"><?php esc_html_e( 'Estimated working days required to process the file.', 'travel-z-visa' ); ?></p>
                </div>
            </div>
        </div>

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #e2e8f0;" />

        <div class="travelz-fees-wrapper">
            <label><strong><?php esc_html_e( 'Visa Fees Structure (Multi-Tier)', 'travel-z-visa' ); ?>:</strong></label>
            <p class="description"><?php esc_html_e( 'Add all fee tiers for this country (e.g. 1st time, 2nd time, Double entry, 1 year multiple, etc.).', 'travel-z-visa' ); ?></p>

            <table class="widefat travelz-fees-table" id="travelz-fees-table">
                <thead>
                    <tr>
                        <th style="width: 35%;"><?php esc_html_e( 'Tier / Entry Label', 'travel-z-visa' ); ?></th>
                        <th style="width: 35%;"><?php esc_html_e( 'Fee (e.g. 9500 BDT)', 'travel-z-visa' ); ?></th>
                        <th style="width: 20%;"><?php esc_html_e( 'Note (Optional)', 'travel-z-visa' ); ?></th>
                        <th style="width: 10%; text-align: center;"><?php esc_html_e( 'Action', 'travel-z-visa' ); ?></th>
                    </tr>
                </thead>
                <tbody id="travelz-fees-body">
                    <?php
                    if ( ! empty( $fees ) ) :
                        foreach ( $fees as $index => $fee ) :
                            $label  = isset( $fee['label'] ) ? $fee['label'] : '';
                            $amount = isset( $fee['amount'] ) ? $fee['amount'] : '';
                            $note   = isset( $fee['note'] ) ? $fee['note'] : '';
                            ?>
                            <tr class="travelz-fee-row">
                                <td>
                                    <input type="text" name="travelz_fees[<?php echo esc_attr( $index ); ?>][label]" value="<?php echo esc_attr( $label ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. 1st time, Double entry', 'travel-z-visa' ); ?>" required />
                                </td>
                                <td>
                                    <input type="text" name="travelz_fees[<?php echo esc_attr( $index ); ?>][amount]" value="<?php echo esc_attr( $amount ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. 9500 BDT', 'travel-z-visa' ); ?>" required />
                                </td>
                                <td>
                                    <input type="text" name="travelz_fees[<?php echo esc_attr( $index ); ?>][note]" value="<?php echo esc_attr( $note ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. Including service fee', 'travel-z-visa' ); ?>" />
                                </td>
                                <td style="text-align: center;">
                                    <button type="button" class="button button-link-delete travelz-remove-fee-btn"><span class="dashicons dashicons-trash"></span></button>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    else :
                        ?>
                        <tr class="travelz-fee-row">
                            <td>
                                <input type="text" name="travelz_fees[0][label]" value="" class="widefat" placeholder="e.g. 1st time" />
                            </td>
                            <td>
                                <input type="text" name="travelz_fees[0][amount]" value="" class="widefat" placeholder="e.g. 9500 BDT" />
                            </td>
                            <td>
                                <input type="text" name="travelz_fees[0][note]" value="" class="widefat" placeholder="Optional note" />
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="button button-link-delete travelz-remove-fee-btn"><span class="dashicons dashicons-trash"></span></button>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div style="margin-top: 10px;">
                <button type="button" class="button button-secondary" id="travelz-add-fee-btn">
                    <span class="dashicons dashicons-plus-alt2" style="vertical-align: middle;"></span> <?php esc_html_e( 'Add Another Fee Tier', 'travel-z-visa' ); ?>
                </button>
            </div>
        </div>
        <?php
    }

    /**
     * Render Document & Flipbook Box.
     */
    public static function render_document_box( $post ) {
        $pdf_url         = get_post_meta( $post->ID, '_travelz_visa_pdf_url', true );
        $viewer_type     = get_post_meta( $post->ID, '_travelz_visa_viewer_type', true );
        $enable_download = get_post_meta( $post->ID, '_travelz_visa_enable_download', true );
        $download_text   = get_post_meta( $post->ID, '_travelz_visa_download_text', true );

        if ( empty( $viewer_type ) ) {
            $viewer_type = 'flipbook';
        }
        if ( '' === $enable_download ) {
            $enable_download = '1';
        }
        if ( empty( $download_text ) ) {
            $download_text = __( 'Download Requirement PDF', 'travel-z-visa' );
        }
        ?>
        <div class="travelz-meta-field-group">
            <div class="travelz-meta-row">
                <div class="travelz-col-12">
                    <label for="travelz_visa_pdf_url"><strong><?php esc_html_e( 'Visa Requirement PDF Document', 'travel-z-visa' ); ?>:</strong></label>
                    <div style="display: flex; gap: 10px; margin-top: 5px;">
                        <input type="text" id="travelz_visa_pdf_url" name="travelz_visa_pdf_url" value="<?php echo esc_url( $pdf_url ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Upload or enter PDF document URL...', 'travel-z-visa' ); ?>" />
                        <button type="button" class="button button-secondary" id="travelz_upload_pdf_btn">
                            <span class="dashicons dashicons-upload" style="vertical-align: middle;"></span> <?php esc_html_e( 'Select / Upload PDF', 'travel-z-visa' ); ?>
                        </button>
                    </div>
                    <p class="description"><?php esc_html_e( 'Select the PDF file from WordPress Media Library. This document will be rendered inside the interactive flipbook viewer on the frontend.', 'travel-z-visa' ); ?></p>
                </div>
            </div>

            <div class="travelz-meta-row" style="margin-top: 15px;">
                <div class="travelz-col-6">
                    <label for="travelz_visa_viewer_type"><strong><?php esc_html_e( 'Viewer Display Mode', 'travel-z-visa' ); ?>:</strong></label>
                    <select id="travelz_visa_viewer_type" name="travelz_visa_viewer_type" class="widefat">
                        <option value="flipbook" <?php selected( $viewer_type, 'flipbook' ); ?>><?php esc_html_e( 'Interactive 3D Flipbook (Realistic Page Turn)', 'travel-z-visa' ); ?></option>
                        <option value="modern_reader" <?php selected( $viewer_type, 'modern_reader' ); ?>><?php esc_html_e( 'Modern Document Reader (Continuous Scroll)', 'travel-z-visa' ); ?></option>
                        <option value="download_only" <?php selected( $viewer_type, 'download_only' ); ?>><?php esc_html_e( 'Download Card Only (No Embed Viewer)', 'travel-z-visa' ); ?></option>
                    </select>
                </div>
                <div class="travelz-col-6">
                    <label for="travelz_visa_download_text"><strong><?php esc_html_e( 'Download Button Label', 'travel-z-visa' ); ?>:</strong></label>
                    <input type="text" id="travelz_visa_download_text" name="travelz_visa_download_text" value="<?php echo esc_attr( $download_text ); ?>" class="widefat" />
                    <div style="margin-top: 8px;">
                        <label>
                            <input type="checkbox" name="travelz_visa_enable_download" value="1" <?php checked( $enable_download, '1' ); ?> />
                            <?php esc_html_e( 'Show "Download PDF" button to visitors', 'travel-z-visa' ); ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render Checklist Box (SEO Booster).
     */
    public static function render_checklist_box( $post ) {
        $checklist = get_post_meta( $post->ID, '_travelz_visa_checklist', true );
        $checklist = is_array( $checklist ) ? $checklist : array();

        $categories = array(
            'general'    => array(
                'label'       => __( 'General Documents (Required for all applicants)', 'travel-z-visa' ),
                'placeholder' => "1. Original Passport with minimum 6 months validity\n2. Two recent passport size photographs (35mm x 45mm, white background)\n3. Bank statement for last 6 months with minimum balance & Bank Solvency Certificate\n4. National ID Card (NID) copy or Birth Certificate",
            ),
            'employed'   => array(
                'label'       => __( 'For Job Holders / Employed', 'travel-z-visa' ),
                'placeholder' => "1. No Objection Certificate (NOC) from employer\n2. Office Visiting Card & ID Card copy\n3. Salary certificate or salary pay slips for last 3-6 months\n4. Government Order (GO) for government employees",
            ),
            'business'   => array(
                'label'       => __( 'For Business Persons', 'travel-z-visa' ),
                'placeholder' => "1. Trade License (renewed copy with English translation & notary)\n2. Company Memorandum of Article & Certificate of Incorporation (if limited company)\n3. Official Company Letterhead Pad & Visiting Card\n4. Company Bank Statement (last 6 months)",
            ),
            'student'    => array(
                'label'       => __( 'For Students', 'travel-z-visa' ),
                'placeholder' => "1. Student ID Card copy\n2. Leave approval letter from School / College / University\n3. Parents' financial documents & sponsorship letter",
            ),
            'additional' => array(
                'label'       => __( 'Additional / Supporting Documents', 'travel-z-visa' ),
                'placeholder' => "1. Marriage Certificate (if traveling with spouse)\n2. Air ticket booking & Hotel accommodation reservation\n3. Travel itinerary / day-to-day plan",
            ),
        );
        ?>
        <p class="description">
            <?php esc_html_e( 'Enter required documents line-by-line for each applicant category. These will appear in a neat tabbed checklist under the visa overview and will be indexed by Google for powerful SEO ranking.', 'travel-z-visa' ); ?>
        </p>

        <div class="travelz-checklist-tabs">
            <?php foreach ( $categories as $key => $cat ) :
                $val = isset( $checklist[ $key ] ) ? $checklist[ $key ] : '';
                ?>
                <div style="margin-bottom: 16px;">
                    <label for="travelz_doc_<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $cat['label'] ); ?>:</strong></label>
                    <textarea id="travelz_doc_<?php echo esc_attr( $key ); ?>" name="travelz_checklist[<?php echo esc_attr( $key ); ?>]" rows="4" class="widefat" placeholder="<?php echo esc_attr( $cat['placeholder'] ); ?>"><?php echo esc_textarea( $val ); ?></textarea>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Render FAQs Box.
     */
    public static function render_faqs_box( $post ) {
        $faqs = get_post_meta( $post->ID, '_travelz_visa_faqs', true );
        $faqs = is_array( $faqs ) ? $faqs : array();
        ?>
        <p class="description"><?php esc_html_e( 'Add country-specific frequently asked questions. These questions generate Google FAQPage Schema automatically.', 'travel-z-visa' ); ?></p>
        
        <div id="travelz-faqs-wrapper">
            <?php
            if ( ! empty( $faqs ) ) :
                foreach ( $faqs as $i => $faq ) :
                    $q = isset( $faq['q'] ) ? $faq['q'] : '';
                    $a = isset( $faq['a'] ) ? $faq['a'] : '';
                    ?>
                    <div class="travelz-faq-item" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong><?php printf( esc_html__( 'FAQ #%d', 'travel-z-visa' ), $i + 1 ); ?></strong>
                            <button type="button" class="button button-link-delete travelz-remove-faq-btn"><span class="dashicons dashicons-trash"></span></button>
                        </div>
                        <input type="text" name="travelz_faqs[<?php echo esc_attr( $i ); ?>][q]" value="<?php echo esc_attr( $q ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Question (e.g. Do I need an interview for China Visa?)', 'travel-z-visa' ); ?>" style="margin-bottom: 6px;" />
                        <textarea name="travelz_faqs[<?php echo esc_attr( $i ); ?>][a]" rows="2" class="widefat" placeholder="<?php esc_attr_e( 'Answer...', 'travel-z-visa' ); ?>"><?php echo esc_textarea( $a ); ?></textarea>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>
        </div>

        <button type="button" class="button button-secondary" id="travelz-add-faq-btn">
            <span class="dashicons dashicons-plus-alt2" style="vertical-align: middle;"></span> <?php esc_html_e( 'Add New FAQ', 'travel-z-visa' ); ?>
        </button>
        <?php
    }

    /**
     * Render Sidebar Options Box.
     */
    public static function render_sidebar_box( $post ) {
        $flag_url    = get_post_meta( $post->ID, '_travelz_visa_flag_url', true );
        $query_url   = get_post_meta( $post->ID, '_travelz_visa_custom_query_url', true );
        $whatsapp_no = get_post_meta( $post->ID, '_travelz_visa_custom_whatsapp', true );
        ?>
        <div style="margin-bottom: 12px;">
            <label for="travelz_visa_flag_url"><strong><?php esc_html_e( 'Country Flag URL / Icon', 'travel-z-visa' ); ?>:</strong></label>
            <div style="display: flex; gap: 6px; margin-top: 4px;">
                <input type="text" id="travelz_visa_flag_url" name="travelz_visa_flag_url" value="<?php echo esc_url( $flag_url ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Flag image URL', 'travel-z-visa' ); ?>" />
                <button type="button" class="button button-secondary" id="travelz_upload_flag_btn"><span class="dashicons dashicons-format-image"></span></button>
            </div>
            <p class="description"><?php esc_html_e( 'Shown beside country title and in search results.', 'travel-z-visa' ); ?></p>
        </div>

        <div style="margin-bottom: 12px;">
            <label for="travelz_visa_custom_query_url"><strong><?php esc_html_e( 'Custom Query URL', 'travel-z-visa' ); ?>:</strong></label>
            <input type="text" id="travelz_visa_custom_query_url" name="travelz_visa_custom_query_url" value="<?php echo esc_url( $query_url ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Default: /queries/', 'travel-z-visa' ); ?>" />
            <p class="description"><?php esc_html_e( 'Leave empty to use global setting.', 'travel-z-visa' ); ?></p>
        </div>

        <div style="margin-bottom: 12px;">
            <label for="travelz_visa_custom_whatsapp"><strong><?php esc_html_e( 'Custom WhatsApp No.', 'travel-z-visa' ); ?>:</strong></label>
            <input type="text" id="travelz_visa_custom_whatsapp" name="travelz_visa_custom_whatsapp" value="<?php echo esc_attr( $whatsapp_no ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. +8801711258451', 'travel-z-visa' ); ?>" />
            <p class="description"><?php esc_html_e( 'Leave empty to use global WhatsApp.', 'travel-z-visa' ); ?></p>
        </div>
        <?php
    }

    /**
     * Save Meta Fields.
     */
    public static function save_meta( $post_id, $post ) {
        // Nonce check
        if ( ! isset( $_POST['travelz_visa_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['travelz_visa_nonce'] ), 'travelz_visa_meta_nonce' ) ) {
            return;
        }

        // Autosave check
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Permission check
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // 1. Overview Fields
        if ( isset( $_POST['travelz_visa_type'] ) ) {
            update_post_meta( $post_id, '_travelz_visa_type', sanitize_text_field( wp_unslash( $_POST['travelz_visa_type'] ) ) );
        }
        if ( isset( $_POST['travelz_visa_duration'] ) ) {
            update_post_meta( $post_id, '_travelz_visa_duration', sanitize_text_field( wp_unslash( $_POST['travelz_visa_duration'] ) ) );
        }
        if ( isset( $_POST['travelz_visa_processing_time'] ) ) {
            update_post_meta( $post_id, '_travelz_visa_processing_time', sanitize_text_field( wp_unslash( $_POST['travelz_visa_processing_time'] ) ) );
        }

        // 2. Fees Repeater
        if ( isset( $_POST['travelz_fees'] ) && is_array( $_POST['travelz_fees'] ) ) {
            $sanitized_fees = array();
            foreach ( $_POST['travelz_fees'] as $fee ) {
                $label  = isset( $fee['label'] ) ? sanitize_text_field( wp_unslash( $fee['label'] ) ) : '';
                $amount = isset( $fee['amount'] ) ? sanitize_text_field( wp_unslash( $fee['amount'] ) ) : '';
                $note   = isset( $fee['note'] ) ? sanitize_text_field( wp_unslash( $fee['note'] ) ) : '';

                if ( ! empty( $label ) || ! empty( $amount ) ) {
                    $sanitized_fees[] = array(
                        'label'  => $label,
                        'amount' => $amount,
                        'note'   => $note,
                    );
                }
            }
            update_post_meta( $post_id, '_travelz_visa_fees', $sanitized_fees );
        } else {
            delete_post_meta( $post_id, '_travelz_visa_fees' );
        }

        // 3. Document / Flipbook Fields
        if ( isset( $_POST['travelz_visa_pdf_url'] ) ) {
            update_post_meta( $post_id, '_travelz_visa_pdf_url', esc_url_raw( wp_unslash( $_POST['travelz_visa_pdf_url'] ) ) );
        }
        if ( isset( $_POST['travelz_visa_viewer_type'] ) ) {
            update_post_meta( $post_id, '_travelz_visa_viewer_type', sanitize_key( wp_unslash( $_POST['travelz_visa_viewer_type'] ) ) );
        }
        $enable_download = isset( $_POST['travelz_visa_enable_download'] ) ? '1' : '0';
        update_post_meta( $post_id, '_travelz_visa_enable_download', $enable_download );

        if ( isset( $_POST['travelz_visa_download_text'] ) ) {
            update_post_meta( $post_id, '_travelz_visa_download_text', sanitize_text_field( wp_unslash( $_POST['travelz_visa_download_text'] ) ) );
        }

        // 4. Checklist Fields
        if ( isset( $_POST['travelz_checklist'] ) && is_array( $_POST['travelz_checklist'] ) ) {
            $sanitized_checklist = array();
            foreach ( $_POST['travelz_checklist'] as $key => $text ) {
                $sanitized_checklist[ sanitize_key( $key ) ] = sanitize_textarea_field( wp_unslash( $text ) );
            }
            update_post_meta( $post_id, '_travelz_visa_checklist', $sanitized_checklist );
        }

        // 5. FAQs Repeater
        if ( isset( $_POST['travelz_faqs'] ) && is_array( $_POST['travelz_faqs'] ) ) {
            $sanitized_faqs = array();
            foreach ( $_POST['travelz_faqs'] as $faq ) {
                $q = isset( $faq['q'] ) ? sanitize_text_field( wp_unslash( $faq['q'] ) ) : '';
                $a = isset( $faq['a'] ) ? sanitize_textarea_field( wp_unslash( $faq['a'] ) ) : '';
                if ( ! empty( $q ) && ! empty( $a ) ) {
                    $sanitized_faqs[] = array( 'q' => $q, 'a' => $a );
                }
            }
            update_post_meta( $post_id, '_travelz_visa_faqs', $sanitized_faqs );
        } else {
            delete_post_meta( $post_id, '_travelz_visa_faqs' );
        }

        // 6. Sidebar Fields
        if ( isset( $_POST['travelz_visa_flag_url'] ) ) {
            update_post_meta( $post_id, '_travelz_visa_flag_url', esc_url_raw( wp_unslash( $_POST['travelz_visa_flag_url'] ) ) );
        }
        if ( isset( $_POST['travelz_visa_custom_query_url'] ) ) {
            update_post_meta( $post_id, '_travelz_visa_custom_query_url', esc_url_raw( wp_unslash( $_POST['travelz_visa_custom_query_url'] ) ) );
        }
        if ( isset( $_POST['travelz_visa_custom_whatsapp'] ) ) {
            update_post_meta( $post_id, '_travelz_visa_custom_whatsapp', sanitize_text_field( wp_unslash( $_POST['travelz_visa_custom_whatsapp'] ) ) );
        }
    }
}

TravelZ_Visa_Meta_Boxes::init();
