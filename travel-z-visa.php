<?php
/**
 * Plugin Name: Travel-Z Visa Assistance
 * Description: Complete Visa Assistance management system with modern interactive flipbook viewer, SEO schema, dynamic admin control, and quick search hub.
 * Version: 1.0.5
 * Author: Kamrul Hasan & Yeasmin
 * Text Domain: travel-z-visa
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define Plugin Constants
define( 'TRAVELZ_VISA_VERSION', '1.0.5' );
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

/**
 * Filter plugin row meta to show 'Developed by Kamrul Hasan & Yeasmin'.
 */
add_filter( 'plugin_row_meta', 'travelz_visa_custom_plugin_row_meta', 10, 4 );
function travelz_visa_custom_plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
    if ( strpos( $plugin_file, 'travel-z-visa.php' ) !== false || strpos( $plugin_file, 'travelz-visa.php' ) !== false ) {
        $new_meta = array();
        if ( ! empty( $plugin_data['Version'] ) ) {
            $new_meta[] = sprintf( 'Version %s', $plugin_data['Version'] );
        }
        $new_meta[] = 'Developed by Kamrul Hasan & Yeasmin';
        return $new_meta;
    }
    return $plugin_meta;
}
