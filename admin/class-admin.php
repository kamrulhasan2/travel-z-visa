<?php
/**
 * Admin Assets & Post List Customizations
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TravelZ_Visa_Admin {

    /**
     * Initialize.
     */
    public static function init() {
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
        add_filter( 'manage_' . TravelZ_Visa_Post_Type::POST_TYPE . '_posts_columns', array( __CLASS__, 'manage_columns' ) );
        add_action( 'manage_' . TravelZ_Visa_Post_Type::POST_TYPE . '_posts_custom_column', array( __CLASS__, 'render_columns' ), 10, 2 );
        add_filter( 'plugin_row_meta', array( __CLASS__, 'customize_plugin_row_meta' ), 10, 4 );
    }

    /**
     * Enqueue Admin Assets.
     */
    public static function enqueue_assets( $hook ) {
        global $post_type;

        if ( TravelZ_Visa_Post_Type::POST_TYPE === $post_type || ( isset( $_GET['page'] ) && 'travelz-visa-settings' === $_GET['page'] ) ) {
            wp_enqueue_media();

            wp_enqueue_style(
                'travelz-visa-admin-style',
                TRAVELZ_VISA_URL . 'admin/css/admin-style.css',
                array(),
                TRAVELZ_VISA_VERSION
            );

            wp_enqueue_script(
                'travelz-visa-admin-script',
                TRAVELZ_VISA_URL . 'admin/js/admin-script.js',
                array( 'jquery' ),
                TRAVELZ_VISA_VERSION,
                true
            );

            wp_localize_script( 'travelz-visa-admin-script', 'travelzAdmin', array(
                'choosePdf'  => __( 'Select or Upload Visa PDF', 'travel-z-visa' ),
                'usePdf'     => __( 'Use this PDF', 'travel-z-visa' ),
                'chooseFlag' => __( 'Select or Upload Country Flag', 'travel-z-visa' ),
                'useFlag'    => __( 'Use as Flag', 'travel-z-visa' ),
            ) );
        }
    }

    /**
     * Custom Columns in Visa List.
     */
    public static function manage_columns( $columns ) {
        $new_columns = array();
        $new_columns['cb']               = $columns['cb'];
        $new_columns['travelz_flag']     = __( 'Flag', 'travel-z-visa' );
        $new_columns['title']            = __( 'Country Name', 'travel-z-visa' );
        $new_columns['travelz_type']     = __( 'Visa Type', 'travel-z-visa' );
        $new_columns['travelz_duration'] = __( 'Duration', 'travel-z-visa' );
        $new_columns['travelz_fees']     = __( 'Fees Range', 'travel-z-visa' );
        $new_columns['travelz_pdf']      = __( 'PDF Document', 'travel-z-visa' );
        $new_columns['date']             = $columns['date'];

        return $new_columns;
    }

    /**
     * Render Custom Columns.
     */
    public static function render_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'travelz_flag':
                $flag_url = get_post_meta( $post_id, '_travelz_visa_flag_url', true );
                if ( ! $flag_url && has_post_thumbnail( $post_id ) ) {
                    $flag_url = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
                }
                if ( $flag_url ) {
                    echo '<img src="' . esc_url( $flag_url ) . '" style="width: 38px; height: 26px; object-fit: cover; border-radius: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.15);" alt="" />';
                } else {
                    echo '<span class="dashicons dashicons-flag" style="color: #94a3b8; font-size: 24px;"></span>';
                }
                break;

            case 'travelz_type':
                $type = get_post_meta( $post_id, '_travelz_visa_type', true );
                echo esc_html( $type ? $type : '—' );
                break;

            case 'travelz_duration':
                $duration = get_post_meta( $post_id, '_travelz_visa_duration', true );
                echo esc_html( $duration ? $duration : '—' );
                break;

            case 'travelz_fees':
                $fees = get_post_meta( $post_id, '_travelz_visa_fees', true );
                if ( ! empty( $fees ) && is_array( $fees ) ) {
                    $first = reset( $fees );
                    $last  = end( $fees );
                    if ( count( $fees ) > 1 ) {
                        echo '<span style="font-weight: 600; color: #0ebac5;">' . esc_html( $first['amount'] ) . ' - ' . esc_html( $last['amount'] ) . '</span> (' . count( $fees ) . ' tiers)';
                    } else {
                        echo '<span style="font-weight: 600; color: #0ebac5;">' . esc_html( $first['amount'] ) . '</span>';
                    }
                } else {
                    echo '—';
                }
                break;

            case 'travelz_pdf':
                $pdf_url = get_post_meta( $post_id, '_travelz_visa_pdf_url', true );
                if ( $pdf_url ) {
                    echo '<a href="' . esc_url( $pdf_url ) . '" target="_blank" class="button button-small"><span class="dashicons dashicons-pdf" style="font-size: 16px; vertical-align: middle;"></span> ' . esc_html__( 'View PDF', 'travel-z-visa' ) . '</a>';
                } else {
                    echo '<span style="color: #ef4444;">' . esc_html__( 'No PDF attached', 'travel-z-visa' ) . '</span>';
                }
                break;
        }
    }

    /**
     * Customize plugin row meta on Plugins list table.
     */
    public static function customize_plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
        if ( strpos( $plugin_file, 'travel-z-visa' ) !== false || strpos( $plugin_file, 'travelz-visa' ) !== false ) {
            $new_meta = array();
            if ( ! empty( $plugin_data['Version'] ) ) {
                $new_meta[] = sprintf( __( 'Version %s', 'travel-z-visa' ), $plugin_data['Version'] );
            }
            $new_meta[] = __( 'Developed by Kamrul Hasan & Yeasmin', 'travel-z-visa' );
            return $new_meta;
        }
        return $plugin_meta;
    }
}

TravelZ_Visa_Admin::init();
