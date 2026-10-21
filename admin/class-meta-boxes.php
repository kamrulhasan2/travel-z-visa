<?php
/**
 * Modern, Non-Coder Friendly Master Workspace for Visa Post Type
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
        add_action( 'add_meta_boxes', array( __CLASS__, 'register_master_panel' ) );
        add_action( 'save_post_' . TravelZ_Visa_Post_Type::POST_TYPE, array( __CLASS__, 'save_meta' ), 10, 2 );
        add_action( 'admin_init', array( __CLASS__, 'cleanup_unwanted_meta_boxes' ) );
    }

    /**
     * Remove clutter from the edit screen so non-coders see only what matters.
     */
    public static function cleanup_unwanted_meta_boxes() {
        remove_post_type_support( TravelZ_Visa_Post_Type::POST_TYPE, 'custom-fields' );
    }

    /**
     * Register single unified master panel.
     */
    public static function register_master_panel() {
        add_meta_box(
            'travelz_visa_master_panel',
            __( 'Travel-Z Visa Information & Configuration (ভিসা ম্যানেজমেন্ট প্যানেল)', 'travel-z-visa' ),
            array( __CLASS__, 'render_master_panel' ),
            TravelZ_Visa_Post_Type::POST_TYPE,
            'normal',
            'high'
        );
    }

    /**
     * Render the Master Workspace.
     */
    public static function render_master_panel( $post ) {
        wp_nonce_field( 'travelz_visa_meta_nonce', 'travelz_visa_nonce' );

        // Overview
        $visa_type       = get_post_meta( $post->ID, '_travelz_visa_type', true );
        $duration        = get_post_meta( $post->ID, '_travelz_visa_duration', true );
        $processing_time = get_post_meta( $post->ID, '_travelz_visa_processing_time', true );
        $fees            = get_post_meta( $post->ID, '_travelz_visa_fees', true );
        $fees            = is_array( $fees ) ? $fees : array();

        // PDF & Flipbook
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

        // Checklist
        $checklist = get_post_meta( $post->ID, '_travelz_visa_checklist', true );
        $checklist = is_array( $checklist ) ? $checklist : array();

        // FAQs
        $faqs = get_post_meta( $post->ID, '_travelz_visa_faqs', true );
        $faqs = is_array( $faqs ) ? $faqs : array();

        // Flags & Overrides
        $flag_url    = get_post_meta( $post->ID, '_travelz_visa_flag_url', true );
        $query_url   = get_post_meta( $post->ID, '_travelz_visa_custom_query_url', true );
        $whatsapp_no = get_post_meta( $post->ID, '_travelz_visa_custom_whatsapp', true );
        ?>

        <div class="travelz-admin-workspace">
            
            <!-- Workspace Navigation Tabs -->
            <div class="travelz-workspace-tabs" role="tablist">
                <button type="button" class="travelz-wtab-btn active" data-tab="tab-overview">
                    <span class="dashicons dashicons-info-outline"></span>
                    <span class="wtab-text">
                        <strong><?php esc_html_e( 'Overview', 'travel-z-visa' ); ?></strong>
                        <small><?php esc_html_e( 'ভিসা ওভারভিউ', 'travel-z-visa' ); ?></small>
                    </span>
                </button>
                <button type="button" class="travelz-wtab-btn" data-tab="tab-fees">
                    <span class="dashicons dashicons-money-alt"></span>
                    <span class="wtab-text">
                        <strong><?php esc_html_e( 'Fees & Pricing', 'travel-z-visa' ); ?></strong>
                        <small><?php esc_html_e( 'ভিসা ফি তালিকা', 'travel-z-visa' ); ?></small>
                    </span>
                </button>
                <button type="button" class="travelz-wtab-btn" data-tab="tab-pdf">
                    <span class="dashicons dashicons-pdf"></span>
                    <span class="wtab-text">
                        <strong><?php esc_html_e( 'PDF & Flipbook', 'travel-z-visa' ); ?></strong>
                        <small><?php esc_html_e( 'রিকোয়ারমেন্ট ফাইল', 'travel-z-visa' ); ?></small>
                    </span>
                </button>
                <button type="button" class="travelz-wtab-btn" data-tab="tab-checklist">
                    <span class="dashicons dashicons-clipboard"></span>
                    <span class="wtab-text">
                        <strong><?php esc_html_e( 'Checklist (SEO)', 'travel-z-visa' ); ?></strong>
                        <small><?php esc_html_e( 'ডকুমেন্ট চেকলিস্ট', 'travel-z-visa' ); ?></small>
                    </span>
                </button>
                <button type="button" class="travelz-wtab-btn" data-tab="tab-faqs">
                    <span class="dashicons dashicons-format-chat"></span>
                    <span class="wtab-text">
                        <strong><?php esc_html_e( 'FAQs', 'travel-z-visa' ); ?></strong>
                        <small><?php esc_html_e( 'সাধারণ প্রশ্নোত্তর', 'travel-z-visa' ); ?></small>
                    </span>
                </button>
                <button type="button" class="travelz-wtab-btn" data-tab="tab-settings">
                    <span class="dashicons dashicons-admin-settings"></span>
                    <span class="wtab-text">
                        <strong><?php esc_html_e( 'Flag & Contact', 'travel-z-visa' ); ?></strong>
                        <small><?php esc_html_e( 'পতাকা ও কন্টাক্ট', 'travel-z-visa' ); ?></small>
                    </span>
                </button>
            </div>

            <!-- Workspace Content Panels -->
            <div class="travelz-workspace-content">
                
                <!-- ================= TAB 1: OVERVIEW ================= -->
                <div class="travelz-tab-panel active" id="tab-overview">
                    <div class="travelz-panel-header">
                        <h3><span class="dashicons dashicons-info"></span> <?php esc_html_e( 'Visa Overview Information (ভিসার সাধারণ তথ্য)', 'travel-z-visa' ); ?></h3>
                        <p class="description"><?php esc_html_e( 'এই তথ্যগুলো সাইটের ফ্রন্টএন্ডে ওভারভিউ কার্ডের মধ্যে প্রদর্শিত হবে। নিচে ক্লিক করে দ্রুত পূরণ করতে পারেন।', 'travel-z-visa' ); ?></p>
                    </div>

                    <div class="travelz-form-row">
                        <div class="travelz-form-col">
                            <label class="travelz-field-label" for="travelz_visa_type">
                                <strong><?php esc_html_e( 'Type of Visa (ভিসার ধরন)', 'travel-z-visa' ); ?> <span class="req">*</span></strong>
                            </label>
                            <input type="text" id="travelz_visa_type" name="travelz_visa_type" value="<?php echo esc_attr( $visa_type ); ?>" class="travelz-input-large" placeholder="e.g. Tourist Sticker visa, E-Visa, Business Visa" />
                            <div class="travelz-quick-tags" data-target="#travelz_visa_type">
                                <span><?php esc_html_e( 'Quick Presets:', 'travel-z-visa' ); ?></span>
                                <button type="button" class="travelz-tag-btn">Tourist Sticker visa</button>
                                <button type="button" class="travelz-tag-btn">Tourist E-Visa</button>
                                <button type="button" class="travelz-tag-btn">Business Visa</button>
                                <button type="button" class="travelz-tag-btn">Transit Visa</button>
                            </div>
                        </div>

                        <div class="travelz-form-col">
                            <label class="travelz-field-label" for="travelz_visa_duration">
                                <strong><?php esc_html_e( 'Visa Duration / Validity (ভিসার মেয়াদ)', 'travel-z-visa' ); ?> <span class="req">*</span></strong>
                            </label>
                            <input type="text" id="travelz_visa_duration" name="travelz_visa_duration" value="<?php echo esc_attr( $duration ); ?>" class="travelz-input-large" placeholder="e.g. 3 months single Entry, 1 year multiple" />
                            <div class="travelz-quick-tags" data-target="#travelz_visa_duration">
                                <span><?php esc_html_e( 'Quick Presets:', 'travel-z-visa' ); ?></span>
                                <button type="button" class="travelz-tag-btn">3 months single Entry</button>
                                <button type="button" class="travelz-tag-btn">6 months multiple</button>
                                <button type="button" class="travelz-tag-btn">1 year multiple</button>
                                <button type="button" class="travelz-tag-btn">30 Days Single Entry</button>
                            </div>
                        </div>
                    </div>

                    <div class="travelz-form-row">
                        <div class="travelz-form-col travelz-col-full">
                            <label class="travelz-field-label" for="travelz_visa_processing_time">
                                <strong><?php esc_html_e( 'File Processing Time (Travel Z) (ফাইল প্রসেসিং সময়)', 'travel-z-visa' ); ?></strong>
                            </label>
                            <input type="text" id="travelz_visa_processing_time" name="travelz_visa_processing_time" value="<?php echo esc_attr( $processing_time ); ?>" class="travelz-input-large" placeholder="e.g. 10-15 working days (Approx.)" />
                            <div class="travelz-quick-tags" data-target="#travelz_visa_processing_time">
                                <span><?php esc_html_e( 'Quick Presets:', 'travel-z-visa' ); ?></span>
                                <button type="button" class="travelz-tag-btn">5-7 working days (Approx.)</button>
                                <button type="button" class="travelz-tag-btn">10-15 working days (Approx.)</button>
                                <button type="button" class="travelz-tag-btn">15-20 working days</button>
                                <button type="button" class="travelz-tag-btn">3-5 working days</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 2: FEES & PRICING ================= -->
                <div class="travelz-tab-panel" id="tab-fees">
                    <div class="travelz-panel-header">
                        <h3><span class="dashicons dashicons-money-alt"></span> <?php esc_html_e( 'Multi-Tier Visa Fees (ভিসা ফি তালিকা)', 'travel-z-visa' ); ?></h3>
                        <p class="description"><?php esc_html_e( 'ভিসার যত প্রকারের ফি বা প্যাকেজ রয়েছে (যেমন 1st time, 2nd time, Double entry, 1 year), তা এখানে সাজিয়ে নিন।', 'travel-z-visa' ); ?></p>
                    </div>

                    <!-- Quick Add Preset Buttons -->
                    <div class="travelz-fee-presets">
                        <span><strong><?php esc_html_e( 'দ্রুত যোগ করুন (Quick Add):', 'travel-z-visa' ); ?></strong></span>
                        <button type="button" class="button button-small travelz-preset-fee" data-label="1st time" data-amount="9500 BDT">+ 1st time</button>
                        <button type="button" class="button button-small travelz-preset-fee" data-label="2nd time" data-amount="9000 BDT">+ 2nd time</button>
                        <button type="button" class="button button-small travelz-preset-fee" data-label="Double entry" data-amount="10900 BDT">+ Double entry</button>
                        <button type="button" class="button button-small travelz-preset-fee" data-label="1 year multiple" data-amount="14900 BDT">+ 1 year multiple</button>
                        <button type="button" class="button button-small travelz-preset-fee" data-label="2 years multiple" data-amount="17900 BDT">+ 2 years multiple</button>
                    </div>

                    <table class="travelz-styled-table" id="travelz-fees-table">
                        <thead>
                            <tr>
                                <th style="width: 32%;"><?php esc_html_e( 'Tier / Entry Label (ক্যাটাগরি)', 'travel-z-visa' ); ?></th>
                                <th style="width: 32%;"><?php esc_html_e( 'Fee Amount (টাকা/ফি)', 'travel-z-visa' ); ?></th>
                                <th style="width: 26%;"><?php esc_html_e( 'Note (মন্তব্য - ঐচ্ছিক)', 'travel-z-visa' ); ?></th>
                                <th style="width: 10%; text-align: center;"><?php esc_html_e( 'মুছুন', 'travel-z-visa' ); ?></th>
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
                                            <input type="text" name="travelz_fees[<?php echo esc_attr( $index ); ?>][label]" value="<?php echo esc_attr( $label ); ?>" class="travelz-input-regular" placeholder="e.g. 1st time, Double entry" required />
                                        </td>
                                        <td>
                                            <input type="text" name="travelz_fees[<?php echo esc_attr( $index ); ?>][amount]" value="<?php echo esc_attr( $amount ); ?>" class="travelz-input-regular" placeholder="e.g. 9500 BDT" required />
                                        </td>
                                        <td>
                                            <input type="text" name="travelz_fees[<?php echo esc_attr( $index ); ?>][note]" value="<?php echo esc_attr( $note ); ?>" class="travelz-input-regular" placeholder="e.g. Single entry" />
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="travelz-del-btn travelz-remove-fee-btn" title="মুছে ফেলুন">
                                                <span class="dashicons dashicons-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                            else :
                                ?>
                                <tr class="travelz-fee-row">
                                    <td>
                                        <input type="text" name="travelz_fees[0][label]" value="1st time" class="travelz-input-regular" placeholder="e.g. 1st time" />
                                    </td>
                                    <td>
                                        <input type="text" name="travelz_fees[0][amount]" value="9500 BDT" class="travelz-input-regular" placeholder="e.g. 9500 BDT" />
                                    </td>
                                    <td>
                                        <input type="text" name="travelz_fees[0][note]" value="" class="travelz-input-regular" placeholder="Optional note" />
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="travelz-del-btn travelz-remove-fee-btn">
                                            <span class="dashicons dashicons-trash"></span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div style="margin-top: 15px;">
                        <button type="button" class="button button-primary travelz-action-btn" id="travelz-add-fee-btn">
                            <span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e( 'নতুন ফি সারি যোগ করুন (Add New Fee Row)', 'travel-z-visa' ); ?>
                        </button>
                    </div>
                </div>

                <!-- ================= TAB 3: PDF & FLIPBOOK ================= -->
                <div class="travelz-tab-panel" id="tab-pdf">
                    <div class="travelz-panel-header">
                        <h3><span class="dashicons dashicons-pdf"></span> <?php esc_html_e( 'Requirement PDF & Flipbook Viewer (পিডিএফ ফাইল ও ফ্লিপবুক)', 'travel-z-visa' ); ?></h3>
                        <p class="description"><?php esc_html_e( 'এখানে দেশভিত্তিক রিকোয়ারমেন্ট PDF ফাইলটি আপলোড করুন। প্লাগিনের ভেতরে থাকা ফ্লিপবুক ভিউয়ার এটিকে চমৎকার বইয়ের মতো রেন্ডার করবে।', 'travel-z-visa' ); ?></p>
                    </div>

                    <!-- Visual PDF Card -->
                    <div class="travelz-pdf-selector-card <?php echo ! empty( $pdf_url ) ? 'has-file' : ''; ?>" id="travelz-pdf-card">
                        <div class="travelz-pdf-icon-wrap">
                            <span class="dashicons dashicons-media-document"></span>
                        </div>
                        <div class="travelz-pdf-info-wrap">
                            <h4 id="travelz-pdf-filename-display">
                                <?php echo ! empty( $pdf_url ) ? esc_html( basename( parse_url( $pdf_url, PHP_URL_PATH ) ) ) : esc_html__( 'No PDF file selected yet', 'travel-z-visa' ); ?>
                            </h4>
                            <p id="travelz-pdf-status-text">
                                <?php echo ! empty( $pdf_url ) ? esc_html__( 'PDF Document is connected and will display in the Flipbook.', 'travel-z-visa' ) : esc_html__( 'Please click the button to select or upload a PDF from your computer.', 'travel-z-visa' ); ?>
                            </p>
                            <input type="hidden" id="travelz_visa_pdf_url" name="travelz_visa_pdf_url" value="<?php echo esc_url( $pdf_url ); ?>" />
                        </div>
                        <div class="travelz-pdf-actions">
                            <button type="button" class="button button-primary" id="travelz_upload_pdf_btn">
                                <span class="dashicons dashicons-upload"></span> <?php echo ! empty( $pdf_url ) ? esc_html__( 'Change / Replace PDF', 'travel-z-visa' ) : esc_html__( 'Select / Upload PDF', 'travel-z-visa' ); ?>
                            </button>
                            <?php if ( ! empty( $pdf_url ) ) : ?>
                                <a href="<?php echo esc_url( $pdf_url ); ?>" target="_blank" class="button" id="travelz-preview-pdf-btn">
                                    <span class="dashicons dashicons-visibility"></span> <?php esc_html_e( 'View PDF', 'travel-z-visa' ); ?>
                                </a>
                                <button type="button" class="button button-link-delete" id="travelz-remove-pdf-btn">
                                    <?php esc_html_e( 'Remove', 'travel-z-visa' ); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Viewer Settings -->
                    <div class="travelz-form-row" style="margin-top: 25px;">
                        <div class="travelz-form-col">
                            <label class="travelz-field-label" for="travelz_visa_viewer_type">
                                <strong><?php esc_html_e( 'Viewer Display Mode (ভিউয়ার মোড)', 'travel-z-visa' ); ?></strong>
                            </label>
                            <select id="travelz_visa_viewer_type" name="travelz_visa_viewer_type" class="travelz-select-large">
                                <option value="flipbook" <?php selected( $viewer_type, 'flipbook' ); ?>><?php esc_html_e( '📖 Interactive 3D Flipbook (Realistic Page Turn) - Recommended', 'travel-z-visa' ); ?></option>
                                <option value="modern_reader" <?php selected( $viewer_type, 'modern_reader' ); ?>><?php esc_html_e( '📜 Continuous Document Reader', 'travel-z-visa' ); ?></option>
                                <option value="download_only" <?php selected( $viewer_type, 'download_only' ); ?>><?php esc_html_e( '📥 Download Card Only (No Flipbook)', 'travel-z-visa' ); ?></option>
                            </select>
                        </div>

                        <div class="travelz-form-col">
                            <label class="travelz-field-label" for="travelz_visa_download_text">
                                <strong><?php esc_html_e( 'Download Button Label (বাটনের লেখা)', 'travel-z-visa' ); ?></strong>
                            </label>
                            <input type="text" id="travelz_visa_download_text" name="travelz_visa_download_text" value="<?php echo esc_attr( $download_text ); ?>" class="travelz-input-large" />
                            <div style="margin-top: 8px;">
                                <label style="font-weight: 500; cursor: pointer;">
                                    <input type="checkbox" name="travelz_visa_enable_download" value="1" <?php checked( $enable_download, '1' ); ?> />
                                    <?php esc_html_e( 'ভিজিটরদের জন্য "Download PDF" বাটন সক্রিয় রাখুন (Allow Download)', 'travel-z-visa' ); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 4: CHECKLIST (SEO) ================= -->
                <div class="travelz-tab-panel" id="tab-checklist">
                    <div class="travelz-panel-header">
                        <h3><span class="dashicons dashicons-clipboard"></span> <?php esc_html_e( 'Required Documents Checklist (প্রয়োজনীয় কাগজপত্র তালিকা)', 'travel-z-visa' ); ?></h3>
                        <p class="description"><?php esc_html_e( 'প্রতি লাইনে একটি করে ডকুমেন্টের নাম লিখুন। এটি গুগলে অত্যন্ত দ্রুত ইনডেক্স হবে এবং ভিজিটররা পেশা অনুযায়ী কাগজপত্র চেক করতে পারবে।', 'travel-z-visa' ); ?></p>
                    </div>

                    <?php
                    $categories = array(
                        'general'    => array(
                            'title'       => __( '১. সাধারণ ডকুমেন্টস (General Documents - সকলের জন্য প্রযোজ্য)', 'travel-z-visa' ),
                            'placeholder' => "Original Passport valid for at least 6 months\n2 copies of recent color photographs\nBank Statement for last 6 months\nBank Solvency Certificate\nNational ID Card (NID) copy",
                        ),
                        'employed'   => array(
                            'title'       => __( '২. চাকুরিজীবীদের জন্য (For Job Holders / Employed)', 'travel-z-visa' ),
                            'placeholder' => "No Objection Certificate (NOC) from employer\nVisiting Card & Office ID Card\nSalary Certificate or Pay slips (last 3-6 months)\nGovernment Order (GO) for government employees",
                        ),
                        'business'   => array(
                            'title'       => __( '৩. ব্যবসায়ীদের জন্য (For Business Persons)', 'travel-z-visa' ),
                            'placeholder' => "Trade License (renewed copy translated & notarized)\nCompany Memorandum & Certificate of Incorporation\nOfficial Letterhead Pad & Visiting Card\nCompany Bank Statement & Solvency",
                        ),
                        'student'    => array(
                            'title'       => __( '৪. শিক্ষার্থীদের জন্য (For Students)', 'travel-z-visa' ),
                            'placeholder' => "Student ID Card copy\nLeave approval letter / Recommendation from Institution\nParents' Financial Documents & Sponsorship declaration",
                        ),
                        'additional' => array(
                            'title'       => __( '৫. অন্যান্য সাপোর্টিং ডকুমেন্টস (Additional / Supporting Documents)', 'travel-z-visa' ),
                            'placeholder' => "Marriage Certificate copy (if spouse is traveling)\nAir ticket booking confirmation\nHotel accommodation reservation",
                        ),
                    );

                    foreach ( $categories as $key => $cat ) :
                        $val = isset( $checklist[ $key ] ) ? $checklist[ $key ] : '';
                        ?>
                        <div class="travelz-checklist-block">
                            <label class="travelz-checklist-header" for="travelz_doc_<?php echo esc_attr( $key ); ?>">
                                <strong><?php echo esc_html( $cat['title'] ); ?></strong>
                            </label>
                            <textarea id="travelz_doc_<?php echo esc_attr( $key ); ?>" name="travelz_checklist[<?php echo esc_attr( $key ); ?>]" rows="4" class="travelz-textarea" placeholder="<?php echo esc_attr( $cat['placeholder'] ); ?>"><?php echo esc_textarea( $val ); ?></textarea>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- ================= TAB 5: FAQS ================= -->
                <div class="travelz-tab-panel" id="tab-faqs">
                    <div class="travelz-panel-header">
                        <h3><span class="dashicons dashicons-format-chat"></span> <?php esc_html_e( 'Frequently Asked Questions (প্রশ্নোত্তর ও FAQ স্কিমা)', 'travel-z-visa' ); ?></h3>
                        <p class="description"><?php esc_html_e( 'এই প্রশ্নোত্তরগুলো পেজের নিচে সুন্দর অ্যাকর্ডিয়ন আকারে থাকবে এবং স্বয়ংক্রিয়ভাবে Google FAQPage Schema জেনারেট করবে।', 'travel-z-visa' ); ?></p>
                    </div>

                    <div id="travelz-faqs-wrapper">
                        <?php
                        if ( ! empty( $faqs ) ) :
                            foreach ( $faqs as $i => $faq ) :
                                $q = isset( $faq['q'] ) ? $faq['q'] : '';
                                $a = isset( $faq['a'] ) ? $faq['a'] : '';
                                ?>
                                <div class="travelz-faq-card">
                                    <div class="travelz-faq-card-header">
                                        <span class="travelz-faq-num"><span class="dashicons dashicons-editor-help"></span> <?php printf( esc_html__( 'প্রশ্ন #%d (Question)', 'travel-z-visa' ), $i + 1 ); ?></span>
                                        <button type="button" class="travelz-del-btn travelz-remove-faq-btn" title="মুছে ফেলুন">
                                            <span class="dashicons dashicons-trash"></span>
                                        </button>
                                    </div>
                                    <div class="travelz-faq-fields">
                                        <input type="text" name="travelz_faqs[<?php echo esc_attr( $i ); ?>][q]" value="<?php echo esc_attr( $q ); ?>" class="travelz-input-regular" placeholder="<?php esc_attr_e( 'প্রশ্ন লিখুন (e.g. Do I need an interview for China Visa?)', 'travel-z-visa' ); ?>" />
                                        <textarea name="travelz_faqs[<?php echo esc_attr( $i ); ?>][a]" rows="2" class="travelz-textarea" placeholder="<?php esc_attr_e( 'উত্তর লিখুন...', 'travel-z-visa' ); ?>"><?php echo esc_textarea( $a ); ?></textarea>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </div>

                    <div style="margin-top: 15px;">
                        <button type="button" class="button button-primary travelz-action-btn" id="travelz-add-faq-btn">
                            <span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e( 'নতুন প্রশ্ন যোগ করুন (Add New FAQ)', 'travel-z-visa' ); ?>
                        </button>
                    </div>
                </div>

                <!-- ================= TAB 6: FLAG & OVERRIDES ================= -->
                <div class="travelz-tab-panel" id="tab-settings">
                    <div class="travelz-panel-header">
                        <h3><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'Country Flag & Contact Overrides (পতাকা ও বিশেষ কন্টাক্ট)', 'travel-z-visa' ); ?></h3>
                        <p class="description"><?php esc_html_e( 'দেশের পতাকা নির্বাচন করুন এবং প্রয়োজন হলে নির্দিষ্ট দেশের জন্য আলাদা কুয়েরিজ লিংক বা হোয়াটসঅ্যাপ নম্বর দিন।', 'travel-z-visa' ); ?></p>
                    </div>

                    <!-- Flag Preview Card -->
                    <div class="travelz-flag-selector-box">
                        <label class="travelz-field-label"><strong><?php esc_html_e( 'Country Flag / Icon (দেশের পতাকা বা লোগো):', 'travel-z-visa' ); ?></strong></label>
                        <div class="travelz-flag-preview-row">
                            <div class="travelz-flag-preview-wrap" id="travelz-flag-preview">
                                <?php if ( ! empty( $flag_url ) ) : ?>
                                    <img src="<?php echo esc_url( $flag_url ); ?>" alt="Flag" />
                                <?php else : ?>
                                    <span class="dashicons dashicons-flag" style="font-size: 36px; color: #cbd5e1;"></span>
                                <?php endif; ?>
                            </div>
                            <div class="travelz-flag-inputs">
                                <input type="text" id="travelz_visa_flag_url" name="travelz_visa_flag_url" value="<?php echo esc_url( $flag_url ); ?>" class="travelz-input-regular" placeholder="https://..." />
                                <div style="display: flex; gap: 8px; margin-top: 8px;">
                                    <button type="button" class="button button-secondary" id="travelz_upload_flag_btn">
                                        <span class="dashicons dashicons-upload"></span> <?php esc_html_e( 'Select Flag from Media', 'travel-z-visa' ); ?>
                                    </button>
                                    <?php if ( ! empty( $flag_url ) ) : ?>
                                        <button type="button" class="button button-link-delete" id="travelz-remove-flag-btn">
                                            <?php esc_html_e( 'Remove', 'travel-z-visa' ); ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr style="margin: 25px 0; border: 0; border-top: 1px solid #f1f5f9;" />

                    <!-- Custom Contact Overrides -->
                    <div class="travelz-form-row">
                        <div class="travelz-form-col">
                            <label class="travelz-field-label" for="travelz_visa_custom_whatsapp">
                                <strong><?php esc_html_e( 'Custom WhatsApp No. (ঐচ্ছিক হোয়াটসঅ্যাপ নম্বর)', 'travel-z-visa' ); ?></strong>
                            </label>
                            <input type="text" id="travelz_visa_custom_whatsapp" name="travelz_visa_custom_whatsapp" value="<?php echo esc_attr( $whatsapp_no ); ?>" class="travelz-input-regular" placeholder="e.g. 8801711258451" />
                            <p class="description"><?php esc_html_e( 'ফাঁকা রাখলে গ্লোবাল সেটিংসের হোয়াটসঅ্যাপ নম্বর ব্যবহৃত হবে।', 'travel-z-visa' ); ?></p>
                        </div>

                        <div class="travelz-form-col">
                            <label class="travelz-field-label" for="travelz_visa_custom_query_url">
                                <strong><?php esc_html_e( 'Custom Query URL (ঐচ্ছিক কুয়েরিজ লিংক)', 'travel-z-visa' ); ?></strong>
                            </label>
                            <input type="text" id="travelz_visa_custom_query_url" name="travelz_visa_custom_query_url" value="<?php echo esc_url( $query_url ); ?>" class="travelz-input-regular" placeholder="e.g. /queries/" />
                            <p class="description"><?php esc_html_e( 'ফাঁকা রাখলে সাইটের ডিফল্ট /queries/ পেজ কাজ করবে।', 'travel-z-visa' ); ?></p>
                        </div>
                    </div>
                </div>

            </div>
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

        // 6. Sidebar / Flags
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
