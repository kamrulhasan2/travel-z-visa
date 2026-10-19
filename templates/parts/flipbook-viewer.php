<?php
/**
 * Interactive Flipbook / Document Reader Component
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id         = get_the_ID();
$title           = get_the_title( $post_id );
$pdf_url         = get_post_meta( $post_id, '_travelz_visa_pdf_url', true );
$viewer_type     = get_post_meta( $post_id, '_travelz_visa_viewer_type', true );
$enable_download = get_post_meta( $post_id, '_travelz_visa_enable_download', true );
$download_text   = get_post_meta( $post_id, '_travelz_visa_download_text', true );

if ( empty( $download_text ) ) {
    $download_text = __( 'Download Requirement PDF', 'travel-z-visa' );
}
?>

<div class="travelz-flipbook-wrapper" id="travelz-flipbook-container" data-pdf="<?php echo esc_url( $pdf_url ); ?>" data-title="<?php echo esc_attr( $title ); ?>">
    
    <!-- Top Toolbar Controls -->
    <div class="travelz-flipbook-toolbar">
        <div class="travelz-toolbar-group travelz-nav-controls">
            <button type="button" class="travelz-fb-btn travelz-fb-prev" id="travelz-fb-prev" title="<?php esc_attr_e( 'Previous Page', 'travel-z-visa' ); ?>" aria-label="<?php esc_attr_e( 'Previous Page', 'travel-z-visa' ); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <span class="travelz-fb-pages">
                <span id="travelz-fb-current-page">1</span> / <span id="travelz-fb-total-pages">--</span>
            </span>
            <button type="button" class="travelz-fb-btn travelz-fb-next" id="travelz-fb-next" title="<?php esc_attr_e( 'Next Page', 'travel-z-visa' ); ?>" aria-label="<?php esc_attr_e( 'Next Page', 'travel-z-visa' ); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
        </div>

        <div class="travelz-toolbar-group travelz-view-controls">
            <button type="button" class="travelz-fb-btn" id="travelz-fb-zoom-in" title="<?php esc_attr_e( 'Zoom In', 'travel-z-visa' ); ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
            </button>
            <button type="button" class="travelz-fb-btn" id="travelz-fb-zoom-out" title="<?php esc_attr_e( 'Zoom Out', 'travel-z-visa' ); ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
            </button>
            <button type="button" class="travelz-fb-btn" id="travelz-fb-fullscreen" title="<?php esc_attr_e( 'Toggle Fullscreen', 'travel-z-visa' ); ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"></polyline><polyline points="9 21 3 21 3 15"></polyline><line x1="21" y1="3" x2="14" y2="10"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg>
            </button>
        </div>

        <?php if ( ! empty( $pdf_url ) && '1' === $enable_download ) : ?>
            <div class="travelz-toolbar-group travelz-download-group">
                <a href="<?php echo esc_url( $pdf_url ); ?>" download class="travelz-fb-download-btn" target="_blank" rel="noopener noreferrer">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    <?php echo esc_html( $download_text ); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Viewer Stage -->
    <div class="travelz-flipbook-stage" id="travelz-flipbook-stage">
        <?php if ( ! empty( $pdf_url ) ) : ?>
            <div class="travelz-book-viewport" id="travelz-book-viewport">
                <!-- PDF.js Canvas / Flip Pages Render Container -->
                <div class="travelz-flipbook-canvas-area" id="travelz-flipbook-canvas-area">
                    <div class="travelz-fb-loading">
                        <div class="travelz-spinner"></div>
                        <p><?php esc_html_e( 'Loading Requirement Document...', 'travel-z-visa' ); ?></p>
                    </div>
                </div>

                <!-- Fallback Iframe/Embed for instant display & reliability -->
                <iframe src="<?php echo esc_url( $pdf_url ); ?>#toolbar=0&navpanes=0&scrollbar=0" class="travelz-native-pdf-embed" id="travelz-native-pdf-embed" title="<?php echo esc_attr( $title ); ?>" style="display:none; width: 100%; height: 600px; border: none; border-radius: 8px;"></iframe>
            </div>
        <?php else : ?>
            <div class="travelz-no-pdf-notice">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                <p><?php esc_html_e( 'Visa Requirement document is being updated by our visa consultants. Please contact us for immediate assistance.', 'travel-z-visa' ); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bottom Controls for Mobile / Quick Navigation -->
    <div class="travelz-flipbook-footer-bar">
        <button type="button" class="travelz-footer-nav-btn travelz-fb-prev">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
            <?php esc_html_e( 'Previous', 'travel-z-visa' ); ?>
        </button>
        <span class="travelz-footer-page-info">
            <?php esc_html_e( 'Page', 'travel-z-visa' ); ?> <span class="travelz-cur-page-num">1</span> <?php esc_html_e( 'of', 'travel-z-visa' ); ?> <span class="travelz-tot-page-num">--</span>
        </span>
        <button type="button" class="travelz-footer-nav-btn travelz-fb-next">
            <?php esc_html_e( 'Next', 'travel-z-visa' ); ?>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
    </div>

</div>
