<?php
/**
 * FAQs Accordion Component
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id = get_the_ID();
$faqs    = get_post_meta( $post_id, '_travelz_visa_faqs', true );

if ( empty( $faqs ) || ! is_array( $faqs ) ) {
    return;
}
?>

<div class="travelz-faqs-section">
    <div class="travelz-section-title-wrap">
        <h3 class="travelz-section-title"><?php esc_html_e( 'Frequently Asked Questions', 'travel-z-visa' ); ?></h3>
        <p class="travelz-section-desc"><?php esc_html_e( 'Find quick answers regarding visa processing times, interview requirements, and payments.', 'travel-z-visa' ); ?></p>
    </div>

    <div class="travelz-accordion">
        <?php foreach ( $faqs as $index => $faq ) :
            if ( empty( $faq['q'] ) || empty( $faq['a'] ) ) {
                continue;
            }
            ?>
            <div class="travelz-accordion-item <?php echo ( 0 === $index ) ? 'open' : ''; ?>">
                <button type="button" class="travelz-accordion-header" aria-expanded="<?php echo ( 0 === $index ) ? 'true' : 'false'; ?>">
                    <span class="travelz-faq-title"><?php echo esc_html( $faq['q'] ); ?></span>
                    <span class="travelz-accordion-arrow">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </button>
                <div class="travelz-accordion-body" style="<?php echo ( 0 === $index ) ? 'display: block;' : 'display: none;'; ?>">
                    <div class="travelz-accordion-content">
                        <?php echo wpautop( esc_html( $faq['a'] ) ); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
