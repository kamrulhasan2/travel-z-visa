<?php
/**
 * Admin Settings Page
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TravelZ_Visa_Settings {

    /**
     * Option key.
     */
    const OPTION_KEY = 'travelz_visa_settings';

    /**
     * Initialize.
     */
    public static function init() {
        if ( is_admin() ) {
            add_action( 'admin_menu', array( __CLASS__, 'add_settings_menu' ) );
            add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
            add_action( 'admin_post_travelz_seed_data', array( __CLASS__, 'handle_seed_sample_data' ) );
        }
    }

    /**
     * Add settings submenu under Travel-Z Visa.
     */
    public static function add_settings_menu() {
        add_submenu_page(
            'edit.php?post_type=' . TravelZ_Visa_Post_Type::POST_TYPE,
            __( 'Visa Settings', 'travel-z-visa' ),
            __( 'Settings', 'travel-z-visa' ),
            'manage_options',
            'travelz-visa-settings',
            array( __CLASS__, 'render_settings_page' )
        );
    }

    /**
     * Register settings.
     */
    public static function register_settings() {
        register_setting( 'travelz_visa_settings_group', self::OPTION_KEY, array( __CLASS__, 'sanitize_settings' ) );
    }

    /**
     * Default settings.
     *
     * @return array
     */
    public static function get_defaults() {
        return array(
            'phone'              => '+88 01711-258451',
            'whatsapp'           => '8801711258451',
            'email'              => 'info@tech.travelzbd.com',
            'office_address'     => 'Citylink Apartment (2nd Floor), House: 1/A, Road:13 (New) Old - 30, Beside Metro Shopping Mall, Dhanmondi, Dhaka, Bangladesh.',
            'query_url'          => home_url( '/queries/' ),
            'slug_prefix'        => 'visa-assistance',
            'currency_symbol'    => 'BDT',
            'primary_color'        => '#0ebac5',
            'accent_color'         => '#f26522',
            'hero_bg_image'        => '',
            'hero_overlay'         => 'light',
            'hero_overlay_opacity' => '75',
            'hero_overlay_color'   => '#ffffff',
            'hero_badge_text'      => 'TRAVEL-Z VISA SERVICES',
            'hero_badge_color'     => '#0ebac5',
            'hero_badge_bg'        => '#e6f9fa',
            'hero_title'           => 'Comprehensive Visa Assistance for a Seamless Travel Experience',
            'hero_title_color'     => '#0f172a',
            'hero_description'     => 'Navigating the complexities of visa applications can be challenging. Our dedicated Visa Assistance service offers expert guidance and support to ensure a smooth and stress-free process. Whether you are traveling for business, leisure, or educational purposes, we provide tailored assistance for all types of visas.',
            'hero_desc_color'      => '#475569',
            'hero_search_placeholder' => 'Search your destination country (e.g. China, Australia, Japan)...',
            'enable_schema'        => '1',
            'flipbook_sound'     => '1',
            'default_view_mode'  => 'flipbook',
        );
    }

    /**
     * Get settings with defaults merged.
     *
     * @return array
     */
    public static function get_settings() {
        $saved = get_option( self::OPTION_KEY, array() );
        return wp_parse_args( $saved, self::get_defaults() );
    }

    /**
     * Sanitize settings callback.
     *
     * @param array $input
     * @return array
     */
    public static function sanitize_settings( $input ) {
        $clean = array();
        $clean['phone']             = isset( $input['phone'] ) ? sanitize_text_field( $input['phone'] ) : '';
        $clean['whatsapp']          = isset( $input['whatsapp'] ) ? sanitize_text_field( preg_replace( '/[^0-9]/', '', $input['whatsapp'] ) ) : '';
        $clean['email']             = isset( $input['email'] ) ? sanitize_email( $input['email'] ) : '';
        $clean['office_address']    = isset( $input['office_address'] ) ? sanitize_textarea_field( $input['office_address'] ) : '';
        $clean['query_url']         = isset( $input['query_url'] ) ? esc_url_raw( $input['query_url'] ) : '';
        $clean['slug_prefix']       = isset( $input['slug_prefix'] ) ? sanitize_title( $input['slug_prefix'] ) : 'visa-assistance';
        $clean['currency_symbol']   = isset( $input['currency_symbol'] ) ? sanitize_text_field( $input['currency_symbol'] ) : 'BDT';
        $clean['primary_color']     = isset( $input['primary_color'] ) ? sanitize_hex_color( $input['primary_color'] ) : '#0ebac5';
        $clean['accent_color']      = isset( $input['accent_color'] ) ? sanitize_hex_color( $input['accent_color'] ) : '#f26522';
        $clean['hero_bg_image']        = isset( $input['hero_bg_image'] ) ? esc_url_raw( $input['hero_bg_image'] ) : '';
        $clean['hero_overlay']         = isset( $input['hero_overlay'] ) ? sanitize_key( $input['hero_overlay'] ) : 'light';
        $clean['hero_overlay_opacity'] = isset( $input['hero_overlay_opacity'] ) ? min( 100, max( 0, intval( $input['hero_overlay_opacity'] ) ) ) : 75;
        $clean['hero_overlay_color']   = isset( $input['hero_overlay_color'] ) ? sanitize_hex_color( $input['hero_overlay_color'] ) : '#ffffff';
        $clean['hero_badge_text']      = isset( $input['hero_badge_text'] ) ? sanitize_text_field( $input['hero_badge_text'] ) : 'TRAVEL-Z VISA SERVICES';
        $clean['hero_badge_color']     = isset( $input['hero_badge_color'] ) ? sanitize_hex_color( $input['hero_badge_color'] ) : '#0ebac5';
        $clean['hero_badge_bg']        = isset( $input['hero_badge_bg'] ) ? sanitize_hex_color( $input['hero_badge_bg'] ) : '#e6f9fa';
        $clean['hero_title']           = isset( $input['hero_title'] ) ? sanitize_text_field( $input['hero_title'] ) : 'Comprehensive Visa Assistance for a Seamless Travel Experience';
        $clean['hero_title_color']     = isset( $input['hero_title_color'] ) ? sanitize_hex_color( $input['hero_title_color'] ) : '#0f172a';
        $clean['hero_description']     = isset( $input['hero_description'] ) ? sanitize_textarea_field( $input['hero_description'] ) : '';
        $clean['hero_desc_color']      = isset( $input['hero_desc_color'] ) ? sanitize_hex_color( $input['hero_desc_color'] ) : '#475569';
        $clean['hero_search_placeholder'] = isset( $input['hero_search_placeholder'] ) ? sanitize_text_field( $input['hero_search_placeholder'] ) : 'Search your destination country (e.g. China, Australia, Japan)...';
        $clean['enable_schema']        = isset( $input['enable_schema'] ) ? '1' : '0';
        $clean['flipbook_sound']    = isset( $input['flipbook_sound'] ) ? '1' : '0';
        $clean['default_view_mode'] = isset( $input['default_view_mode'] ) ? sanitize_key( $input['default_view_mode'] ) : 'flipbook';

        return $clean;
    }

    /**
     * Handle seed sample data action.
     */
    public static function handle_seed_sample_data() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized user', 'travel-z-visa' ) );
        }
        check_admin_referer( 'travelz_seed_action', 'travelz_seed_nonce' );

        TravelZ_Visa_Sample_Data::seed_all( true );

        wp_safe_redirect( add_query_arg( array(
            'page'   => 'travelz-visa-settings',
            'seeded' => '1',
        ), admin_url( 'edit.php?post_type=' . TravelZ_Visa_Post_Type::POST_TYPE ) ) );
        exit;
    }

    /**
     * Render Settings Page.
     */
    public static function render_settings_page() {
        $settings = self::get_settings();
        ?>
        <div class="wrap travelz-settings-wrap">
            <h1><?php esc_html_e( 'Travel-Z Visa Settings', 'travel-z-visa' ); ?></h1>

            <?php if ( isset( $_GET['settings-updated'] ) ) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><strong><?php esc_html_e( 'Settings saved successfully.', 'travel-z-visa' ); ?></strong></p>
                </div>
            <?php endif; ?>

            <?php if ( isset( $_GET['seeded'] ) ) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><strong><?php esc_html_e( 'Default sample countries and visa data have been successfully imported/updated!', 'travel-z-visa' ); ?></strong></p>
                </div>
            <?php endif; ?>

            <div class="travelz-settings-grid">
                <div class="travelz-settings-main">
                    <form method="post" action="options.php">
                        <?php
                        settings_fields( 'travelz_visa_settings_group' );
                        ?>

                        <!-- Card 1: Contact Information -->
                        <div class="travelz-admin-card">
                            <h2><span class="dashicons dashicons-phone" style="vertical-align: middle;"></span> <?php esc_html_e( 'Contact & Inquiry Settings', 'travel-z-visa' ); ?></h2>
                            <p class="description"><?php esc_html_e( 'These details are displayed on all country visa pages and query action buttons.', 'travel-z-visa' ); ?></p>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><label for="travelz_phone"><?php esc_html_e( 'Helpline Phone Number', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <input type="text" id="travelz_phone" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[phone]" value="<?php echo esc_attr( $settings['phone'] ); ?>" class="regular-text" />
                                        <p class="description"><?php esc_html_e( 'e.g. +88 01711-258451', 'travel-z-visa' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="travelz_whatsapp"><?php esc_html_e( 'WhatsApp Number', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <input type="text" id="travelz_whatsapp" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[whatsapp]" value="<?php echo esc_attr( $settings['whatsapp'] ); ?>" class="regular-text" />
                                        <p class="description"><?php esc_html_e( 'Include country code without + or spaces (e.g. 8801711258451 for Bangladesh).', 'travel-z-visa' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="travelz_email"><?php esc_html_e( 'Inquiry Email Address', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <input type="email" id="travelz_email" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[email]" value="<?php echo esc_attr( $settings['email'] ); ?>" class="regular-text" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="travelz_query_url"><?php esc_html_e( 'Queries Page URL', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <input type="url" id="travelz_query_url" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[query_url]" value="<?php echo esc_attr( $settings['query_url'] ); ?>" class="large-text" />
                                        <p class="description"><?php esc_html_e( 'Default is /queries/. The "Queries" button on every visa page redirects here.', 'travel-z-visa' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="travelz_address"><?php esc_html_e( 'Office Address', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <textarea id="travelz_address" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[office_address]" rows="3" class="large-text"><?php echo esc_textarea( $settings['office_address'] ); ?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Card 2: Permalinks & Display -->
                        <div class="travelz-admin-card" style="margin-top: 20px;">
                            <h2><span class="dashicons dashicons-admin-links" style="vertical-align: middle;"></span> <?php esc_html_e( 'URL & Display Settings', 'travel-z-visa' ); ?></h2>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><label for="travelz_slug"><?php esc_html_e( 'Visa URL Slug Prefix', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <code><?php echo esc_url( home_url( '/' ) ); ?></code>
                                        <input type="text" id="travelz_slug" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[slug_prefix]" value="<?php echo esc_attr( $settings['slug_prefix'] ); ?>" class="regular-text" style="width: 160px;" />
                                        <code>/{country-name}/</code>
                                        <p class="description"><?php esc_html_e( 'Default: visa-assistance. (Save permalinks in Settings > Permalinks if you change this).', 'travel-z-visa' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="travelz_currency"><?php esc_html_e( 'Currency Suffix', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <input type="text" id="travelz_currency" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[currency_symbol]" value="<?php echo esc_attr( $settings['currency_symbol'] ); ?>" class="small-text" />
                                        <p class="description"><?php esc_html_e( 'Default: BDT', 'travel-z-visa' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'Brand Colors', 'travel-z-visa' ); ?></th>
                                    <td>
                                        <div style="display: flex; gap: 20px; align-items: center;">
                                            <div>
                                                <label for="travelz_primary_color"><strong><?php esc_html_e( 'Primary (Teal)', 'travel-z-visa' ); ?>:</strong></label><br>
                                                <input type="color" id="travelz_primary_color" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[primary_color]" value="<?php echo esc_attr( $settings['primary_color'] ); ?>" />
                                            </div>
                                            <div>
                                                <label for="travelz_accent_color"><strong><?php esc_html_e( 'Accent (Orange)', 'travel-z-visa' ); ?>:</strong></label><br>
                                                <input type="color" id="travelz_accent_color" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[accent_color]" value="<?php echo esc_attr( $settings['accent_color'] ); ?>" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="travelz_hero_bg_image"><?php esc_html_e( 'Hero Background Image', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                                            <div id="travelz-hero-bg-preview" style="width: 140px; height: 80px; border: 1px solid #cbd5e1; border-radius: 6px; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                <?php if ( ! empty( $settings['hero_bg_image'] ) ) : ?>
                                                    <img src="<?php echo esc_url( $settings['hero_bg_image'] ); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="" />
                                                <?php else : ?>
                                                    <span style="font-size: 11px; color: #94a3b8; text-align: center; padding: 4px;"><?php esc_html_e( 'Default (Clean White)', 'travel-z-visa' ); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <input type="text" id="travelz_hero_bg_image" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[hero_bg_image]" value="<?php echo esc_url( $settings['hero_bg_image'] ); ?>" class="regular-text" placeholder="https://..." />
                                                <div style="margin-top: 8px; display: flex; gap: 8px;">
                                                    <button type="button" class="button button-secondary" id="travelz_upload_hero_bg_btn">
                                                        <span class="dashicons dashicons-upload" style="vertical-align: middle;"></span> <?php esc_html_e( 'Select / Upload Image', 'travel-z-visa' ); ?>
                                                    </button>
                                                    <button type="button" class="button button-link-delete" id="travelz_remove_hero_bg_btn" style="<?php echo empty( $settings['hero_bg_image'] ) ? 'display: none;' : ''; ?>">
                                                        <?php esc_html_e( 'Remove Image', 'travel-z-visa' ); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="description"><?php esc_html_e( 'Leave empty to keep the default clean white hero background. If an image is selected, it will be displayed behind the hero banner on the Visa Assistance page.', 'travel-z-visa' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="travelz_hero_opacity"><?php esc_html_e( 'Hero Overlay Transparency', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 14px;">
                                            <input type="range" id="travelz_hero_opacity_slider" min="0" max="100" step="5" value="<?php echo esc_attr( $settings['hero_overlay_opacity'] ); ?>" style="width: 200px;" oninput="document.getElementById('travelz_hero_opacity').value = this.value; document.getElementById('travelz_opacity_val').innerText = this.value + '%';" />
                                            <input type="number" id="travelz_hero_opacity" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[hero_overlay_opacity]" min="0" max="100" step="5" value="<?php echo esc_attr( $settings['hero_overlay_opacity'] ); ?>" style="width: 65px;" oninput="document.getElementById('travelz_hero_opacity_slider').value = this.value; document.getElementById('travelz_opacity_val').innerText = this.value + '%';" />
                                            <strong id="travelz_opacity_val" style="color: #0ebac5; font-size: 15px;"><?php echo esc_html( $settings['hero_overlay_opacity'] ); ?>%</strong>
                                        </div>
                                        <p class="description"><?php esc_html_e( 'Adjust the overlay intensity over the background image (e.g. 60% - 85% is ideal for keeping text clean and readable while showing the background image).', 'travel-z-visa' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="travelz_hero_overlay_color"><?php esc_html_e( 'Hero Overlay Tint Color', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <input type="color" id="travelz_hero_overlay_color" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[hero_overlay_color]" value="<?php echo esc_attr( $settings['hero_overlay_color'] ); ?>" />
                                        <p class="description"><?php esc_html_e( 'Default is white (#ffffff) for clean white aesthetic. You can also pick a dark or tinted color.', 'travel-z-visa' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'Interactive Flipbook Sound', 'travel-z-visa' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[flipbook_sound]" value="1" <?php checked( $settings['flipbook_sound'], '1' ); ?> />
                                            <?php esc_html_e( 'Play realistic page-turning sound when flipping pages', 'travel-z-visa' ); ?>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'Google Schema (SEO)', 'travel-z-visa' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[enable_schema]" value="1" <?php checked( $settings['enable_schema'], '1' ); ?> />
                                            <?php esc_html_e( 'Automatically generate JSON-LD Service, Product, and FAQPage structured schema', 'travel-z-visa' ); ?>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Card 3: Hero Banner Content & Styling -->
                        <div class="travelz-admin-card" style="margin-top: 20px;">
                            <h2><span class="dashicons dashicons-edit" style="vertical-align: middle;"></span> <?php esc_html_e( 'Hero Banner Content & Text Colors', 'travel-z-visa' ); ?></h2>
                            <p class="description"><?php esc_html_e( 'Customize the banner headings, badge, subtitle description, and individual text colors on the Visa Assistance Hub page.', 'travel-z-visa' ); ?></p>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><label for="travelz_hero_badge_text"><?php esc_html_e( 'Hero Badge / Tagline', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <input type="text" id="travelz_hero_badge_text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[hero_badge_text]" value="<?php echo esc_attr( $settings['hero_badge_text'] ); ?>" class="regular-text" style="width: 350px;" />
                                        <div style="display: flex; gap: 24px; align-items: center; margin-top: 10px;">
                                            <div>
                                                <label for="travelz_hero_badge_color" style="font-size: 12px; color: #475569; font-weight: 600;"><?php esc_html_e( 'Badge Text Color', 'travel-z-visa' ); ?>:</label><br>
                                                <input type="color" id="travelz_hero_badge_color" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[hero_badge_color]" value="<?php echo esc_attr( $settings['hero_badge_color'] ); ?>" />
                                            </div>
                                            <div>
                                                <label for="travelz_hero_badge_bg" style="font-size: 12px; color: #475569; font-weight: 600;"><?php esc_html_e( 'Badge Background', 'travel-z-visa' ); ?>:</label><br>
                                                <input type="color" id="travelz_hero_badge_bg" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[hero_badge_bg]" value="<?php echo esc_attr( $settings['hero_badge_bg'] ); ?>" />
                                            </div>
                                        </div>
                                        <p class="description"><?php esc_html_e( 'The pill-shaped tagline badge above the title (e.g. TRAVEL-Z VISA SERVICES).', 'travel-z-visa' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="travelz_hero_title"><?php esc_html_e( 'Hero Main Title', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <input type="text" id="travelz_hero_title" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[hero_title]" value="<?php echo esc_attr( $settings['hero_title'] ); ?>" class="large-text" />
                                        <div style="margin-top: 10px;">
                                            <label for="travelz_hero_title_color" style="font-size: 12px; color: #475569; font-weight: 600;"><?php esc_html_e( 'Title Color', 'travel-z-visa' ); ?>:</label><br>
                                            <input type="color" id="travelz_hero_title_color" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[hero_title_color]" value="<?php echo esc_attr( $settings['hero_title_color'] ); ?>" />
                                        </div>
                                        <p class="description"><?php esc_html_e( 'Main headline of the visa service on the landing page.', 'travel-z-visa' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="travelz_hero_description"><?php esc_html_e( 'Hero Description', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <textarea id="travelz_hero_description" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[hero_description]" rows="4" class="large-text"><?php echo esc_textarea( $settings['hero_description'] ); ?></textarea>
                                        <div style="margin-top: 10px;">
                                            <label for="travelz_hero_desc_color" style="font-size: 12px; color: #475569; font-weight: 600;"><?php esc_html_e( 'Description Text Color', 'travel-z-visa' ); ?>:</label><br>
                                            <input type="color" id="travelz_hero_desc_color" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[hero_desc_color]" value="<?php echo esc_attr( $settings['hero_desc_color'] ); ?>" />
                                        </div>
                                        <p class="description"><?php esc_html_e( 'Introductory paragraph below the main title.', 'travel-z-visa' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="travelz_hero_search_placeholder"><?php esc_html_e( 'Search Bar Placeholder Hint', 'travel-z-visa' ); ?></label></th>
                                    <td>
                                        <input type="text" id="travelz_hero_search_placeholder" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[hero_search_placeholder]" value="<?php echo esc_attr( $settings['hero_search_placeholder'] ); ?>" class="large-text" />
                                        <p class="description"><?php esc_html_e( 'Placeholder text inside the country search box (Default: Search your destination country (e.g. China, Australia, Japan)...).', 'travel-z-visa' ); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <?php submit_button( __( 'Save All Settings', 'travel-z-visa' ), 'primary', 'submit', true, array( 'style' => 'background: #0ebac5; border-color: #0c9ea7; padding: 6px 20px; font-size: 15px;' ) ); ?>
                    </form>
                </div>

                <!-- Right Sidebar / Info Box -->
                <div class="travelz-settings-sidebar">
                    <div class="travelz-admin-card" style="border-left: 4px solid #f26522;">
                        <h3><span class="dashicons dashicons-database-import" style="vertical-align: middle; color: #f26522;"></span> <?php esc_html_e( 'Sample Data Importer', 'travel-z-visa' ); ?></h3>
                        <p><?php esc_html_e( 'Import or refresh the default country list (China, Australia, Canada, Japan, Malaysia, Schengen, Dubai, USA, etc.) with pre-filled overview information and fees.', 'travel-z-visa' ); ?></p>
                        
                        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                            <?php wp_nonce_field( 'travelz_seed_action', 'travelz_seed_nonce' ); ?>
                            <input type="hidden" name="action" value="travelz_seed_data" />
                            <button type="submit" class="button button-secondary" onclick="return confirm('Do you want to import/refresh the default visa countries?');" style="width: 100%; text-align: center;">
                                <span class="dashicons dashicons-download" style="vertical-align: middle;"></span> <?php esc_html_e( 'Import / Reset Default Visas', 'travel-z-visa' ); ?>
                            </button>
                        </form>
                    </div>

                    <div class="travelz-admin-card" style="margin-top: 20px; border-left: 4px solid #0ebac5;">
                        <h3><span class="dashicons dashicons-shortcode" style="vertical-align: middle; color: #0ebac5;"></span> <?php esc_html_e( 'Available Shortcodes', 'travel-z-visa' ); ?></h3>
                        <p><?php esc_html_e( 'Use these shortcodes anywhere in Elementor or WordPress content:', 'travel-z-visa' ); ?></p>
                        <ul>
                            <li><code>[travelz_visa_hub]</code><br><small><?php esc_html_e( 'Full Visa Assistance search & country catalog.', 'travel-z-visa' ); ?></small></li>
                            <li style="margin-top: 8px;"><code>[travelz_visa_search]</code><br><small><?php esc_html_e( 'Compact country dropdown & instant search bar.', 'travel-z-visa' ); ?></small></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

TravelZ_Visa_Settings::init();
