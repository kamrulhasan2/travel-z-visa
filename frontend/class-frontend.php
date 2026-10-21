<?php
/**
 * Frontend Assets & Template Loader
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TravelZ_Visa_Frontend {

    /**
     * Initialize.
     */
    public static function init() {
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
        add_filter( 'template_include', array( __CLASS__, 'template_loader' ) );
        add_action( 'template_redirect', array( __CLASS__, 'maybe_redirect_old_pages' ) );

        // Theme compatibility: disable sidebar and force full-width container on Astra
        add_filter( 'astra_page_layout', array( __CLASS__, 'filter_astra_sidebar' ) );
        add_filter( 'astra_get_content_layout', array( __CLASS__, 'filter_astra_content_layout' ) );

        // Inject body class for CSS scoping & reliable centering
        add_filter( 'body_class', array( __CLASS__, 'filter_body_classes' ) );

        // AJAX PDF Proxy to bypass CORS issues on cross-domain/local PDF rendering
        add_action( 'wp_ajax_travelz_pdf_proxy', array( __CLASS__, 'pdf_proxy' ) );
        add_action( 'wp_ajax_nopriv_travelz_pdf_proxy', array( __CLASS__, 'pdf_proxy' ) );
    }

    /**
     * Disable Astra sidebar on Visa archive and single pages.
     */
    public static function filter_astra_sidebar( $layout ) {
        if ( is_singular( TravelZ_Visa_Post_Type::POST_TYPE ) || is_post_type_archive( TravelZ_Visa_Post_Type::POST_TYPE ) || is_tax( TravelZ_Visa_Post_Type::TAXONOMY ) ) {
            return 'no-sidebar';
        }
        return $layout;
    }

    /**
     * Force Astra plain/full-width container on Visa archive and single pages.
     */
    public static function filter_astra_content_layout( $layout ) {
        if ( is_singular( TravelZ_Visa_Post_Type::POST_TYPE ) || is_post_type_archive( TravelZ_Visa_Post_Type::POST_TYPE ) || is_tax( TravelZ_Visa_Post_Type::TAXONOMY ) ) {
            return 'plain-container';
        }
        return $layout;
    }

    /**
     * Add custom body class for reliable layout styling.
     */
    public static function filter_body_classes( $classes ) {
        if ( is_singular( TravelZ_Visa_Post_Type::POST_TYPE ) || is_post_type_archive( TravelZ_Visa_Post_Type::POST_TYPE ) || is_tax( TravelZ_Visa_Post_Type::TAXONOMY ) ) {
            $classes[] = 'travelz-visa-active-page';
        }
        return $classes;
    }

    /**
     * Enqueue Frontend Assets.
     */
    public static function enqueue_assets() {
        $settings = TravelZ_Visa_Settings::get_settings();

        wp_enqueue_style(
            'travelz-visa-style',
            TRAVELZ_VISA_URL . 'frontend/css/frontend-style.css',
            array(),
            TRAVELZ_VISA_VERSION
        );

        wp_enqueue_style(
            'travelz-visa-flipbook',
            TRAVELZ_VISA_URL . 'frontend/css/flipbook.css',
            array(),
            TRAVELZ_VISA_VERSION
        );

        // Inject Dynamic Colors
        $custom_css = "
            :root {
                --travelz-primary: " . esc_attr( $settings['primary_color'] ) . ";
                --travelz-accent: " . esc_attr( $settings['accent_color'] ) . ";
                --travelz-primary-rgb: " . self::hex2rgb( $settings['primary_color'] ) . ";
            }
        ";
        wp_add_inline_style( 'travelz-visa-style', $custom_css );

        wp_enqueue_script(
            'travelz-visa-script',
            TRAVELZ_VISA_URL . 'frontend/js/frontend-script.js',
            array( 'jquery' ),
            TRAVELZ_VISA_VERSION,
            true
        );

        wp_enqueue_script(
            'travelz-visa-flipbook',
            TRAVELZ_VISA_URL . 'frontend/js/flipbook.js',
            array( 'jquery' ),
            TRAVELZ_VISA_VERSION,
            true
        );

        wp_localize_script( 'travelz-visa-script', 'travelzVisaData', array(
            'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
            'soundEnabled'  => ! empty( $settings['flipbook_sound'] ),
            'soundUrl'      => TRAVELZ_VISA_URL . 'frontend/assets/page-flip.mp3',
        ) );
    }

    /**
     * Helper to convert HEX color to RGB string.
     */
    private static function hex2rgb( $hex ) {
        $hex = str_replace( '#', '', $hex );
        if ( strlen( $hex ) == 3 ) {
            $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
            $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
            $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
        } else {
            $r = hexdec( substr( $hex, 0, 2 ) );
            $g = hexdec( substr( $hex, 2, 2 ) );
            $b = hexdec( substr( $hex, 4, 2 ) );
        }
        return "$r, $g, $b";
    }

    /**
     * Intercept template hierarchy.
     */
    public static function template_loader( $template ) {
        if ( is_singular( TravelZ_Visa_Post_Type::POST_TYPE ) ) {
            $theme_file = locate_template( array( 'single-travelz_visa.php' ) );
            if ( $theme_file ) {
                return $theme_file;
            }
            return TRAVELZ_VISA_PATH . 'templates/single-visa.php';
        }

        if ( is_post_type_archive( TravelZ_Visa_Post_Type::POST_TYPE ) || is_tax( TravelZ_Visa_Post_Type::TAXONOMY ) ) {
            $theme_file = locate_template( array( 'archive-travelz_visa.php' ) );
            if ( $theme_file ) {
                return $theme_file;
            }
            return TRAVELZ_VISA_PATH . 'templates/archive-visa.php';
        }

        return $template;
    }

    /**
     * Optional 301 Redirect for old top-level country pages if they exist as pages
     * and a travelz_visa post matches the same slug.
     */
    public static function maybe_redirect_old_pages() {
        if ( is_page() && ! is_admin() ) {
            $slug = get_post_field( 'post_name', get_the_ID() );
            // Clean slug if it was e.g. thailand-2 or cambodia-visa
            $normalized_slug = preg_replace( '/(-2|-visa)$/', '', $slug );

            // Check if matching travelz_visa exists
            $visa_post = get_page_by_path( $normalized_slug, OBJECT, TravelZ_Visa_Post_Type::POST_TYPE );
            if ( ! $visa_post ) {
                $visa_post = get_page_by_path( $slug, OBJECT, TravelZ_Visa_Post_Type::POST_TYPE );
            }

            // Only redirect if it's not the /visa-assistance/ page itself
            if ( $visa_post && $slug !== TravelZ_Visa_Post_Type::get_slug() ) {
                // If page content contains old Elementor iframe or user wants redirection
                $redirect = get_option( 'travelz_visa_redirect_old', '0' );
                if ( '1' === $redirect ) {
                    wp_safe_redirect( get_permalink( $visa_post->ID ), 301 );
                    exit;
                }
            }
        }
    }

    /**
     * Safe PDF Proxy to avoid CORS errors when PDF is on a different origin.
     */
    public static function pdf_proxy() {
        $pdf_url = isset( $_GET['pdf'] ) ? esc_url_raw( wp_unslash( $_GET['pdf'] ) ) : '';
        if ( empty( $pdf_url ) ) {
            wp_die( 'No PDF specified', 400 );
        }

        $clean_url = strtok( $pdf_url, '?' );
        if ( ! preg_match( '/\.pdf$/i', $clean_url ) ) {
            wp_die( 'Invalid file type', 400 );
        }

        $response = wp_remote_get( $pdf_url, array(
            'timeout'   => 25,
            'sslverify' => false,
        ) );

        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            wp_die( 'Unable to load PDF', 502 );
        }

        $body = wp_remote_retrieve_body( $response );

        header( 'Content-Type: application/pdf' );
        header( 'Access-Control-Allow-Origin: *' );
        header( 'Access-Control-Allow-Methods: GET' );
        header( 'Content-Length: ' . strlen( $body ) );
        header( 'Cache-Control: public, max-age=86400' );

        echo $body;
        exit;
    }
}

TravelZ_Visa_Frontend::init();
