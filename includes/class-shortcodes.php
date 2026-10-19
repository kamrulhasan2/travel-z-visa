<?php
/**
 * Shortcodes for Travel-Z Visa
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TravelZ_Visa_Shortcodes {

    /**
     * Initialize.
     */
    public static function init() {
        add_shortcode( 'travelz_visa_hub', array( __CLASS__, 'render_visa_hub' ) );
        add_shortcode( 'travelz_visa_search', array( __CLASS__, 'render_search_bar' ) );
        add_shortcode( 'travelz_visa_card', array( __CLASS__, 'render_single_card' ) );
    }

    /**
     * Render Full Visa Hub.
     */
    public static function render_visa_hub( $atts ) {
        ob_start();
        include TRAVELZ_VISA_PATH . 'templates/archive-visa.php';
        return ob_get_clean();
    }

    /**
     * Render Compact Search Bar.
     */
    public static function render_search_bar( $atts ) {
        $atts = shortcode_atts( array(
            'placeholder' => __( 'Select or search a country...', 'travel-z-visa' ),
            'button_text' => __( 'Search Visa', 'travel-z-visa' ),
        ), $atts, 'travelz_visa_search' );

        $visas = get_posts( array(
            'post_type'      => TravelZ_Visa_Post_Type::POST_TYPE,
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ) );

        ob_start();
        ?>
        <div class="travelz-compact-search-box">
            <div class="travelz-search-input-wrap">
                <span class="travelz-search-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <input type="text" class="travelz-search-input" placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>" autocomplete="off" />
                <button type="button" class="travelz-search-submit-btn"><?php echo esc_html( $atts['button_text'] ); ?></button>
            </div>
            <div class="travelz-search-dropdown" style="display: none;">
                <ul class="travelz-dropdown-list">
                    <?php foreach ( $visas as $visa ) :
                        $flag = get_post_meta( $visa->ID, '_travelz_visa_flag_url', true );
                        $type = get_post_meta( $visa->ID, '_travelz_visa_type', true );
                        ?>
                        <li data-url="<?php echo esc_url( get_permalink( $visa->ID ) ); ?>" data-title="<?php echo esc_attr( $visa->post_title ); ?>">
                            <?php if ( $flag ) : ?>
                                <img src="<?php echo esc_url( $flag ); ?>" alt="" class="travelz-dd-flag" />
                            <?php endif; ?>
                            <span class="travelz-dd-name"><?php echo esc_html( $visa->post_title ); ?></span>
                            <?php if ( $type ) : ?>
                                <span class="travelz-dd-type"><?php echo esc_html( $type ); ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Single Visa Card Shortcode.
     */
    public static function render_single_card( $atts ) {
        $atts = shortcode_atts( array(
            'id' => 0,
        ), $atts, 'travelz_visa_card' );

        $post_id = intval( $atts['id'] );
        if ( ! $post_id ) {
            return '';
        }

        $visa = get_post( $post_id );
        if ( ! $visa || TravelZ_Visa_Post_Type::POST_TYPE !== $visa->post_type ) {
            return '';
        }

        ob_start();
        include TRAVELZ_VISA_PATH . 'templates/parts/overview-card.php';
        return ob_get_clean();
    }
}

TravelZ_Visa_Shortcodes::init();
