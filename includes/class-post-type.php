<?php
/**
 * Post Type and Taxonomies Registration
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TravelZ_Visa_Post_Type {

    /**
     * Post type name.
     */
    const POST_TYPE = 'travelz_visa';

    /**
     * Taxonomy name.
     */
    const TAXONOMY = 'visa_region';

    /**
     * Initialize.
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'register' ) );
        add_filter( 'post_updated_messages', array( __CLASS__, 'updated_messages' ) );
    }

    /**
     * Get configurable slug.
     *
     * @return string
     */
    public static function get_slug() {
        $settings = get_option( 'travelz_visa_settings', array() );
        $slug = ! empty( $settings['slug_prefix'] ) ? sanitize_title( $settings['slug_prefix'] ) : 'visa-assistance';
        return $slug;
    }

    /**
     * Register Post Type and Taxonomy.
     */
    public static function register() {
        $slug = self::get_slug();

        // 1. Register Taxonomy: visa_region
        $tax_labels = array(
            'name'              => _x( 'Regions / Continents', 'taxonomy general name', 'travel-z-visa' ),
            'singular_name'     => _x( 'Region', 'taxonomy singular name', 'travel-z-visa' ),
            'search_items'      => __( 'Search Regions', 'travel-z-visa' ),
            'all_items'         => __( 'All Regions', 'travel-z-visa' ),
            'parent_item'       => __( 'Parent Region', 'travel-z-visa' ),
            'parent_item_colon' => __( 'Parent Region:', 'travel-z-visa' ),
            'edit_item'         => __( 'Edit Region', 'travel-z-visa' ),
            'update_item'       => __( 'Update Region', 'travel-z-visa' ),
            'add_new_item'      => __( 'Add New Region', 'travel-z-visa' ),
            'new_item_name'     => __( 'New Region Name', 'travel-z-visa' ),
            'menu_name'         => __( 'Regions / Continents', 'travel-z-visa' ),
        );

        register_taxonomy( self::TAXONOMY, array( self::POST_TYPE ), array(
            'hierarchical'      => true,
            'labels'            => $tax_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'visa-region' ),
            'show_in_rest'      => true,
        ) );

        // 2. Register Custom Post Type: travelz_visa
        $cpt_labels = array(
            'name'                  => _x( 'Visa Assistance', 'Post type general name', 'travel-z-visa' ),
            'singular_name'         => _x( 'Country Visa', 'Post type singular name', 'travel-z-visa' ),
            'menu_name'             => _x( 'Travel-Z Visa', 'Admin Menu text', 'travel-z-visa' ),
            'name_admin_bar'        => _x( 'Visa Country', 'Add New on Toolbar', 'travel-z-visa' ),
            'add_new'               => __( 'Add New Visa', 'travel-z-visa' ),
            'add_new_item'          => __( 'Add New Country Visa', 'travel-z-visa' ),
            'new_item'              => __( 'New Country Visa', 'travel-z-visa' ),
            'edit_item'             => __( 'Edit Country Visa', 'travel-z-visa' ),
            'view_item'             => __( 'View Country Visa', 'travel-z-visa' ),
            'all_items'             => __( 'All Visas', 'travel-z-visa' ),
            'search_items'          => __( 'Search Visas', 'travel-z-visa' ),
            'parent_item_colon'     => __( 'Parent Visas:', 'travel-z-visa' ),
            'not_found'             => __( 'No visas found.', 'travel-z-visa' ),
            'not_found_in_trash'    => __( 'No visas found in Trash.', 'travel-z-visa' ),
            'featured_image'        => _x( 'Country Flag / Banner', 'Overrides the "Featured Image" phrase', 'travel-z-visa' ),
            'set_featured_image'    => _x( 'Set country flag or banner', 'Overrides the "Set featured image" phrase', 'travel-z-visa' ),
            'remove_featured_image' => _x( 'Remove country image', 'Overrides the "Remove featured image" phrase', 'travel-z-visa' ),
            'use_featured_image'    => _x( 'Use as country image', 'Overrides the "Use as featured image" phrase', 'travel-z-visa' ),
        );

        $args = array(
            'labels'             => $cpt_labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array(
                'slug'       => $slug,
                'with_front' => false,
            ),
            'capability_type'    => 'post',
            'has_archive'        => $slug,
            'hierarchical'       => false,
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-airplane',
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
            'show_in_rest'       => true,
        );

        register_post_type( self::POST_TYPE, $args );
    }

    /**
     * Updated messages for post type.
     *
     * @param array $messages
     * @return array
     */
    public static function updated_messages( $messages ) {
        global $post, $post_ID;

        $messages[ self::POST_TYPE ] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => sprintf( __( 'Visa details updated. <a href="%s">View Visa</a>', 'travel-z-visa' ), esc_url( get_permalink( $post_ID ) ) ),
            4  => __( 'Visa details updated.', 'travel-z-visa' ),
            6  => sprintf( __( 'Visa published. <a href="%s">View Visa</a>', 'travel-z-visa' ), esc_url( get_permalink( $post_ID ) ) ),
            7  => __( 'Visa saved.', 'travel-z-visa' ),
        );

        return $messages;
    }
}

TravelZ_Visa_Post_Type::init();
