<?php
/**
 * Country Switcher / Search Bar (Top of Visa Pages)
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$current_id = get_the_ID();
$all_visas = get_posts( array(
    'post_type'      => TravelZ_Visa_Post_Type::POST_TYPE,
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
    'post_status'    => 'publish',
) );
?>
<div class="travelz-top-switcher-container">
    <div class="travelz-switcher-inner">
        <div class="travelz-select-wrapper">
            <select class="travelz-country-select" id="travelz-country-dropdown" onchange="if(this.value) window.location.href=this.value;">
                <option value=""><?php esc_html_e( 'Select Country', 'travel-z-visa' ); ?></option>
                <?php foreach ( $all_visas as $visa ) : ?>
                    <option value="<?php echo esc_url( get_permalink( $visa->ID ) ); ?>" <?php selected( $visa->ID, $current_id ); ?>>
                        <?php echo esc_html( $visa->post_title ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="travelz-instant-search-wrap">
            <input type="text" class="travelz-quick-search-input" id="travelz-quick-search" placeholder="<?php esc_attr_e( 'Search destination country...', 'travel-z-visa' ); ?>" autocomplete="off" />
            <button type="button" class="travelz-switcher-btn" id="travelz-quick-search-btn">
                <?php esc_html_e( 'Search', 'travel-z-visa' ); ?>
            </button>
            <div class="travelz-quick-dropdown-results" id="travelz-quick-results" style="display: none;"></div>
        </div>
    </div>
</div>
