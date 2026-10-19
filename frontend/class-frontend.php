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
}

TravelZ_Visa_Frontend::init();
