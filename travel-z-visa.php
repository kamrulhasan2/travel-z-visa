<?php
/**
 * Plugin Name: Travel-Z Visa Assistance
 * Plugin URI: https://tech.travelzbd.com/
 * Description: Complete Visa Assistance management system with modern interactive flipbook viewer, SEO schema, dynamic admin control, and quick search hub.
 * Version: 1.0.4
 * Author: Travel-Z / E-GUIDER
 * Author URI: https://tech.travelzbd.com/
 * Text Domain: travel-z-visa
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define Plugin Constants
define( 'TRAVELZ_VISA_VERSION', '1.0.4' );
define( 'TRAVELZ_VISA_FILE', __FILE__ );
define( 'TRAVELZ_VISA_PATH', plugin_dir_path( __FILE__ ) );
define( 'TRAVELZ_VISA_URL', plugin_dir_url( __FILE__ ) );

// Autoload Includes
require_once TRAVELZ_VISA_PATH . 'includes/class-travelz-visa.php';

/**
 * Main instance of Travel-Z Visa plugin.
 *
 * @return TravelZ_Visa
 */
function travelz_visa() {
    return TravelZ_Visa::instance();
}

// Initialize Plugin
travelz_visa();

// Activation hook
register_activation_hook( __FILE__, array( 'TravelZ_Visa', 'activate' ) );

// Deactivation hook
register_deactivation_hook( __FILE__, array( 'TravelZ_Visa', 'deactivate' ) );
