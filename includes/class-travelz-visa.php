<?php
/**
 * Core Orchestrator Class
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TravelZ_Visa {

    /**
     * Singleton instance.
     *
     * @var TravelZ_Visa|null
     */
    private static $instance = null;

    /**
     * Get singleton instance.
     *
     * @return TravelZ_Visa
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Load all required files.
     */
    private function load_dependencies() {
        // Includes
        require_once TRAVELZ_VISA_PATH . 'includes/class-post-type.php';
        require_once TRAVELZ_VISA_PATH . 'includes/class-seo.php';
        require_once TRAVELZ_VISA_PATH . 'includes/class-shortcodes.php';
        require_once TRAVELZ_VISA_PATH . 'includes/class-sample-data.php';

        require_once TRAVELZ_VISA_PATH . 'admin/class-settings.php';

        // Admin
        if ( is_admin() ) {
            require_once TRAVELZ_VISA_PATH . 'admin/class-admin.php';
            require_once TRAVELZ_VISA_PATH . 'admin/class-meta-boxes.php';
        }

        // Frontend
        require_once TRAVELZ_VISA_PATH . 'frontend/class-frontend.php';
    }

    /**
     * Initialize core hooks.
     */
    private function init_hooks() {
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
    }

    /**
     * Load localization.
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'travel-z-visa', false, dirname( plugin_basename( TRAVELZ_VISA_FILE ) ) . '/languages' );
    }

    /**
     * Plugin activation.
     */
    public static function activate() {
        require_once TRAVELZ_VISA_PATH . 'includes/class-post-type.php';
        TravelZ_Visa_Post_Type::register();
        
        // Seed default sample data if not already seeded
        require_once TRAVELZ_VISA_PATH . 'includes/class-sample-data.php';
        TravelZ_Visa_Sample_Data::maybe_seed();

        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation.
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
}
