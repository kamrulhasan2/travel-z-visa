<?php
/**
 * Sample Data Seeder for Travel-Z Visas
 *
 * @package TravelZ_Visa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TravelZ_Visa_Sample_Data {

    /**
     * Option key to track seeding.
     */
    const SEEDED_OPTION = 'travelz_visa_seeded_v1';

    /**
     * Run seeding if not already run.
     */
    public static function maybe_seed() {
        if ( ! get_option( self::SEEDED_OPTION ) ) {
            self::seed_all();
            update_option( self::SEEDED_OPTION, 1 );
        }
    }

    /**
     * Seed all default countries and visas.
     *
     * @param bool $force
     */
    public static function seed_all( $force = false ) {
        $countries = self::get_countries_data();

        foreach ( $countries as $c ) {
            $existing = get_page_by_path( $c['slug'], OBJECT, TravelZ_Visa_Post_Type::POST_TYPE );

            if ( $existing && ! $force ) {
                continue;
            }

            $post_id = 0;
            if ( $existing && $force ) {
                $post_id = $existing->ID;
                wp_update_post( array(
                    'ID'           => $post_id,
                    'post_title'   => $c['title'],
                    'post_content' => $c['content'],
                    'post_status'  => 'publish',
                ) );
            } else {
                $post_id = wp_insert_post( array(
                    'post_title'   => $c['title'],
                    'post_name'    => $c['slug'],
                    'post_type'    => TravelZ_Visa_Post_Type::POST_TYPE,
                    'post_status'  => 'publish',
                    'post_content' => $c['content'],
                ) );
            }

            if ( ! is_wp_error( $post_id ) && $post_id > 0 ) {
                // Set Region taxonomy
                if ( ! empty( $c['region'] ) ) {
                    wp_set_object_terms( $post_id, $c['region'], TravelZ_Visa_Post_Type::TAXONOMY, false );
                }

                // Set Meta Fields
                update_post_meta( $post_id, '_travelz_visa_type', $c['type'] );
                update_post_meta( $post_id, '_travelz_visa_duration', $c['duration'] );
                update_post_meta( $post_id, '_travelz_visa_processing_time', $c['processing_time'] );
                update_post_meta( $post_id, '_travelz_visa_fees', $c['fees'] );
                update_post_meta( $post_id, '_travelz_visa_pdf_url', $c['pdf_url'] );
                update_post_meta( $post_id, '_travelz_visa_viewer_type', 'flipbook' );
                update_post_meta( $post_id, '_travelz_visa_enable_download', '1' );
                update_post_meta( $post_id, '_travelz_visa_download_text', __( 'Download Requirement PDF', 'travel-z-visa' ) );
                update_post_meta( $post_id, '_travelz_visa_flag_url', $c['flag_url'] );
                update_post_meta( $post_id, '_travelz_visa_checklist', $c['checklist'] );
                update_post_meta( $post_id, '_travelz_visa_faqs', $c['faqs'] );
            }
        }
    }

    /**
     * Get array of all countries and details.
     *
     * @return array
     */
    public static function get_countries_data() {
        $flag_base = 'https://flagcdn.com/w80/';

        return array(
            array(
                'title'           => 'China',
                'slug'            => 'china',
                'region'          => 'Asia',
                'flag_url'        => $flag_base . 'cn.png',
                'type'            => 'Tourist Sticker visa',
                'duration'        => '3 months single Entry',
                'processing_time' => '10-15 working days (Approx.)',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Complete visa processing and expert assistance for China Tourist and Business Visas from Bangladesh.',
                'fees'            => array(
                    array( 'label' => '1st time applicant', 'amount' => '9500 BDT', 'note' => 'Single entry' ),
                    array( 'label' => '2nd time applicant', 'amount' => '9000 BDT', 'note' => 'Single entry' ),
                    array( 'label' => 'Double entry', 'amount' => '10900 BDT', 'note' => 'Valid for 6 months' ),
                    array( 'label' => '1 year multiple', 'amount' => '14900 BDT', 'note' => 'Subject to embassy approval' ),
                    array( 'label' => '2 years multiple', 'amount' => '17900 BDT', 'note' => 'Frequent travelers' ),
                ),
                'checklist'       => array(
                    'general'    => "Original Passport valid for at least 6 months with minimum 2 blank pages\n2 copies of recent color photographs (33mm x 48mm, white background, no glasses)\nBank Statement for last 6 months (Minimum closing balance: 2,00,000 BDT)\nBank Solvency Certificate\nNational ID Card (NID) copy or Birth Certificate copy",
                    'employed'   => "No Objection Certificate (NOC) on company official letterhead\nOfficial Visiting Card & Employee ID Card copy\nSalary Certificate / Pay slips for last 3 months\nGovernment Order (GO) in case of Government Service",
                    'business'   => "Valid Trade License (renewed copy translated into English and notarized)\nCompany Memorandum of Article & Certificate of Incorporation (if limited company)\nCompany official letterhead pad & Visiting Card\nCompany Bank Statement for last 6 months with Solvency",
                    'student'    => "Student ID card copy\nLeave approval letter / Recommendation from Institution\nParents' Financial Documents & Sponsorship declaration",
                    'additional' => "Marriage Certificate copy (if spouse is traveling)\nAir ticket booking confirmation\nHotel booking reservation",
                ),
                'faqs'            => array(
                    array( 'q' => 'How long does it take to get a China visa?', 'a' => 'Standard processing time is approximately 10 to 15 working days after submitting documents to the China Visa Application Service Center in Dhaka.' ),
                    array( 'q' => 'Is an in-person biometric appointment required?', 'a' => 'Yes, applicants between 14 and 70 years old usually require biometric fingerprint submission at the Visa Center in Dhaka unless previously exempt.' ),
                    array( 'q' => 'What is the minimum bank balance required for China?', 'a' => 'We recommend maintaining a minimum closing balance of BDT 200,000 to 300,000 per applicant for a smooth approval.' ),
                ),
            ),
            array(
                'title'           => 'Australia',
                'slug'            => 'australia',
                'region'          => 'Oceania',
                'flag_url'        => $flag_base . 'au.png',
                'type'            => 'Visitor Visa (Subclass 600)',
                'duration'        => '3 - 12 Months',
                'processing_time' => '20-30 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Expert online processing for Australian Subclass 600 Tourist & Business Visas.',
                'fees'            => array(
                    array( 'label' => 'Standard Visitor Visa', 'amount' => '22500 BDT', 'note' => 'Embassy fee & service' ),
                    array( 'label' => 'Business Stream', 'amount' => '24500 BDT', 'note' => 'Multiple entry' ),
                ),
                'checklist'       => array(
                    'general'    => "Color scanned copy of all Passport pages\nRecent passport size photograph (35mm x 45mm, white background)\nBank Statement for last 6 months with healthy balance\nBank Solvency Certificate\nAsset evaluation & property documents",
                    'employed'   => "NOC from employer mentioning leave duration & return guarantee\nVisiting Card & Office ID Card\nSalary Certificate & Salary Bank Account",
                    'business'   => "Trade License renewed copy (translated & notarized)\nTIN Certificate & Income Tax Return Certificate (last 3 years)\nCompany Pad & Visiting Card",
                    'student'    => "Student ID Card & Leave letter from educational institution\nParents sponsorship documents",
                    'additional' => "Day-by-day travel plan / itinerary\nProof of ties to Bangladesh",
                ),
                'faqs'            => array(
                    array( 'q' => 'Is Australia visa stickerless (e-Visa)?', 'a' => 'Yes, Australia issues electronic visitor visas (e-Visa / visa grant notification) linked directly to your passport number.' ),
                ),
            ),
            array(
                'title'           => 'Canada',
                'slug'            => 'canada',
                'region'          => 'North America',
                'flag_url'        => $flag_base . 'ca.png',
                'type'            => 'Temporary Resident Visa (V-1)',
                'duration'        => 'Up to Passport Validity (Multiple)',
                'processing_time' => '30-45 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Professional application support for Canada Tourist, Visitor, and Business visas from Bangladesh.',
                'fees'            => array(
                    array( 'label' => 'Tourist / Visitor Visa', 'amount' => '21000 BDT', 'note' => 'Includes Biometric fee' ),
                    array( 'label' => 'Family Package Support', 'amount' => '38000 BDT', 'note' => 'Per family' ),
                ),
                'checklist'       => array(
                    'general'    => "Current & old passports\nTwo photos with specifications (35mm x 45mm, face 80%)\nFinancial Statement (6 months) & Solvency\nProperty Valuation & Asset Proof",
                    'employed'   => "NOC on official pad\nSalary slip & visiting card",
                    'business'   => "Trade license, TIN, Tax returns, Company profile",
                    'student'    => "Student ID, bonafide certificate, leave approval",
                    'additional' => "Cover letter explaining purpose of trip & ties to Bangladesh",
                ),
                'faqs'            => array(
                    array( 'q' => 'Do I need to visit VFS Canada in Dhaka?', 'a' => 'Yes, applicants must submit biometric fingerprints and facial photo at VFS Canada Dhaka after submitting the online application.' ),
                ),
            ),
            array(
                'title'           => 'Japan',
                'slug'            => 'japan',
                'region'          => 'Asia',
                'flag_url'        => $flag_base . 'jp.png',
                'type'            => 'Short-Term Tourist / Business Visa',
                'duration'        => '15 - 90 Days',
                'processing_time' => '7-10 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Hassle-free Japan tourist visa processing with itinerary preparation and file organization.',
                'fees'            => array(
                    array( 'label' => 'Single Entry Tourist', 'amount' => '6500 BDT', 'note' => 'Service & processing' ),
                    array( 'label' => 'Multiple Entry (Frequent Travelers)', 'amount' => '9500 BDT', 'note' => 'Subject to embassy terms' ),
                ),
                'checklist'       => array(
                    'general'    => "Valid passport\nJapan visa application form with photo (45mm x 45mm)\nBank statement for last 6 months & Solvency\nIncome Tax Return certificate",
                    'employed'   => "Employment certificate / NOC\nVisiting card & office ID",
                    'business'   => "Trade license & company bank statement",
                    'student'    => "Student ID card & institutional permission",
                    'additional' => "Daily schedule / travel itinerary form (Schedule of Stay)",
                ),
                'faqs'            => array(
                    array( 'q' => 'Is embassy fee free for Japanese visa for Bangladeshi citizens?', 'a' => 'Yes, the Embassy of Japan does not charge an embassy visa fee for Bangladeshi citizens; only agency processing & VFS service fees apply.' ),
                ),
            ),
            array(
                'title'           => 'Malaysia',
                'slug'            => 'malaysia',
                'region'          => 'Asia',
                'flag_url'        => $flag_base . 'my.png',
                'type'            => 'e-Visa / Sticker Visa',
                'duration'        => '3 Months Single / Multiple',
                'processing_time' => '5-7 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Quick Malaysia e-Visa and sticker visa processing for leisure and business trips.',
                'fees'            => array(
                    array( 'label' => 'e-Visa Tourist', 'amount' => '6000 BDT', 'note' => 'Fast online approval' ),
                    array( 'label' => 'Sticker Visa', 'amount' => '7500 BDT', 'note' => 'Passport submission' ),
                ),
                'checklist'       => array(
                    'general'    => "Passport valid for at least 6 months\nRecent 35mm x 50mm studio photo with white background\nBank statement (last 6 months, min 1,00,000 BDT) & solvency",
                    'employed'   => "NOC letter from company\nVisiting card",
                    'business'   => "Trade license copy (English notarized)",
                    'student'    => "Student ID card & parent consent",
                    'additional' => "Confirmed return flight & hotel voucher",
                ),
                'faqs'            => array(
                    array( 'q' => 'How many days does Malaysia e-Visa take?', 'a' => 'Malaysia e-Visa is usually issued within 3 to 5 business days.' ),
                ),
            ),
            array(
                'title'           => 'Singapore',
                'slug'            => 'singapore',
                'region'          => 'Asia',
                'flag_url'        => $flag_base . 'sg.png',
                'type'            => 'e-Visa (Entry Visa)',
                'duration'        => '1 - 2 Months',
                'processing_time' => '5-8 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Official Singapore eVisa processing with Letter of Introduction (LOI) support.',
                'fees'            => array(
                    array( 'label' => 'Standard eVisa with LOI', 'amount' => '7500 BDT', 'note' => 'All inclusive' ),
                    array( 'label' => 'Multiple Entry eVisa', 'amount' => '9000 BDT', 'note' => 'Eligible passports' ),
                ),
                'checklist'       => array(
                    'general'    => "Passport copy & original passport\nPhoto (35mm x 45mm, matte finish, white background)\nBank statement (last 6 months) & solvency",
                    'employed'   => "Company NOC & visiting card",
                    'business'   => "Trade license & company pad",
                    'student'    => "Student ID & bonafide certificate",
                    'additional' => "Singapore contact / sponsor / LOI (Arranged by Travel Z)",
                ),
                'faqs'            => array(
                    array( 'q' => 'Do I need an LOI (Letter of Introduction) for Singapore?', 'a' => 'Yes, Bangladeshi passport holders require an LOI, which Travel-Z arranges as part of our visa service.' ),
                ),
            ),
            array(
                'title'           => 'Thailand',
                'slug'            => 'thailand',
                'region'          => 'Asia',
                'flag_url'        => $flag_base . 'th.png',
                'type'            => 'Tourist Sticker Visa (TR)',
                'duration'        => '3 Months Single Entry',
                'processing_time' => '6-8 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Fast and reliable Thailand tourist sticker visa processing through authorized centers in Dhaka.',
                'fees'            => array(
                    array( 'label' => 'Single Entry Tourist', 'amount' => '5800 BDT', 'note' => 'VFS & Embassy fee included' ),
                    array( 'label' => 'Multiple Entry (6 Months)', 'amount' => '19500 BDT', 'note' => 'Frequent travelers' ),
                ),
                'checklist'       => array(
                    'general'    => "Original passport valid for 6 months\nTwo 35mm x 45mm photographs (white background, taken within 6 months)\nBank statement (last 6 months, min 80,000 BDT per person or 150,000 BDT per family) & solvency",
                    'employed'   => "NOC on official letterhead\nVisiting card & ID card",
                    'business'   => "Trade license (English translated & notarized)\nCompany letterhead pad",
                    'student'    => "Student ID card & institution leave letter",
                    'additional' => "Confirmed air ticket & hotel reservation",
                ),
                'faqs'            => array(
                    array( 'q' => 'Can I get Thailand visa on arrival?', 'a' => 'Bangladeshi passport holders are generally not eligible for Visa on Arrival and must apply for a sticker visa before traveling.' ),
                ),
            ),
            array(
                'title'           => 'Dubai (UAE)',
                'slug'            => 'dubai',
                'region'          => 'Middle East',
                'flag_url'        => $flag_base . 'ae.png',
                'type'            => 'UAE Tourist E-Visa',
                'duration'        => '30 / 60 Days',
                'processing_time' => '3-5 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Instant UAE / Dubai Tourist & Transit E-Visa processing with guaranteed approval support.',
                'fees'            => array(
                    array( 'label' => '30 Days Single Entry', 'amount' => '13500 BDT', 'note' => 'Includes insurance' ),
                    array( 'label' => '60 Days Single Entry', 'amount' => '18500 BDT', 'note' => 'Includes insurance' ),
                    array( 'label' => '30 Days Multiple Entry', 'amount' => '22500 BDT', 'note' => 'Business travelers' ),
                ),
                'checklist'       => array(
                    'general'    => "Color scanned copy of passport bio-page (valid 6 months)\nPassport size photograph with white background\nNational ID card copy",
                    'employed'   => "Visiting card or office ID",
                    'business'   => "Trade license copy",
                    'student'    => "Student ID card",
                    'additional' => "Return air ticket booking",
                ),
                'faqs'            => array(
                    array( 'q' => 'Is physical passport required for Dubai visa?', 'a' => 'No, Dubai visa is an electronic visa (PDF). You only need to send clear scanned copies of your passport and photo.' ),
                ),
            ),
            array(
                'title'           => 'United Kingdom (London)',
                'slug'            => 'london',
                'region'          => 'Europe',
                'flag_url'        => $flag_base . 'gb.png',
                'type'            => 'Standard Visitor Visa',
                'duration'        => '6 Months / 2 Years / 5 Years',
                'processing_time' => '15-20 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Comprehensive UK Standard Visitor visa documentation, online filing, and biometric appointment scheduling.',
                'fees'            => array(
                    array( 'label' => '6 Months Standard Visitor', 'amount' => '24500 BDT', 'note' => 'Embassy & processing' ),
                    array( 'label' => '2 Years Long Term Visitor', 'amount' => '68000 BDT', 'note' => 'Frequent travelers' ),
                ),
                'checklist'       => array(
                    'general'    => "All current and previous passports\nDetailed bank statement (last 6 months) with verified transactions\nBank Solvency Certificate\nTax returns & TIN certificate\nProperty / Asset documents",
                    'employed'   => "NOC letter specifying salary, leave dates & position\nSalary slips for last 6 months\nVisiting card & work ID",
                    'business'   => "Trade license, incorporation certificates, company bank statements\nAudit report (if available)",
                    'student'    => "School/College bonafide letter & sponsor documents",
                    'additional' => "Comprehensive cover letter with travel itinerary",
                ),
                'faqs'            => array(
                    array( 'q' => 'Where is UK biometrics submitted in Dhaka?', 'a' => 'Biometrics are submitted at the VFS Global UK Visa Application Centre located in Delta Life Tower, Gulshan-2, Dhaka.' ),
                ),
            ),
            array(
                'title'           => 'United States (USA)',
                'slug'            => 'usa',
                'region'          => 'North America',
                'flag_url'        => $flag_base . 'us.png',
                'type'            => 'B1/B2 Tourist & Business Visa',
                'duration'        => 'Up to 5 Years (Multiple)',
                'processing_time' => 'Subject to embassy interview appointment',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Professional DS-160 filing, MRV fee payment, expedited appointment scheduling, and mock interview guidance.',
                'fees'            => array(
                    array( 'label' => 'B1/B2 Visa File & DS-160 Support', 'amount' => '27500 BDT', 'note' => 'Includes MRV Embassy fee' ),
                    array( 'label' => 'Family Visa Assistance', 'amount' => '48000 BDT', 'note' => 'Up to 3 members' ),
                ),
                'checklist'       => array(
                    'general'    => "Valid passport & previous passports\nDS-160 confirmation barcode page\nAppointment confirmation page\n2x2 inch (51mm x 51mm) photo\nFinancial capability proof & Bank statements",
                    'employed'   => "NOC letter from company & salary proof",
                    'business'   => "Trade license, Tax certificates, Company profile",
                    'student'    => "Current student proof & parent financial documents",
                    'additional' => "All original documents to be presented during the in-person embassy interview",
                ),
                'faqs'            => array(
                    array( 'q' => 'Is in-person interview required for USA visa?', 'a' => 'Yes, all first-time applicants aged 14 to 79 must attend an in-person interview at the US Embassy in Dhaka.' ),
                ),
            ),
            array(
                'title'           => 'Schengen (Europe)',
                'slug'            => 'schengen',
                'region'          => 'Europe',
                'flag_url'        => $flag_base . 'eu.png',
                'type'            => 'Short-Stay Tourist Visa (Type C)',
                'duration'        => 'Up to 90 Days',
                'processing_time' => '15-25 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Full visa assistance for 27 European Schengen member states (France, Germany, Italy, Spain, Switzerland, etc.).',
                'fees'            => array(
                    array( 'label' => 'Standard Schengen Tourist Visa', 'amount' => '18500 BDT', 'note' => 'Embassy fee & file prep' ),
                    array( 'label' => 'Business Visit Stream', 'amount' => '21500 BDT', 'note' => 'Invitation review included' ),
                ),
                'checklist'       => array(
                    'general'    => "Passport valid for 3 months beyond intended departure from Schengen\nTwo Schengen-standard photos (35mm x 45mm)\nOverseas Travel Medical Insurance (minimum coverage €30,000)\nBank statement (last 6 months) & Solvency",
                    'employed'   => "NOC from employer & salary pay slips",
                    'business'   => "Trade license, TIN, Tax returns & Company pad",
                    'student'    => "Student ID & school leave letter",
                    'additional' => "Confirmed flight reservation & hotel voucher covering entire Schengen stay",
                ),
                'faqs'            => array(
                    array( 'q' => 'Which Schengen embassy should I apply to?', 'a' => 'You must apply at the embassy of your main destination (where you will spend the most nights) or the country of first entry if staying equal days.' ),
                ),
            ),
            array(
                'title'           => 'Turkey',
                'slug'            => 'turkey',
                'region'          => 'Europe',
                'flag_url'        => $flag_base . 'tr.png',
                'type'            => 'Sticker Visa / E-Visa',
                'duration'        => '3 Months',
                'processing_time' => '10-14 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Turkey sticker visa processing through Gateway Globe Dhaka and E-Visa for valid US/UK/Schengen visa holders.',
                'fees'            => array(
                    array( 'label' => 'Sticker Visa Support', 'amount' => '14500 BDT', 'note' => 'Gateway & Embassy fee included' ),
                    array( 'label' => 'E-Visa (With US/UK/Schengen)', 'amount' => '8500 BDT', 'note' => 'Instant approval' ),
                ),
                'checklist'       => array(
                    'general'    => "Passport with at least 6 months validity\n2 biometric photos (50mm x 50mm, white background)\nBank statement for last 6 months with healthy balance\nTravel insurance",
                    'employed'   => "NOC & Visiting Card",
                    'business'   => "Trade license (English translated & notarized)",
                    'student'    => "Student ID card",
                    'additional' => "Flight reservation and hotel booking",
                ),
                'faqs'            => array(
                    array( 'q' => 'Who is eligible for Turkey E-Visa from Bangladesh?', 'a' => 'Bangladeshi passport holders who hold a valid existing visa or residence permit from the USA, UK, Ireland, or any Schengen country can obtain a Turkey E-Visa.' ),
                ),
            ),
            array(
                'title'           => 'Indonesia',
                'slug'            => 'indonesia',
                'region'          => 'Asia',
                'flag_url'        => $flag_base . 'id.png',
                'type'            => 'Tourist E-Visa (B211A / 211A)',
                'duration'        => '60 Days Single Entry',
                'processing_time' => '5-7 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Fast e-Visa processing for Bali & Jakarta holidays.',
                'fees'            => array(
                    array( 'label' => '60 Days Tourist E-Visa', 'amount' => '11500 BDT', 'note' => 'No passport submission needed' ),
                ),
                'checklist'       => array(
                    'general'    => "Passport scanned copy (valid minimum 6 months)\nRecent photo\nBank statement (last 3 months with min $2,000 balance)",
                    'employed'   => "Visiting card",
                    'business'   => "Trade license",
                    'student'    => "Student ID",
                    'additional' => "Confirmed return ticket",
                ),
                'faqs'            => array(
                    array( 'q' => 'Can Indonesia e-Visa be extended?', 'a' => 'Yes, 60-day tourist e-Visas can be extended within Indonesia up to 2 times (60 days each).' ),
                ),
            ),
            array(
                'title'           => 'India',
                'slug'            => 'india',
                'region'          => 'Asia',
                'flag_url'        => $flag_base . 'in.png',
                'type'            => 'Tourist / Medical Sticker Visa',
                'duration'        => '1 - 5 Years Multiple',
                'processing_time' => '7-12 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Complete online form filling, IVAC token slot booking, and file submission assistance.',
                'fees'            => array(
                    array( 'label' => 'Tourist Visa Processing', 'amount' => '1800 BDT', 'note' => 'IVAC fee & file arrangement' ),
                    array( 'label' => 'Medical Visa Processing', 'amount' => '2200 BDT', 'note' => 'Includes hospital letter verification' ),
                ),
                'checklist'       => array(
                    'general'    => "Original passport & all previous passports\nRecent 2x2 inch photograph\nNational ID card copy\nUtility bill (Electricity/Gas) copy matching present address\nBank statement (minimum 20,000 BDT) or Dollar endorsement ($150)",
                    'employed'   => "NOC letter from company",
                    'business'   => "Trade license copy",
                    'student'    => "Student ID copy",
                    'additional' => "Medical invitation from Indian hospital (for Medical visa)",
                ),
                'faqs'            => array(
                    array( 'q' => 'Is utility bill mandatory for Indian visa?', 'a' => 'Yes, a recent electricity, gas, or landline telephone bill matching your present address is strictly required by IVAC.' ),
                ),
            ),
            array(
                'title'           => 'Vietnam',
                'slug'            => 'vietnam',
                'region'          => 'Asia',
                'flag_url'        => $flag_base . 'vn.png',
                'type'            => 'E-Visa / Approval Letter',
                'duration'        => '30 / 90 Days',
                'processing_time' => '4-6 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Online Vietnam e-Visa assistance for single and multiple entries.',
                'fees'            => array(
                    array( 'label' => '30 Days Single Entry E-Visa', 'amount' => '6500 BDT', 'note' => 'Fast electronic approval' ),
                    array( 'label' => '90 Days Multiple Entry', 'amount' => '11500 BDT', 'note' => 'For multiple visits' ),
                ),
                'checklist'       => array(
                    'general'    => "Scanned passport bio-page (valid 6 months)\nDigital portrait photo (4x6cm, white background)\nFlight itinerary",
                    'employed'   => "Visiting card",
                    'business'   => "Trade license",
                    'student'    => "Student card",
                    'additional' => "Entry & exit port names in Vietnam",
                ),
                'faqs'            => array(
                    array( 'q' => 'How long does Vietnam e-Visa take?', 'a' => 'Vietnam e-Visa typically takes 4 to 6 business days for approval.' ),
                ),
            ),
            array(
                'title'           => 'Egypt',
                'slug'            => 'egypt',
                'region'          => 'Africa',
                'flag_url'        => $flag_base . 'eg.png',
                'type'            => 'Tourist Sticker Visa',
                'duration'        => '30 Days Single Entry',
                'processing_time' => '12-15 working days',
                'pdf_url'         => 'https://tech.travelzbd.com/wp-content/uploads/2025/02/China.pdf',
                'content'         => 'Explore the Pyramids and ancient Nile with our expert Egypt tourist visa file processing.',
                'fees'            => array(
                    array( 'label' => 'Tourist Sticker Visa', 'amount' => '9800 BDT', 'note' => 'Embassy fee & processing' ),
                ),
                'checklist'       => array(
                    'general'    => "Valid passport for at least 6 months\nTwo 40mm x 60mm photos with white background\nBank statement (last 6 months, min 2,00,000 BDT) & solvency",
                    'employed'   => "NOC letter from employer",
                    'business'   => "Trade license notarized copy",
                    'student'    => "Student ID card",
                    'additional' => "Confirmed return ticket and hotel accommodation",
                ),
                'faqs'            => array(
                    array( 'q' => 'Do I need a security clearance for Egypt visa?', 'a' => 'The Embassy of Egypt performs an internal security clearance, which is why standard processing takes 12 to 15 working days.' ),
                ),
            ),
        );
    }
}
