<?php
/**
 * SEO & Schema.org Structured Data
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TravelZ_Visa_SEO {

    /**
     * Initialize.
     */
    public static function init() {
        add_action( 'wp_head', array( __CLASS__, 'output_schema_and_meta' ), 5 );
    }

    /**
     * Output Schema & Meta in <head>.
     */
    public static function output_schema_and_meta() {
        if ( ! is_singular( TravelZ_Visa_Post_Type::POST_TYPE ) ) {
            return;
        }

        $settings = TravelZ_Visa_Settings::get_settings();
        if ( empty( $settings['enable_schema'] ) ) {
            return;
        }

        $post_id         = get_the_ID();
        $title           = get_the_title( $post_id );
        $permalink       = get_permalink( $post_id );
        $visa_type       = get_post_meta( $post_id, '_travelz_visa_type', true );
        $duration        = get_post_meta( $post_id, '_travelz_visa_duration', true );
        $processing_time = get_post_meta( $post_id, '_travelz_visa_processing_time', true );
        $fees            = get_post_meta( $post_id, '_travelz_visa_fees', true );
        $faqs            = get_post_meta( $post_id, '_travelz_visa_faqs', true );
        $flag_url        = get_post_meta( $post_id, '_travelz_visa_flag_url', true );
        if ( ! $flag_url && has_post_thumbnail( $post_id ) ) {
            $flag_url = get_the_post_thumbnail_url( $post_id, 'full' );
        }

        $meta_desc = sprintf(
            /* translators: 1: Country, 2: Visa Type, 3: Processing Time */
            __( '%1$s Visa Assistance by TravelZ. Type: %2$s. Processing time: %3$s. Full documents checklist, requirements flipbook, and fee details.', 'travel-z-visa' ),
            $title,
            $visa_type ? $visa_type : 'Tourist Visa',
            $processing_time ? $processing_time : '10-15 working days'
        );

        // 1. Breadcrumb Schema
        $hub_url = home_url( '/' . TravelZ_Visa_Post_Type::get_slug() . '/' );
        $breadcrumb_schema = array(
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => array(
                array(
                    '@type'    => 'ListItem',
                    'position' => 1,
                    'name'     => __( 'Home', 'travel-z-visa' ),
                    'item'     => home_url( '/' ),
                ),
                array(
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => __( 'Visa Assistance', 'travel-z-visa' ),
                    'item'     => $hub_url,
                ),
                array(
                    '@type'    => 'ListItem',
                    'position' => 3,
                    'name'     => $title,
                    'item'     => $permalink,
                ),
            ),
        );

        // 2. Service & Product Schema
        $service_schema = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'Service',
            'name'        => sprintf( __( '%s Visa Assistance Service', 'travel-z-visa' ), $title ),
            'serviceType' => $visa_type ? $visa_type : __( 'Visa Processing & Assistance', 'travel-z-visa' ),
            'provider'    => array(
                '@type'     => 'TravelAgency',
                'name'      => 'TravelZ',
                'url'       => home_url( '/' ),
                'telephone' => $settings['phone'],
                'email'     => $settings['email'],
                'address'   => array(
                    '@type'          => 'PostalAddress',
                    'streetAddress'  => $settings['office_address'],
                    'addressCountry' => 'BD',
                ),
            ),
            'description' => $meta_desc,
            'url'         => $permalink,
        );

        if ( $flag_url ) {
            $service_schema['image'] = $flag_url;
        }

        // Add Offer schema if fees exist
        if ( ! empty( $fees ) && is_array( $fees ) ) {
            $offers = array();
            $prices = array();

            foreach ( $fees as $fee ) {
                $amount_clean = preg_replace( '/[^0-9.]/', '', $fee['amount'] );
                if ( is_numeric( $amount_clean ) && $amount_clean > 0 ) {
                    $prices[] = floatval( $amount_clean );
                    $offers[] = array(
                        '@type'         => 'Offer',
                        'name'          => $fee['label'],
                        'price'         => $amount_clean,
                        'priceCurrency' => 'BDT',
                        'availability'  => 'https://schema.org/InStock',
                        'url'           => $permalink,
                    );
                }
            }

            if ( ! empty( $prices ) ) {
                $service_schema['offers'] = array(
                    '@type'         => 'AggregateOffer',
                    'lowPrice'      => min( $prices ),
                    'highPrice'     => max( $prices ),
                    'priceCurrency' => 'BDT',
                    'offerCount'    => count( $offers ),
                    'offers'        => $offers,
                );
            }
        }

        // 3. FAQ Schema
        $faq_schema = null;
        if ( ! empty( $faqs ) && is_array( $faqs ) ) {
            $faq_schema = array(
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                'mainEntity' => array(),
            );
            foreach ( $faqs as $item ) {
                if ( ! empty( $item['q'] ) && ! empty( $item['a'] ) ) {
                    $faq_schema['mainEntity'][] = array(
                        '@type'          => 'Question',
                        'name'           => $item['q'],
                        'acceptedAnswer' => array(
                            '@type' => 'Answer',
                            'text'  => $item['a'],
                        ),
                    );
                }
            }
            if ( empty( $faq_schema['mainEntity'] ) ) {
                $faq_schema = null;
            }
        }

        // Output JSON-LD Schemas
        echo "\n<!-- Travel-Z Visa Schema Markup -->\n";
        echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb_schema ) . "</script>\n";
        echo '<script type="application/ld+json">' . wp_json_encode( $service_schema ) . "</script>\n";
        if ( $faq_schema ) {
            echo '<script type="application/ld+json">' . wp_json_encode( $faq_schema ) . "</script>\n";
        }
        echo "<!-- /Travel-Z Visa Schema Markup -->\n\n";

        // Fallback OpenGraph Tags (if Rank Math / Yoast isn't handling)
        if ( ! defined( 'RANK_MATH_VERSION' ) && ! defined( 'WPSEO_VERSION' ) ) {
            echo '<meta name="description" content="' . esc_attr( $meta_desc ) . '" />' . "\n";
            echo '<meta property="og:title" content="' . esc_attr( $title . ' Visa Assistance - TravelZ' ) . '" />' . "\n";
            echo '<meta property="og:description" content="' . esc_attr( $meta_desc ) . '" />' . "\n";
            echo '<meta property="og:type" content="article" />' . "\n";
            echo '<meta property="og:url" content="' . esc_url( $permalink ) . '" />' . "\n";
            if ( $flag_url ) {
                echo '<meta property="og:image" content="' . esc_url( $flag_url ) . '" />' . "\n";
            }
        }
    }
}

TravelZ_Visa_SEO::init();
