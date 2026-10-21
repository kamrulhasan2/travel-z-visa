=== Travel-Z Visa Assistance ===
Contributors: travelzbd, eguider
Tags: visa, travel, visa assistance, flipbook, document viewer, travel agency, bangladesh
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A modern, high-converting, SEO-optimized Visa Assistance management plugin with interactive PDF flipbook, structured document checklist, multi-tier fee repeater, and custom admin dashboard.

== Description ==

**Travel-Z Visa Assistance** is a tailor-made WordPress plugin built specifically for travel agencies, visa consultants, and tour operators. It transforms static or cumbersome page-builder visa listings into a unified, dynamic system manageable directly from the WordPress Admin Dashboard.

= Key Features =
* **Full WordPress Admin Control:** Easily add, edit, or remove countries, visa validity, processing time, multi-tier fees, and requirement PDFs without writing any code.
* **Interactive Flipbook / PDF Viewer:** Clean, mobile-friendly document reader with 3D page flip effects, page navigation, zoom in/out, fullscreen toggle, and sound synthesis.
* **Multi-Tier Fee Structure:** Repeater table in admin to add 1st time, 2nd time, double entry, 1-year, and 2-year fee options.
* **Category Document Checklist (SEO Powerhouse):** Tabbed checklist for Job Holders, Business Persons, Students, and General documents that Google can crawl and index.
* **SEO & Google Schema Structured Data:** Automatic JSON-LD schema for Service, TravelAgency, Product/Offer, Breadcrumbs, and FAQPage.
* **Direct Actions & Lead Generation:** One-click redirection to your inquiry/queries page, WhatsApp chat with prefilled country messages, and helpline call buttons.
* **Country Switcher & Live Search:** Instant autocomplete search bar with country flags on both individual visa pages and the main landing hub.
* **Shortcodes:** Embed the full hub, compact search bar, or individual country cards anywhere on your site.
* **One-Click Demo Data:** Pre-populate your site with verified visa records for China, Canada, Australia, Japan, Schengen countries, and more.

== Installation ==

1. Upload the `travel-z-visa` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to **Settings** > **Permalinks** and click **Save Changes** to refresh rewrite rules.
4. Navigate to **Travel-Z Visa** in the admin dashboard to manage visas and configure settings.

== Shortcodes ==

* `[travelz_visa_hub]` - Embeds the full Visa Assistance search, filter tabs, and country catalog.
* `[travelz_visa_search placeholder="Search..." button_text="Find Visa"]` - Embeds a compact country select and instant live search bar.
* `[travelz_visa_card id="123"]` - Embeds an overview card for a specific visa post ID.

== Frequently Asked Questions ==

= How do I fix 404 errors on country visa pages? =
Navigate to **Settings** > **Permalinks** in your WordPress admin panel and click **Save Changes**. This flushes your site's rewrite rules.

= Can I customize the currency symbol? =
Yes! Navigate to **Travel-Z Visa** > **Settings** and update the Currency Symbol field (e.g. BDT, USD, EUR, etc.).

= Can I override plugin templates in my theme? =
Yes. Copy any template file from `travel-z-visa/templates/` into your active theme directory at `your-theme/travel-z-visa/`.

== Screenshots ==

1. Visa Catalog Hub with Live Search and Continent Filters.
2. Single Country Visa Page with Interactive Flipbook Document Viewer.
3. Tabbed Document Checklist categorized by profession for high SEO indexing.
4. WordPress Admin Visa Post Editor with Repeater Fee Table.
5. Plugin Settings Panel for branding, helpline numbers, and hero banner.

== Changelog ==

= 1.0.0 =
* Initial release of Travel-Z Visa Assistance plugin.
