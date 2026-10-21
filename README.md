# Travel-Z Visa Assistance

<p align="center">
  <img src="https://img.shields.io/badge/WordPress-5.8%20--%206.7+-21759b?style=for-the-badge&logo=wordpress&logoColor=white" alt="WordPress Version" />
  <img src="https://img.shields.io/badge/PHP-7.4%20--%208.3+-777bb4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version" />
  <img src="https://img.shields.io/badge/Schema.org-JSON--LD%20Valid-4285F4?style=for-the-badge&logo=google&logoColor=white" alt="Schema.org" />
  <img src="https://img.shields.io/badge/License-GPLv2%20or%20Later-blue?style=for-the-badge" alt="License" />
  <img src="https://img.shields.io/badge/Release-v1.0.0-success?style=for-the-badge" alt="Version 1.0.0" />
</p>

---

## 📌 Executive Summary

**Travel-Z Visa Assistance** is an enterprise-grade, high-converting WordPress plugin architected specifically for travel agencies, visa consultants, and outbound tour operators. 

Traditional WordPress visa pages are often fragmented across cumbersome page builder layouts (Elementor, Divi, WPBakery) that are difficult for non-technical staff to update, slow to load, and ineffective for search engine indexing. **Travel-Z Visa Assistance** centralizes visa management into an intuitive, native WordPress administrative interface—complete with an interactive 3D PDF flipbook reader, segmented profession-based document checklists, dynamic multi-tier fee structures, instant country autocomplete search, and automated Schema.org SEO rich snippets.

---

## 📑 Table of Contents

- [Key Features](#-key-features)
- [System Architecture](#-system-architecture)
- [Directory Structure](#-directory-structure)
- [System Requirements](#-system-requirements)
- [Installation & Quick Start](#-installation--quick-start)
- [Admin Management Guide](#-admin-management-guide)
  - [Global Plugin Settings](#1-global-plugin-settings)
  - [Managing Country Visa Records](#2-managing-country-visa-records)
  - [One-Click Demo Data Importer](#3-one-click-demo-data-importer)
- [Shortcode Documentation](#-shortcode-documentation)
- [Frontend User Experience](#-frontend-user-experience)
- [SEO & Structured Data (JSON-LD)](#-seo--structured-data-json-ld)
- [Theme Overrides & Developer Customization](#-theme-overrides--developer-customization)
  - [CSS Variable Customization](#css-custom-properties)
  - [Template Override Hierarchy](#template-override-hierarchy)
  - [Action Hooks & Filters](#action-hooks--filters)
- [Security & Performance](#-security--performance)
- [Troubleshooting & FAQs](#-troubleshooting--faqs)
- [Changelog](#-changelog)
- [License & Support](#-license--support)

---

## ✨ Key Features

### 📖 1. Interactive Document Flipbook & PDF Reader
- **Modern Canvas Rendering:** Powered by HTML5 Canvas and PDF rendering technology with realistic page-flip physics.
- **Responsive Stage:** Seamless experience across desktop, tablet, and mobile displays.
- **Intuitive Toolbar:** Previous/Next page buttons, live page counter (`X / Y`), Zoom In/Out controls, and full-screen toggle.
- **Audio Experience:** Built-in synthesized page turn sound effect (can be toggled in admin settings).
- **Download Gate:** Direct download button with custom CTA copy and permissions toggle.
- **Smart Fallback:** Native embed/iframe fallback for instant rendering if canvas is unsupported.

### 📋 2. Structured Document Checklists (SEO Optimized)
- **Categorized Tabs:** Organizes documents by applicant profession:
  - 💼 **Job Holders / Employed** (NOC, salary slips, visiting cards, GOs)
  - 🏢 **Business Persons** (Trade license, MOA, incorporation docs, company statements)
  - 🎓 **Students** (Student ID, institutional leave approval, sponsorship declarations)
  - 📑 **General Documents** (Passport, photographs, personal bank statements, solvency certificates)
  - 📎 **Other / Supporting** (Marriage certificates, flight reservations, hotel bookings)
- **Crawlable Content:** Unlike flat PDF images, all checklist items are rendered as semantic HTML that Google and other search engines can read, index, and rank.

### 💰 3. Dynamic Multi-Tier Pricing Table
- **Repeater Matrix:** Add unlimited fee tiers (e.g., 1st-time applicant, 2nd-time renewal, double entry, 1-year multiple, 2-year multiple).
- **Customizable Columns:** Entry label, currency amount (BDT, USD, EUR, etc.), and explanatory notes (e.g., *"Subject to embassy approval"*).
- **No Hardcoding:** Fully editable per country directly inside the WordPress dashboard.

### 🔍 4. Live Search & Continent Filtering
- **Real-Time Country Switcher:** Instant autocomplete search bar with country flags and visa types.
- **Archive Catalog Filtering:** Filter visas dynamically by Continent/Region (Asia, Europe, Americas, Middle East, Africa, Oceania).
- **Quick Switcher Dropdown:** Embedded on individual visa detail pages to let users switch destinations effortlessly.

### ⚡ 5. Search Engine Optimization (SEO & Schema.org)
- **Automatic JSON-LD Schema:** Generates Google-compliant structured data for:
  - `BreadcrumbList` (Breadcrumb navigation paths)
  - `Service` & `TravelAgency` (Service offerings and provider details)
  - `Offer` (Pricing matrices and currency definitions)
  - `FAQPage` (Automated FAQ rich snippets in Google search results)
- **Social Graph Optimization:** Preconfigured Open Graph (`og:title`, `og:description`, `og:image`, `og:url`) meta tags.

### 🎯 6. High-Conversion Lead Generation
- **WhatsApp Integration:** Direct one-click chat button pre-configured with personalized country text (e.g., *"Hello Travel-Z, I am interested in China Visa Assistance"*).
- **Click-to-Call Hotline:** Prominent helpline buttons linked to official travel agency phone lines.
- **Query Redirection:** Direct CTA buttons steering visitors to the agency's custom inquiry or quote form.

### 🎨 7. Comprehensive Customizer & Styling Engine
- **Dynamic Color Palettes:** Set primary (`#0ebac5`) and accent (`#f26522`) brand colors from the admin; styles are injected dynamically into CSS variables.
- **Hero Banner Controls:** Configure background image, light/dark overlay tint, overlay opacity percentage, custom headline, subheadline, and search placeholder.
- **Configurable Permalink Slug:** Custom post type URL prefix can be changed (default: `/visa-assistance/`) to match site structure.

---

## 🏛 System Architecture

The plugin is designed following WordPress object-oriented coding standards and modular file separation:

```
travel-z-visa/
├── admin/                                # Backend administration
│   ├── css/                              # Admin stylesheets
│   │   └── admin-style.css
│   ├── js/                               # Admin scripts (repeaters, media pickers)
│   │   └── admin-script.js
│   ├── class-admin.php                   # Admin menus, asset enqueuing, custom columns
│   ├── class-meta-boxes.php              # Custom meta boxes (Overview, Pricing, PDF, Checklist, FAQs)
│   └── class-settings.php               # Global plugin configuration page
│
├── frontend/                             # Customer-facing display
│   ├── css/
│   │   ├── flipbook.css                  # Document reader & 3D stage styles
│   │   └── frontend-style.css            # Hub, single cards, checklist & responsive grids
│   ├── js/
│   │   ├── flipbook.js                   # Interactive PDF viewer & audio synthesis
│   │   └── frontend-script.js            # Live search, country dropdown & tab switching
│   └── class-frontend.php                # Template loader, asset enqueue & CSS variable injector
│
├── includes/                             # Core engine & data logic
│   ├── class-post-type.php               # CPT ('travelz_visa') & Taxonomy ('visa_region') registration
│   ├── class-sample-data.php             # Demo seeder for 10+ popular visa destinations
│   ├── class-seo.php                     # Schema.org JSON-LD & Open Graph generator
│   ├── class-shortcodes.php              # Shortcode definitions & markup renderers
│   └── class-travelz-visa.php            # Main singleton orchestrator
│
├── templates/                            # Template files (theme overridable)
│   ├── archive-visa.php                  # Visa Catalog & Hub template
│   ├── single-visa.php                   # Single Country Visa detail page
│   └── parts/                            # Modular component partials
│       ├── checklist.php                 # Tabbed profession document checklist
│       ├── country-switcher.php          # Dropdown country switcher
│       ├── faqs.php                      # Accordion FAQs component
│       ├── flipbook-viewer.php           # Document reader stage & controls
│       └── overview-card.php             # Visa specifications & pricing summary card
│
├── languages/                            # Internationalization (.pot / .po / .mo files)
├── readme.txt                            # WordPress.org repository specifications
├── README.md                             # Comprehensive developer & project documentation
└── travel-z-visa.php                     # Primary plugin bootstrap file
```

---

## 💻 System Requirements

| Requirement | Minimum | Recommended |
|:---|:---|:---|
| **WordPress** | 5.8 | 6.5+ / 6.7+ |
| **PHP Version** | 7.4 | 8.1 / 8.2 / 8.3 |
| **MySQL / MariaDB** | MySQL 5.7 / MariaDB 10.3 | MySQL 8.0 / MariaDB 10.6+ |
| **Web Server** | Apache / Nginx / LiteSpeed | Nginx with FastCGI Cache |
| **Browser Compatibility** | Chrome, Firefox, Safari, Edge | Modern Evergreen Browsers |

---

## 🚀 Installation & Quick Start

### Method 1: WordPress Admin Upload
1. Download the plugin archive `travel-z-visa.zip`.
2. In your WordPress dashboard, navigate to **Plugins** > **Add New** > **Upload Plugin**.
3. Choose the `.zip` file and click **Install Now**.
4. Click **Activate Plugin**.

### Method 2: Manual Installation via SFTP / SSH
1. Extract the `travel-z-visa.zip` file.
2. Upload the `travel-z-visa` folder to your server directory:
   ```bash
   /wp-content/plugins/
   ```
3. Navigate to **Plugins** > **Installed Plugins** in the WordPress admin panel.
4. Locate **Travel-Z Visa Assistance** and click **Activate**.

### Post-Activation Step (Mandatory)
Flush your permalinks to ensure the custom post type URLs resolve properly:
- Navigate to **Settings** > **Permalinks**.
- Click **Save Changes** without modifying any fields.

---

## ⚙️ Admin Management Guide

### 1. Global Plugin Settings
Navigate to **Travel-Z Visa** > **Settings** in the WordPress sidebar to customize global options:

- **Contact & Communication:**
  - **Direct Phone Helpline:** Phone number formatted for click-to-call (`tel:` links).
  - **WhatsApp Hotline:** Clean international number (e.g. `8801711258451`) for instant WhatsApp chat triggers.
  - **Inquiry / Booking Form URL:** Direct URL to your customized lead capture page.
  - **Corporate Address:** Physical office address displayed in schema and footers.
- **Branding & Visual Palette:**
  - **Primary Color:** Dominant brand color (default: `#0ebac5`).
  - **Accent Color:** High-contrast CTA color (default: `#f26522`).
- **Hero Banner Settings:**
  - Background image selector (via WP Media Library).
  - Overlay style (`light`, `dark`, or custom hex).
  - Overlay opacity slider (0% to 100%).
  - Hero badge text, title, and description.
- **Features & SEO:**
  - Enable/Disable Schema.org structured data.
  - Enable/Disable Flipbook page turn audio sound.
  - Permalink slug customization (default: `visa-assistance`).

---

### 2. Managing Country Visa Records
Navigate to **Travel-Z Visa** > **Add New Visa** to publish a new country profile:

#### A. Title & Excerpt
- Enter the Country Name in the title field (e.g., *China*, *Canada*, *United Kingdom*).
- Provide a summary description in the main editor.

#### B. Overview & Pricing Meta Box
- **Visa Type:** (e.g., *Tourist Sticker Visa*, *e-Visa*, *Business Visa*).
- **Validity / Duration:** (e.g., *3 Months Single Entry*, *5 Years Multiple*).
- **Processing Time:** (e.g., *10-15 Working Days (Approx.)*).
- **Pricing Repeater:** Add custom fee rows with Entry Type, Fee (e.g., `9,500 BDT`), and descriptive Notes.

#### C. Requirement Document & Flipbook Meta Box
- **PDF Document URL:** Upload or select the requirement PDF using the WordPress Media Library.
- **Enable Direct Download:** Checkbox to allow users to download the file directly.
- **Custom Button Label:** Configure CTA text (e.g., *Download China Visa Guide*).

#### D. Document Checklist (SEO Powerhouse)
Enter specific required documents (one per line) across 5 designated tabs:
1. **General Documents**
2. **Job Holders / Employed**
3. **Business Persons**
4. **Students**
5. **Additional / Supporting Documents**

#### E. Frequently Asked Questions (FAQ Schema)
Add collapsible Q&A pairs. These are automatically formatted into Google `FAQPage` JSON-LD schema for search engine rich results.

#### F. Country Flag & Region
- Upload or specify a flag URL in the right sidebar.
- Assign the country to a **Region / Continent** taxonomy (e.g., *Asia*, *Europe*, *Americas*).

---

### 3. One-Click Demo Data Importer
Need instant starter content?
1. Navigate to **Travel-Z Visa** > **Settings**.
2. Scroll to the bottom to the **Demo Data** section.
3. Click **Seed Sample Data**.
4. The plugin will immediately populate your site with verified visa records (including China, Canada, Australia, Japan, Schengen countries, etc.) complete with checklists, pricing tables, sample PDF links, and FAQs.

---

## 🏷 Shortcode Documentation

The plugin provides three flexible shortcodes to embed visa information anywhere on your website (compatible with Elementor, Gutenberg, Beaver Builder, Divi, or standard page templates).

| Shortcode | Attributes | Default | Description |
|:---|:---|:---|:---|
| `[travelz_visa_hub]` | *None* | *N/A* | Embeds the complete visa catalog with hero banner, live search, continent filter tabs, and responsive country grid. |
| `[travelz_visa_search]` | `placeholder`<br>`button_text` | `"Select or search a country..."`<br>`"Search Visa"` | Embeds a compact, autocomplete search bar with instant country dropdown navigation. |
| `[travelz_visa_card]` | `id` *(required)* | `0` | Embeds the standalone pricing and specification card for a specific visa post ID. |

### Shortcode Examples

#### Full Visa Hub:
```html
[travelz_visa_hub]
```

#### Customized Compact Search Bar:
```html
[travelz_visa_search placeholder="Search country (e.g. Canada, Japan)..." button_text="Find Visa"]
```

#### Individual Visa Overview Card:
```html
[travelz_visa_card id="142"]
```

---

## 🖥 Frontend User Experience

```
+--------------------------------------------------------------------------+
|  [Flag] CHINA VISA ASSISTANCE                                            |
|  Type: Tourist Sticker Visa | Processing Time: 10-15 Working Days        |
+------------------------------------+-------------------------------------+
|  DOCUMENT CHECKLIST (TABS)         |  OVERVIEW & FEE SCHEDULE            |
|  [General] [Employed] [Business]   |  ---------------------------------  |
|  * Valid Passport (6+ months)      |  * 1st Time:     9,500 BDT          |
|  * 2 Passport Photos (33x48mm)     |  * 2nd Time:     9,000 BDT          |
|  * 6 Months Bank Statement         |  * Double Entry: 10,900 BDT         |
|  * Solvency Certificate            |  * 1-Year Multi: 14,900 BDT         |
+------------------------------------+-------------------------------------+
|  INTERACTIVE REQUIREMENT FLIPBOOK / PDF READER                           |
|  [<< Prev] [ Page 1 of 4 ] [Next >>] [Zoom -] [Zoom +] [Fullscreen]     |
|  +--------------------------------------------------------------------+  |
|  |                                                                    |  |
|  |                    [Interactive Flip Canvas]                       |  |
|  |                                                                    |  |
|  +--------------------------------------------------------------------+  |
|  [ Download PDF Guide ]                                                  |
+--------------------------------------------------------------------------+
|  FREQUENTLY ASKED QUESTIONS (FAQ ACCORDION & GOOGLE SCHEMA)              |
|  [+] How long does it take to get a China visa?                          |
|  [+] Is biometric appointment required?                                  |
+--------------------------------------------------------------------------+
|  [ Chat on WhatsApp ]                     [ Direct Helpline Inquiry ]    |
+--------------------------------------------------------------------------+
```

---

## 🔍 SEO & Structured Data (JSON-LD)

When enabled in settings, **Travel-Z Visa Assistance** automatically outputs Google-compliant JSON-LD structured data in the `<head>` of all single visa pages:

```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://example.com" },
        { "@type": "ListItem", "position": 2, "name": "Visa Assistance", "item": "https://example.com/visa-assistance/" },
        { "@type": "ListItem", "position": 3, "name": "China", "item": "https://example.com/visa-assistance/china/" }
      ]
    },
    {
      "@type": "Service",
      "name": "China Visa Assistance",
      "serviceType": "Tourist Sticker visa",
      "description": "China Visa Assistance by TravelZ. Type: Tourist Sticker visa. Processing time: 10-15 working days...",
      "provider": {
        "@type": "TravelAgency",
        "name": "Travel-Z Visa Services",
        "telephone": "+88 01711-258451"
      },
      "offers": {
        "@type": "Offer",
        "price": "9500",
        "priceCurrency": "BDT"
      }
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "How long does it take to get a China visa?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Standard processing time is approximately 10 to 15 working days..."
          }
        }
      ]
    }
  ]
}
```

---

## 🛠 Theme Overrides & Developer Customization

### CSS Custom Properties
The plugin automatically exposes root CSS variables based on your settings. You can override them in your theme's `style.css`:

```css
:root {
  --travelz-primary: #0ebac5;       /* Primary brand accent */
  --travelz-primary-rgb: 14, 186, 197;
  --travelz-accent: #f26522;        /* Buttons & Highlights */
  --travelz-radius: 12px;           /* Border radius */
  --travelz-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}
```

### Template Override Hierarchy
You can customize the frontend markup without losing changes during plugin updates. Copy files from:
```
wp-content/plugins/travel-z-visa/templates/
```
into your child theme at:
```
wp-content/themes/your-child-theme/travel-z-visa/
```

WordPress will automatically look in your active theme before falling back to plugin defaults:
- `your-child-theme/travel-z-visa/archive-visa.php`
- `your-child-theme/travel-z-visa/single-visa.php`
- `your-child-theme/travel-z-visa/parts/flipbook-viewer.php`
- `your-child-theme/travel-z-visa/parts/checklist.php`
- `your-child-theme/travel-z-visa/parts/overview-card.php`

### Action Hooks & Filters

```php
// Filter custom post type slug before registration
add_filter( 'travelz_visa_cpt_slug', function( $slug ) {
    return 'visas';
} );

// Customize default settings values
add_filter( 'travelz_visa_default_settings', function( $defaults ) {
    $defaults['currency_symbol'] = 'USD';
    return $defaults;
} );

// Hook before the flipbook viewer renders
add_action( 'travelz_visa_before_flipbook', function( $post_id ) {
    // Custom disclaimer or promo banner
} );
```

---

## 🔒 Security & Performance

- **Role & Capability Verification:** All administrative write actions and AJAX endpoints require `manage_options` capabilities (`current_user_can('manage_options')`).
- **Nonce Authentication:** Meta boxes and settings forms validate cryptographic nonces (`check_admin_referer` and `wp_verify_nonce`).
- **Strict Data Sanitization:** All incoming inputs are sanitized with `sanitize_text_field`, `esc_url_raw`, `sanitize_hex_color`, and `wp_kses_post`.
- **Zero Heavy External Runtimes:** Fast, lightweight JavaScript engine avoiding monolithic third-party libraries; leverages asynchronous script loading.
- **Direct Access Protection:** Every PHP file starts with `if ( ! defined( 'ABSPATH' ) ) exit;` to prevent unauthorized file execution.

---

## ❓ Troubleshooting & FAQs

### Q: Clicking on a visa country page returns a 404 Not Found error.
**Solution:** WordPress rewrite rules need to be refreshed. Go to **Settings** > **Permalinks** in the WP admin and click **Save Changes**.

### Q: The PDF flipbook shows a blank screen or loading spinner indefinitely.
**Solution:** 
1. Verify that your PDF file URL is hosted on the same domain or has proper CORS headers (`Access-Control-Allow-Origin: *`).
2. Make sure the file exists and is publicly accessible by opening the PDF link in a new incognito window.
3. The plugin will automatically fallback to native embed if WebGL/Canvas rendering is unavailable.

### Q: Can I change the currency from BDT to another currency?
**Solution:** Yes. Navigate to **Travel-Z Visa** > **Settings** and update the **Currency Symbol** field (e.g. `$`, `USD`, `EUR`, `AED`, `GBP`).

### Q: Does the plugin work with page builders like Elementor?
**Solution:** Yes. You can use the shortcodes (`[travelz_visa_hub]`, `[travelz_visa_search]`, `[travelz_visa_card id="..."]`) inside any Elementor Shortcode or Text widget.

---

## 📝 Changelog

### Version 1.0.0
- **Initial Release:** Complete Travel-Z Visa Assistance System.
- Added custom post type `travelz_visa` and taxonomy `visa_region`.
- Integrated 3D Flipbook & PDF Document Viewer with zoom, audio, and fullscreen controls.
- Added tabbed profession document checklist (Job Holders, Business Persons, Students, General).
- Added multi-tier fee matrix repeater table.
- Added instant autocomplete country search and continent catalog filters.
- Implemented automatic Schema.org JSON-LD structured data (`Service`, `TravelAgency`, `Offer`, `Breadcrumbs`, `FAQPage`).
- Added full admin settings page with color picker, hero customizer, and sample data generator.

---

## 📄 License & Credits

- **License:** GNU General Public License v2 or later ([GPL-2.0-or-later](https://www.gnu.org/licenses/gpl-2.0.html))
- **Developed by:** Kamrul Hasan & Yeasmin
- **Official Website:** [https://tech.travelzbd.com/](https://tech.travelzbd.com/)

---

<p align="center">
  <b>Built with ❤️ for Travel-Z | Elevating Travel Experiences</b>
</p>
