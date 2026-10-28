<?php
/**
 * Archive / Hub Template for Visa Assistance
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $travelz_is_shortcode;
$travelz_is_standalone = empty( $travelz_is_shortcode );

if ( $travelz_is_standalone ) {
    get_header();
}

// Fetch all visas
$visas = get_posts( array(
    'post_type'      => TravelZ_Visa_Post_Type::POST_TYPE,
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
    'post_status'    => 'publish',
) );

// Fetch all regions
$regions = get_terms( array(
    'taxonomy'   => TravelZ_Visa_Post_Type::TAXONOMY,
    'hide_empty' => true,
) );

$settings             = TravelZ_Visa_Settings::get_settings();
$hero_bg_image        = ! empty( $settings['hero_bg_image'] ) ? $settings['hero_bg_image'] : '';
$hero_overlay_opacity = isset( $settings['hero_overlay_opacity'] ) ? floatval( $settings['hero_overlay_opacity'] ) : 75;
$hero_overlay_color   = ! empty( $settings['hero_overlay_color'] ) ? $settings['hero_overlay_color'] : '#ffffff';

$hero_badge_text         = ! empty( $settings['hero_badge_text'] ) ? $settings['hero_badge_text'] : __( 'Travel-Z Visa Services', 'travel-z-visa' );
$hero_badge_color        = ! empty( $settings['hero_badge_color'] ) ? $settings['hero_badge_color'] : '#0ebac5';
$hero_badge_bg           = ! empty( $settings['hero_badge_bg'] ) ? $settings['hero_badge_bg'] : '#e6f9fa';
$hero_title              = ! empty( $settings['hero_title'] ) ? $settings['hero_title'] : __( 'Comprehensive Visa Assistance for a Seamless Travel Experience', 'travel-z-visa' );
$hero_title_color        = ! empty( $settings['hero_title_color'] ) ? $settings['hero_title_color'] : '#0f172a';
$hero_description        = ! empty( $settings['hero_description'] ) ? $settings['hero_description'] : __( 'Navigating the complexities of visa applications can be challenging. Our dedicated Visa Assistance service offers expert guidance and support to ensure a smooth and stress-free process. Whether you are traveling for business, leisure, or educational purposes, we provide tailored assistance for all types of visas.', 'travel-z-visa' );
$hero_desc_color         = ! empty( $settings['hero_desc_color'] ) ? $settings['hero_desc_color'] : '#475569';
$hero_search_placeholder = ! empty( $settings['hero_search_placeholder'] ) ? $settings['hero_search_placeholder'] : __( 'Search your destination country (e.g. China, Australia, Japan)...', 'travel-z-visa' );

$hero_classes = array( 'travelz-hub-hero' );
$hero_styles  = array();

if ( ! empty( $hero_bg_image ) ) {
    $hero_classes[] = 'has-bg-image';

    // Convert hex overlay color to RGB
    $hex = str_replace( '#', '', $hero_overlay_color );
    if ( strlen( $hex ) === 3 ) {
        $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
        $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
        $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
    } else {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
    }

    $alpha1 = max( 0, min( 1, $hero_overlay_opacity / 100 ) );
    $alpha2 = max( 0, min( 1, $alpha1 - 0.08 ) );

    $hero_styles[] = sprintf(
        'background-image: linear-gradient(135deg, rgba(%d, %d, %d, %.2f), rgba(%d, %d, %d, %.2f)), url("%s");',
        $r, $g, $b, $alpha1,
        $r, $g, $b, $alpha2,
        esc_url( $hero_bg_image )
    );
    $hero_styles[] = 'background-position: center center;';
    $hero_styles[] = 'background-size: cover;';
    $hero_styles[] = 'background-repeat: no-repeat;';

    // If overlay is dark, apply dark mode class for text
    if ( $r < 100 && $g < 100 && $b < 100 && $alpha1 > 0.45 ) {
        $hero_classes[] = 'overlay-dark';
    }
}
?>

<?php if ( $travelz_is_standalone ) : ?>
<div id="primary" class="content-area primary travelz-primary-wrap">
    <main id="main" class="site-main travelz-main-wrap">
<?php endif; ?>

<div class="travelz-visa-hub-page">
    
    <!-- Hero Banner -->
    <div class="<?php echo esc_attr( implode( ' ', $hero_classes ) ); ?>" style="<?php echo esc_attr( implode( ' ', $hero_styles ) ); ?>">
        <div class="travelz-container">
            <div class="travelz-hub-hero-content">
                <span class="travelz-hub-badge" style="color: <?php echo esc_attr( $hero_badge_color ); ?>; background-color: <?php echo esc_attr( $hero_badge_bg ); ?>;"><?php echo esc_html( $hero_badge_text ); ?></span>
                <h1 class="travelz-hub-title" style="color: <?php echo esc_attr( $hero_title_color ); ?>;"><?php echo esc_html( $hero_title ); ?></h1>
                <p class="travelz-hub-desc" style="color: <?php echo esc_attr( $hero_desc_color ); ?>;">
                    <?php echo nl2br( esc_html( $hero_description ) ); ?>
                </p>

                <!-- Search Box inside Hero -->
                <div class="travelz-hub-search-wrapper">
                    <div class="travelz-hub-search-bar">
                        <span class="travelz-hub-search-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </span>
                        <input type="text" id="travelz-hub-filter-input" class="travelz-hub-search-input" placeholder="<?php echo esc_attr( $hero_search_placeholder ); ?>" autocomplete="off" />
                        <button type="button" class="travelz-hub-search-btn">
                            <?php esc_html_e( 'Explore', 'travel-z-visa' ); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="travelz-container">

        <!-- Region Filter Tabs -->
        <?php if ( ! empty( $regions ) && ! is_wp_error( $regions ) ) : ?>
            <div class="travelz-region-filters">
                <button type="button" class="travelz-region-btn active" data-region="all">
                    <span class="travelz-pill-text"><?php esc_html_e( 'All Destinations', 'travel-z-visa' ); ?></span>
                    <span class="travelz-pill-count"><?php echo count( $visas ); ?></span>
                </button>
                <?php foreach ( $regions as $reg ) : ?>
                    <button type="button" class="travelz-region-btn" data-region="<?php echo esc_attr( $reg->slug ); ?>">
                        <span class="travelz-pill-text"><?php echo esc_html( $reg->name ); ?></span>
                        <span class="travelz-pill-count"><?php echo esc_html( $reg->count ); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Country Cards Grid -->
        <div class="travelz-countries-grid" id="travelz-countries-grid">
            <?php
            if ( ! empty( $visas ) ) :
                foreach ( $visas as $visa ) :
                    $post_id         = $visa->ID;
                    $title           = $visa->post_title;
                    $permalink       = get_permalink( $post_id );
                    $flag_url        = get_post_meta( $post_id, '_travelz_visa_flag_url', true );
                    $visa_type       = get_post_meta( $post_id, '_travelz_visa_type', true );
                    $duration        = get_post_meta( $post_id, '_travelz_visa_duration', true );
                    $processing_time = get_post_meta( $post_id, '_travelz_visa_processing_time', true );
                    $fees            = get_post_meta( $post_id, '_travelz_visa_fees', true );

                    // Get terms for filtering
                    $post_regions = wp_get_post_terms( $post_id, TravelZ_Visa_Post_Type::TAXONOMY, array( 'fields' => 'slugs' ) );
                    $regions_attr = ! empty( $post_regions ) && ! is_wp_error( $post_regions ) ? implode( ' ', $post_regions ) : '';

                    // Get lowest starting fee
                    $starting_fee = '';
                    if ( ! empty( $fees ) && is_array( $fees ) ) {
                        $first_fee    = reset( $fees );
                        $starting_fee = $first_fee['amount'];
                    }
                    ?>
                    <div class="travelz-country-card" data-title="<?php echo esc_attr( strtolower( $title ) ); ?>" data-regions="<?php echo esc_attr( $regions_attr ); ?>">
                        <div class="travelz-card-flag-wrap">
                            <?php if ( $flag_url ) : ?>
                                <img src="<?php echo esc_url( $flag_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="travelz-card-flag" loading="lazy" />
                            <?php else : ?>
                                <span class="dashicons dashicons-flag travelz-fallback-flag"></span>
                            <?php endif; ?>
                            <span class="travelz-card-type-badge"><?php echo esc_html( $visa_type ? $visa_type : __( 'Tourist Visa', 'travel-z-visa' ) ); ?></span>
                        </div>

                        <div class="travelz-card-content">
                            <h3 class="travelz-card-title">
                                <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
                            </h3>

                            <div class="travelz-card-meta">
                                <?php if ( $duration ) : ?>
                                    <div class="travelz-card-meta-item">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        <span><?php echo esc_html( $duration ); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if ( $processing_time ) : ?>
                                    <div class="travelz-card-meta-item">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                        <span><?php echo esc_html( $processing_time ); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="travelz-card-footer">
                                <div class="travelz-card-price">
                                    <?php if ( $starting_fee ) : ?>
                                        <span class="travelz-price-label"><?php esc_html_e( 'Starts from', 'travel-z-visa' ); ?></span>
                                        <span class="travelz-price-val"><?php echo esc_html( $starting_fee ); ?></span>
                                    <?php else : ?>
                                        <span class="travelz-price-val"><?php esc_html_e( 'Consult Us', 'travel-z-visa' ); ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo esc_url( $permalink ); ?>" class="travelz-card-view-btn">
                                    <?php esc_html_e( 'View Details', 'travel-z-visa' ); ?>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
            else : ?>
                <div class="travelz-no-countries">
                    <p><?php esc_html_e( 'No countries found. Please check back later or contact our team.', 'travel-z-visa' ); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div id="travelz-no-results-msg" style="display: none; text-align: center; padding: 40px 20px;">
            <p style="font-size: 18px; color: #64748b;"><?php esc_html_e( 'No matching country found for your search. Please try another country name.', 'travel-z-visa' ); ?></p>
        </div>

        <!-- Helpline CTA Banner -->
        <div class="travelz-help-banner">
            <div class="travelz-help-content">
                <h3><?php esc_html_e( 'Need Personalized Visa Consultancy?', 'travel-z-visa' ); ?></h3>
                <p><?php esc_html_e( 'Our dedicated team in Dhanmondi, Dhaka is ready to review your documents and assist with your application.', 'travel-z-visa' ); ?></p>
            </div>
            <div class="travelz-help-actions">
                <a href="<?php echo esc_url( $settings['query_url'] ); ?>" class="travelz-help-btn-primary">
                    <?php esc_html_e( 'Submit Online Query', 'travel-z-visa' ); ?>
                </a>
                <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $settings['phone'] ) ); ?>" class="travelz-help-btn-call">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    <?php echo esc_html( $settings['phone'] ); ?>
                </a>
            </div>
        </div>

    </div>
</div>

<?php if ( $travelz_is_standalone ) : ?>
    </main>
</div>
<?php endif; ?>

<?php
if ( $travelz_is_standalone ) {
    get_footer();
}
