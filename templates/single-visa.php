<?php
/**
 * Single Visa Template
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

while ( have_posts() ) :
    the_post();

    $post_id   = get_the_ID();
    $title     = get_the_title();
    $flag_url  = get_post_meta( $post_id, '_travelz_visa_flag_url', true );
    $hub_url   = home_url( '/' . TravelZ_Visa_Post_Type::get_slug() . '/' );
    ?>

    <div class="travelz-visa-single-page">
        
        <!-- Hero Header -->
        <div class="travelz-single-hero">
            <div class="travelz-container">
                <div class="travelz-hero-inner">
                    <div class="travelz-hero-title-area">
                        <?php if ( $flag_url ) : ?>
                            <img src="<?php echo esc_url( $flag_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="travelz-hero-flag" />
                        <?php endif; ?>
                        <h1 class="travelz-hero-heading"><?php echo esc_html( $title ); ?> <?php esc_html_e( 'Visa', 'travel-z-visa' ); ?></h1>
                    </div>
                    
                    <!-- Breadcrumbs -->
                    <nav class="travelz-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'travel-z-visa' ); ?>">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'travel-z-visa' ); ?></a>
                        <span class="travelz-bc-sep">/</span>
                        <a href="<?php echo esc_url( $hub_url ); ?>"><?php esc_html_e( 'Visa Assistance', 'travel-z-visa' ); ?></a>
                        <span class="travelz-bc-sep">/</span>
                        <span class="travelz-bc-current"><?php echo esc_html( $title ); ?></span>
                    </nav>
                </div>
            </div>
        </div>

        <div class="travelz-container">
            
            <!-- Top Search / Country Switcher -->
            <?php include TRAVELZ_VISA_PATH . 'templates/parts/country-switcher.php'; ?>

            <!-- Core 2-Column Section (Flipbook Left + Overview Right) -->
            <div class="travelz-detail-grid">
                
                <!-- Left: Flipbook / Document Reader -->
                <div class="travelz-grid-flipbook">
                    <?php include TRAVELZ_VISA_PATH . 'templates/parts/flipbook-viewer.php'; ?>
                </div>

                <!-- Right: Overview Box & Actions -->
                <div class="travelz-grid-overview">
                    <?php include TRAVELZ_VISA_PATH . 'templates/parts/overview-card.php'; ?>
                </div>

            </div>

            <!-- Optional Extra Editor Content -->
            <?php if ( get_the_content() ) : ?>
                <div class="travelz-post-content-card">
                    <?php the_content(); ?>
                </div>
            <?php endif; ?>

            <!-- Document Checklist (SEO Boost) -->
            <?php include TRAVELZ_VISA_PATH . 'templates/parts/checklist.php'; ?>

            <!-- FAQs Accordion -->
            <?php include TRAVELZ_VISA_PATH . 'templates/parts/faqs.php'; ?>

        </div>
    </div>

    <?php
endwhile;

get_footer();
