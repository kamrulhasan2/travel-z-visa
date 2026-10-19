<?php
/**
 * Visa Overview Card Component
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id         = get_the_ID();
$title           = get_the_title( $post_id );
$visa_type       = get_post_meta( $post_id, '_travelz_visa_type', true );
$duration        = get_post_meta( $post_id, '_travelz_visa_duration', true );
$processing_time = get_post_meta( $post_id, '_travelz_visa_processing_time', true );
$fees            = get_post_meta( $post_id, '_travelz_visa_fees', true );
$settings        = TravelZ_Visa_Settings::get_settings();

// Query URL setup
$custom_query_url = get_post_meta( $post_id, '_travelz_visa_custom_query_url', true );
$query_base_url   = ! empty( $custom_query_url ) ? $custom_query_url : $settings['query_url'];
$query_url        = add_query_arg( array( 'country' => urlencode( $title ) ), $query_base_url );

// WhatsApp URL setup
$custom_wa = get_post_meta( $post_id, '_travelz_visa_custom_whatsapp', true );
$wa_number = ! empty( $custom_wa ) ? preg_replace( '/[^0-9]/', '', $custom_wa ) : $settings['whatsapp'];
$wa_msg    = rawurlencode( sprintf( "Hello Travel-Z, I am interested in %s Visa Assistance. Please provide me details.", $title ) );
$wa_url    = "https://wa.me/{$wa_number}?text={$wa_msg}";
?>

<div class="travelz-overview-card">
    <div class="travelz-overview-header">
        <h3 class="travelz-overview-title"><?php esc_html_e( 'Overview:', 'travel-z-visa' ); ?></h3>
    </div>

    <div class="travelz-overview-body">
        <?php if ( $visa_type ) : ?>
            <div class="travelz-overview-row">
                <span class="travelz-row-label"><?php esc_html_e( 'Type of visa:', 'travel-z-visa' ); ?></span>
                <span class="travelz-row-value"><?php echo esc_html( $visa_type ); ?></span>
            </div>
        <?php endif; ?>

        <?php if ( $duration ) : ?>
            <div class="travelz-overview-row">
                <span class="travelz-row-label"><?php esc_html_e( 'Visa Duration:', 'travel-z-visa' ); ?></span>
                <span class="travelz-row-value"><?php echo esc_html( $duration ); ?></span>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $fees ) && is_array( $fees ) ) : ?>
            <div class="travelz-overview-row travelz-fees-row">
                <span class="travelz-row-label"><?php esc_html_e( 'Visa fees:', 'travel-z-visa' ); ?></span>
                <div class="travelz-fees-list">
                    <?php foreach ( $fees as $index => $fee ) : ?>
                        <div class="travelz-fee-item">
                            <span class="travelz-fee-amount"><?php echo esc_html( $fee['amount'] ); ?></span>
                            <?php if ( ! empty( $fee['label'] ) ) : ?>
                                <span class="travelz-fee-label">(<?php echo esc_html( $fee['label'] ); ?>)</span>
                            <?php endif; ?>
                            <?php if ( ! empty( $fee['note'] ) ) : ?>
                                <small class="travelz-fee-note">- <?php echo esc_html( $fee['note'] ); ?></small>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( $processing_time ) : ?>
            <div class="travelz-overview-row">
                <span class="travelz-row-label"><?php esc_html_e( 'File processing time (Travel Z):', 'travel-z-visa' ); ?></span>
                <span class="travelz-row-value"><?php echo esc_html( $processing_time ); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Actions Section -->
    <div class="travelz-overview-actions">
        <!-- Option C: Queries Redirect Button -->
        <a href="<?php echo esc_url( $query_url ); ?>" class="travelz-btn travelz-btn-query">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            <?php esc_html_e( 'Queries / Apply Now', 'travel-z-visa' ); ?>
        </a>

        <!-- WhatsApp Direct Chat Button -->
        <?php if ( ! empty( $wa_number ) ) : ?>
            <a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" class="travelz-btn travelz-btn-whatsapp">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                <?php esc_html_e( 'Chat on WhatsApp', 'travel-z-visa' ); ?>
            </a>
        <?php endif; ?>

        <!-- Hotline Call Button -->
        <?php if ( ! empty( $settings['phone'] ) ) : ?>
            <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $settings['phone'] ) ); ?>" class="travelz-btn travelz-btn-phone">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                <?php echo esc_html( $settings['phone'] ); ?>
            </a>
        <?php endif; ?>
    </div>
</div>
