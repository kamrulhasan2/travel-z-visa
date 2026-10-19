<?php
/**
 * Structured Document Checklist Component
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id   = get_the_ID();
$checklist = get_post_meta( $post_id, '_travelz_visa_checklist', true );
if ( empty( $checklist ) || ! is_array( $checklist ) ) {
    return;
}

// Filter out empty tabs
$tabs = array(
    'general'    => array(
        'title' => __( 'General Documents', 'travel-z-visa' ),
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
        'data'  => isset( $checklist['general'] ) ? trim( $checklist['general'] ) : '',
    ),
    'employed'   => array(
        'title' => __( 'Job Holders', 'travel-z-visa' ),
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>',
        'data'  => isset( $checklist['employed'] ) ? trim( $checklist['employed'] ) : '',
    ),
    'business'   => array(
        'title' => __( 'Business Persons', 'travel-z-visa' ),
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
        'data'  => isset( $checklist['business'] ) ? trim( $checklist['business'] ) : '',
    ),
    'student'    => array(
        'title' => __( 'Students', 'travel-z-visa' ),
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
        'data'  => isset( $checklist['student'] ) ? trim( $checklist['student'] ) : '',
    ),
    'additional' => array(
        'title' => __( 'Other / Supporting', 'travel-z-visa' ),
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>',
        'data'  => isset( $checklist['additional'] ) ? trim( $checklist['additional'] ) : '',
    ),
);

$active_tabs = array_filter( $tabs, function( $t ) {
    return ! empty( $t['data'] );
} );

if ( empty( $active_tabs ) ) {
    return;
}

$first_tab_key = array_key_first( $active_tabs );
?>

<div class="travelz-checklist-section">
    <div class="travelz-section-title-wrap">
        <h3 class="travelz-section-title"><?php esc_html_e( 'Required Documents Checklist', 'travel-z-visa' ); ?></h3>
        <p class="travelz-section-desc"><?php esc_html_e( 'Please check your required documents according to your profession before submitting your visa file to Travel-Z.', 'travel-z-visa' ); ?></p>
    </div>

    <!-- Tab Buttons -->
    <div class="travelz-tabs-nav" role="tablist">
        <?php foreach ( $active_tabs as $key => $tab ) : ?>
            <button type="button" class="travelz-tab-btn <?php echo ( $key === $first_tab_key ) ? 'active' : ''; ?>" data-target="travelz-tab-<?php echo esc_attr( $key ); ?>">
                <?php echo $tab['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <span><?php echo esc_html( $tab['title'] ); ?></span>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Tab Contents -->
    <div class="travelz-tabs-content">
        <?php foreach ( $active_tabs as $key => $tab ) :
            $lines = explode( "\n", $tab['data'] );
            ?>
            <div class="travelz-tab-pane <?php echo ( $key === $first_tab_key ) ? 'active' : ''; ?>" id="travelz-tab-<?php echo esc_attr( $key ); ?>">
                <ul class="travelz-checklist-items">
                    <?php foreach ( $lines as $line ) :
                        $line = trim( $line );
                        if ( empty( $line ) ) {
                            continue;
                        }
                        // Remove leading numbering if already typed
                        $clean_line = preg_replace( '/^[0-9]+[\.\-\)]\s*/', '', $line );
                        ?>
                        <li class="travelz-checklist-item">
                            <span class="travelz-check-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </span>
                            <span class="travelz-check-text"><?php echo esc_html( $clean_line ); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</div>
