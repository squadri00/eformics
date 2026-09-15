-- ===========================================================================
-- Eformics Systems — database schema
-- Apply:  mysql -u eformicsdb -p eformicsdb < sql/schema.sql   (safe to re-run)
-- ===========================================================================

-- Key/value settings ------------------------------------------------------
CREATE TABLE IF NOT EXISTS site_settings (
  setting_key   VARCHAR(64) NOT NULL PRIMARY KEY,
  setting_value TEXT        NOT NULL,
  updated_at    TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Drop keys from earlier prototypes
DELETE FROM site_settings
 WHERE setting_key IN ('hero_media_type', 'hero_image_url', 'hero_image_alt', 'hero_video_url', 'hero_video_poster', 'homepage_hero_enabled');

-- Homepage intro video (independent of per-page hero media)
INSERT INTO site_settings (setting_key, setting_value) VALUES
  ('home_video_enabled', '0'),
  ('home_video_type',    'youtube'),
  ('home_video_label',   'See Eformics in 90 seconds')
ON DUPLICATE KEY UPDATE setting_key = setting_key;

-- Per-page hero media (right-hand side of interior page heroes) --------
CREATE TABLE IF NOT EXISTS hero_media (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  page        VARCHAR(40)                      NOT NULL DEFAULT '',
  title       VARCHAR(150)                     NOT NULL,
  media_type  ENUM('image','video','youtube') NOT NULL DEFAULT 'image',
  image_path  VARCHAR(255)                     NOT NULL DEFAULT '',
  image_alt   VARCHAR(255)                     NOT NULL DEFAULT '',
  video_path  VARCHAR(255)                     NOT NULL DEFAULT '',
  youtube_id  VARCHAR(32)                      NOT NULL DEFAULT '',
  poster_path VARCHAR(255)                     NOT NULL DEFAULT '',
  is_active   TINYINT(1)                       NOT NULL DEFAULT 0,
  created_at  TIMESTAMP                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP                        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_hero_page (page, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Upgrades for older installs (safe to re-run)
ALTER TABLE hero_media ADD COLUMN IF NOT EXISTS page VARCHAR(40) NOT NULL DEFAULT '' AFTER id;
ALTER TABLE hero_media MODIFY COLUMN media_type ENUM('image','video','youtube') NOT NULL DEFAULT 'image';
ALTER TABLE hero_media ADD COLUMN IF NOT EXISTS youtube_id VARCHAR(32) NOT NULL DEFAULT '' AFTER video_path;

-- Seed the three product-page logos, only if none exist yet
INSERT INTO hero_media (page, title, media_type, image_path, image_alt, is_active)
SELECT s.page, s.title, s.media_type, s.image_path, s.image_alt, s.is_active FROM (
      SELECT 'meccora'  AS page, 'Meccora logo'  AS title, 'image' AS media_type, '/ef/assets/img/meccora-logo.png'  AS image_path, 'Meccora logo'  AS image_alt, 1 AS is_active
  UNION ALL SELECT 'quotaire', 'Quotaire logo', 'image', '/ef/assets/img/quotaire-logo.png', 'Quotaire logo', 1
  UNION ALL SELECT 'chantley', 'Chantley logo', 'image', '/ef/assets/img/chantley-logo.png', 'Chantley logo', 1
) AS s
WHERE NOT EXISTS (SELECT 1 FROM hero_media WHERE page IN ('meccora', 'quotaire', 'chantley'));

-- Social media links (footer) ----------------------------------------
CREATE TABLE IF NOT EXISTS social_links (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  label      VARCHAR(80)  NOT NULL,
  url        VARCHAR(500) NOT NULL,
  icon       VARCHAR(40)  NOT NULL DEFAULT 'link',
  sort_order INT          NOT NULL DEFAULT 0,
  is_enabled TINYINT(1)   NOT NULL DEFAULT 1,
  created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_social_enabled (is_enabled, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed the three existing footer icons, only if the table is empty.
INSERT INTO social_links (label, url, icon, sort_order, is_enabled)
SELECT s.label, s.url, s.icon, s.sort_order, s.is_enabled FROM (
      SELECT 'Facebook' AS label, '#' AS url, 'facebook' AS icon, 10 AS sort_order, 1 AS is_enabled
  UNION ALL SELECT 'LinkedIn', '#', 'linkedin', 20, 1
  UNION ALL SELECT 'YouTube',  '#', 'youtube',  30, 1
) AS s
WHERE NOT EXISTS (SELECT 1 FROM social_links);

-- Web design portfolio ------------------------------------------------
CREATE TABLE IF NOT EXISTS portfolio_projects (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(150) NOT NULL,
  image_path  VARCHAR(255) NOT NULL DEFAULT '',
  image_alt   VARCHAR(255) NOT NULL DEFAULT '',
  website_url VARCHAR(500) NOT NULL DEFAULT '',
  info        TEXT         NULL,
  client_name VARCHAR(150) NOT NULL DEFAULT '',
  is_featured TINYINT(1)   NOT NULL DEFAULT 0,
  is_enabled  TINYINT(1)   NOT NULL DEFAULT 1,
  sort_order  INT          NOT NULL DEFAULT 0,
  created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_folio (is_enabled, is_featured, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO site_settings (setting_key, setting_value) VALUES
  ('portfolio_columns', '3'),
  ('portfolio_heading', 'Our work'),
  ('portfolio_intro',   'A selection of websites we have designed and built for businesses.'),
  ('wd_slider_enabled',  '1'),
  ('wd_slider_per_view', '3'),
  ('wd_slider_autoplay', '1'),
  ('wd_slider_interval', '4'),
  ('wd_slider_heading',  'Recent work'),
  ('wd_slider_intro',    'A few of the websites we have designed and built.')
ON DUPLICATE KEY UPDATE setting_key = setting_key;

-- ===========================================================================
-- Visual Enhancement service — before/after image pairs + video clips
-- ===========================================================================
CREATE TABLE IF NOT EXISTS va_before_after (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(150) NOT NULL DEFAULT '',
  before_path VARCHAR(255) NOT NULL DEFAULT '',
  after_path  VARCHAR(255) NOT NULL DEFAULT '',
  before_alt  VARCHAR(255) NOT NULL DEFAULT '',
  after_alt   VARCHAR(255) NOT NULL DEFAULT '',
  is_enabled  TINYINT(1)   NOT NULL DEFAULT 1,
  sort_order  INT          NOT NULL DEFAULT 0,
  created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_va_ba (is_enabled, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS va_videos (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(150) NOT NULL DEFAULT '',
  source      ENUM('upload','embed') NOT NULL DEFAULT 'upload',
  video_path  VARCHAR(255) NOT NULL DEFAULT '',   -- when source = upload
  video_url   VARCHAR(500) NOT NULL DEFAULT '',   -- when source = embed (YouTube / Vimeo)
  poster_path VARCHAR(255) NOT NULL DEFAULT '',
  is_enabled  TINYINT(1)   NOT NULL DEFAULT 1,
  sort_order  INT          NOT NULL DEFAULT 0,
  created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_va_vid (is_enabled, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO seo_pages (page_key, page_path, page_label, sitemap_priority, sitemap_changefreq)
SELECT 'portfolio', '/portfolio.php', 'Portfolio', 0.7, 'monthly'
WHERE NOT EXISTS (SELECT 1 FROM seo_pages WHERE page_key = 'portfolio');

UPDATE seo_pages SET title = 'Web Design Portfolio | Eformics Systems',
  meta_description = 'A selection of websites Eformics Systems has designed and built for businesses across many industries.'
  WHERE page_key = 'portfolio' AND title = '' AND meta_description = '';

INSERT INTO seo_pages (page_key, page_path, page_label, sitemap_priority, sitemap_changefreq)
SELECT 'products/smart-qr-menu', '/products/smart-qr-menu.php', 'Smart QR Menu', 0.8, 'monthly'
WHERE NOT EXISTS (SELECT 1 FROM seo_pages WHERE page_key = 'products/smart-qr-menu');

UPDATE seo_pages SET title = 'Smart QR Menu — Live Digital Menus for Restaurants | Eformics',
  meta_description = 'A fully managed digital menu for restaurants. Customers scan, your menu opens instantly with photos and prices, and our team keeps it updated for you.'
  WHERE page_key = 'products/smart-qr-menu' AND title = '' AND meta_description = '';

INSERT INTO seo_pages (page_key, page_path, page_label, sitemap_priority, sitemap_changefreq)
SELECT 'products/recodik', '/products/recodik.php', 'Recodik', 0.8, 'monthly'
WHERE NOT EXISTS (SELECT 1 FROM seo_pages WHERE page_key = 'products/recodik');

UPDATE seo_pages SET title = 'Recodik — Free, Self-Hosted Record Keeper | Eformics Systems',
  meta_description = 'Recodik is a free, self-hosted, no-code record keeper you fully customize — build your own categories and fields for customers, passwords, domains, anything. No license, ever.'
  WHERE page_key = 'products/recodik' AND title = '' AND meta_description = '';

UPDATE seo_pages SET jsonld =
  '{"@context":"https://schema.org","@type":"SoftwareApplication","name":"Recodik","applicationCategory":"BusinessApplication","operatingSystem":"Windows, Self-hosted","description":"A free, self-hosted, no-code record keeper — build your own categories and fields for anything you need to track.","offers":{"@type":"Offer","price":"0","priceCurrency":"USD"},"provider":{"@type":"Organization","name":"Eformics Systems"}}'
  WHERE page_key = 'products/recodik' AND jsonld IS NULL;

-- Recodik download gate: one row per email that has requested (and, once
-- verified, downloaded) Recodik.exe. A fresh OTP request for the same email
-- updates its existing row rather than inserting a new one (see
-- partials/recodik.php), so this stays one row per email, not one per request.
CREATE TABLE IF NOT EXISTS recodik_downloads (
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  email          VARCHAR(255) NOT NULL,
  country        VARCHAR(100) NOT NULL,
  otp_hash       VARCHAR(255) NOT NULL,
  otp_expires_at DATETIME     NOT NULL,
  attempts       TINYINT UNSIGNED NOT NULL DEFAULT 0,
  last_sent_at   DATETIME     NOT NULL,
  verified_at    DATETIME     NULL,
  ip_address     VARCHAR(45)  NULL,
  created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_recodik_email (email),
  INDEX idx_recodik_verified (verified_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO seo_pages (page_key, page_path, page_label, sitemap_priority, sitemap_changefreq)
SELECT 'services/visual-enhancement', '/services/visual-enhancement.php', 'Visual Enhancement', 0.8, 'monthly'
WHERE NOT EXISTS (SELECT 1 FROM seo_pages WHERE page_key = 'services/visual-enhancement');

UPDATE seo_pages SET title = 'Restaurant Visual Enhancement — Food Photo & Video Editing | Eformics',
  meta_description = 'We turn ordinary restaurant food photos into premium, menu-ready images and short animated videos for websites, delivery apps and social media.'
  WHERE page_key = 'services/visual-enhancement' AND title = '' AND meta_description = '';

-- ===========================================================================
-- SEO
-- ===========================================================================

-- Global SEO defaults (key/value)
INSERT INTO site_settings (setting_key, setting_value) VALUES
  ('seo_default_description',   ''),
  ('seo_default_og_image',      ''),
  ('seo_og_site_name',          'Eformics Systems'),
  ('seo_og_locale',             'en_CA'),
  ('seo_twitter_site',          ''),
  ('seo_twitter_default_card',  'summary_large_image'),
  ('seo_google_verification',   ''),
  ('seo_bing_verification',     ''),
  ('seo_favicon',               ''),
  ('seo_apple_icon',            ''),
  ('seo_theme_color',           '#393193'),
  ('seo_noindex_site',          '0'),
  ('seo_org_jsonld',            ''),
  ('seo_head_snippet',          ''),
  ('seo_body_snippet',          ''),
  ('seo_robots_txt',            '')
ON DUPLICATE KEY UPDATE setting_key = setting_key;

-- Per-page SEO -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS seo_pages (
  id                 INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  page_key           VARCHAR(80)  NOT NULL,
  page_path          VARCHAR(255) NOT NULL DEFAULT '/',
  page_label         VARCHAR(120) NOT NULL DEFAULT '',
  title              VARCHAR(255) NOT NULL DEFAULT '',
  meta_description   VARCHAR(400) NOT NULL DEFAULT '',
  meta_keywords      VARCHAR(255) NOT NULL DEFAULT '',
  canonical          VARCHAR(255) NOT NULL DEFAULT '',
  robots_index       TINYINT(1)   NOT NULL DEFAULT 1,
  robots_follow      TINYINT(1)   NOT NULL DEFAULT 1,
  robots_advanced    VARCHAR(255) NOT NULL DEFAULT '',
  og_title           VARCHAR(255) NOT NULL DEFAULT '',
  og_description     VARCHAR(400) NOT NULL DEFAULT '',
  og_image           VARCHAR(255) NOT NULL DEFAULT '',
  og_type            VARCHAR(40)  NOT NULL DEFAULT '',
  tw_card            VARCHAR(40)  NOT NULL DEFAULT '',
  tw_title           VARCHAR(255) NOT NULL DEFAULT '',
  tw_description     VARCHAR(400) NOT NULL DEFAULT '',
  tw_image           VARCHAR(255) NOT NULL DEFAULT '',
  jsonld             TEXT         NULL,
  head_snippet       TEXT         NULL,
  body_snippet       TEXT         NULL,
  sitemap_include    TINYINT(1)   NOT NULL DEFAULT 1,
  sitemap_priority   DECIMAL(2,1) NOT NULL DEFAULT 0.5,
  sitemap_changefreq VARCHAR(20)  NOT NULL DEFAULT 'monthly',
  updated_at         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_seo_page_key (page_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE seo_pages ADD COLUMN IF NOT EXISTS published TINYINT(1) NOT NULL DEFAULT 1 AFTER page_label;

-- Register the known pages, only if the table is empty
INSERT INTO seo_pages (page_key, page_path, page_label, sitemap_priority, sitemap_changefreq)
SELECT s.k, s.p, s.l, s.pr, s.cf FROM (
      SELECT 'home' k, '/' p, 'Home' l, 1.0 pr, 'weekly' cf
  UNION ALL SELECT 'about',                '/about.php',                'About / Who We Are',    0.7, 'monthly'
  UNION ALL SELECT 'contact',              '/contact.php',              'Contact',               0.7, 'monthly'
  UNION ALL SELECT 'products/meccora',     '/products/meccora.php',     'Meccora',               0.8, 'monthly'
  UNION ALL SELECT 'products/quotaire',    '/products/quotaire.php',    'Quotaire',              0.8, 'monthly'
  UNION ALL SELECT 'products/chantley',    '/products/chantley.php',    'Chantley',              0.8, 'monthly'
  UNION ALL SELECT 'services/development', '/services/development.php',  'Custom Development',     0.8, 'monthly'
  UNION ALL SELECT 'services/web-design',  '/services/web-design.php',   'Website Designing',     0.8, 'monthly'
  UNION ALL SELECT 'services/pwa',         '/services/pwa.php',          'Progressive Web Apps',  0.8, 'monthly'
  UNION ALL SELECT 'privacy-policy',       '/privacy-policy.php',        'Privacy Policy',        0.3, 'yearly'
  UNION ALL SELECT 'terms',                '/terms.php',                'Terms of Use',          0.3, 'yearly'
  UNION ALL SELECT 'sitemap',              '/sitemap.php',              'Sitemap (HTML page)',   0.3, 'yearly'
) AS s
WHERE NOT EXISTS (SELECT 1 FROM seo_pages);

-- Starter SEO content — only fills fields that are still blank, so it never
-- overwrites anything edited in the admin.
UPDATE site_settings SET setting_value =
  'Eformics Systems builds custom software, websites and progressive web apps for businesses, and runs its own SaaS products. Based in Mississauga, Ontario since 2009.'
  WHERE setting_key = 'seo_default_description' AND setting_value = '';

UPDATE seo_pages SET title = 'Eformics Systems | Custom Software & Websites for Business',
  meta_description = 'We build custom software, websites and progressive web apps for small and mid-sized businesses, and run our own SaaS products. Building software since 2009.'
  WHERE page_key = 'home' AND title = '' AND meta_description = '';

UPDATE seo_pages SET title = 'About Eformics Systems — Our Team and Story | Since 2009',
  meta_description = 'Eformics Systems started as a web design shop in 2009 and now builds custom software for clients while running three of its own SaaS products.'
  WHERE page_key = 'about' AND title = '' AND meta_description = '';

UPDATE seo_pages SET title = 'Contact Eformics Systems — Start Your Project',
  meta_description = 'Tell us about your software, website or web app project. Send a message and the Eformics Systems team will get back to you.'
  WHERE page_key = 'contact' AND title = '' AND meta_description = '';

UPDATE seo_pages SET title = 'Meccora — Service Reminder Software for Auto Repair Shops',
  meta_description = 'Meccora keeps auto repair customers coming back with automated email and SMS service reminders, full vehicle history and deferred-work recovery.'
  WHERE page_key = 'products/meccora' AND title = '' AND meta_description = '';

UPDATE seo_pages SET title = 'Quotaire — Build Your Own Online Quote Calculator',
  meta_description = 'Quotaire turns your price list into a live online quote calculator in four steps. Use it on your website or internally — build it yourself, or we build it.'
  WHERE page_key = 'products/quotaire' AND title = '' AND meta_description = '';

UPDATE seo_pages SET title = 'Chantley — AI Chatbot for Small Business Websites',
  meta_description = 'Chantley turns your website content into an AI chatbot that answers customer questions 24/7. No coding, live the same day, plans from $29 per month.'
  WHERE page_key = 'products/chantley' AND title = '' AND meta_description = '';

UPDATE seo_pages SET title = 'Custom Software Development for Business | Eformics Systems',
  meta_description = 'We design and build custom web applications, customer portals and internal tools around how your business actually works — then support them long term.'
  WHERE page_key = 'services/development' AND title = '' AND meta_description = '';

UPDATE seo_pages SET title = 'Website Design Services — Fast, Modern, Built to Convert',
  meta_description = 'Eformics Systems designs fast, responsive, conversion-focused websites for businesses that want more than a template, with SEO foundations from day one.'
  WHERE page_key = 'services/web-design' AND title = '' AND meta_description = '';

UPDATE seo_pages SET title = 'Progressive Web App Development | Eformics Systems',
  meta_description = 'We build installable, offline-capable progressive web apps for small and mid-sized businesses — no app store, one codebase, push notifications where supported.'
  WHERE page_key = 'services/pwa' AND title = '' AND meta_description = '';

UPDATE seo_pages SET title = 'Privacy Policy | Eformics Systems',
  meta_description = 'How Eformics Systems collects, uses and protects personal information, in line with Canada''s PIPEDA.'
  WHERE page_key = 'privacy-policy' AND title = '' AND meta_description = '';

UPDATE seo_pages SET title = 'Terms of Use | Eformics Systems',
  meta_description = 'The terms that govern use of the Eformics Systems website. Paid work and our products are covered by separate agreements.'
  WHERE page_key = 'terms' AND title = '' AND meta_description = '';

UPDATE seo_pages SET title = 'Sitemap | Eformics Systems', robots_index = 0
  WHERE page_key = 'sitemap' AND title = '';

-- Starter structured data (only where none is set yet)
UPDATE seo_pages SET jsonld =
  '{"@context":"https://schema.org","@type":"SoftwareApplication","name":"Meccora","applicationCategory":"BusinessApplication","operatingSystem":"Web","description":"Automated service reminders, vehicle history and deferred-work recovery for independent auto repair shops.","offers":{"@type":"Offer","price":"60","priceCurrency":"CAD"},"provider":{"@type":"Organization","name":"Eformics Systems"}}'
  WHERE page_key = 'products/meccora' AND jsonld IS NULL;

UPDATE seo_pages SET jsonld =
  '{"@context":"https://schema.org","@type":"SoftwareApplication","name":"Quotaire","applicationCategory":"BusinessApplication","operatingSystem":"Web","description":"Build custom online quote calculators from your own price list, in four steps.","provider":{"@type":"Organization","name":"Eformics Systems"}}'
  WHERE page_key = 'products/quotaire' AND jsonld IS NULL;

UPDATE seo_pages SET jsonld =
  '{"@context":"https://schema.org","@type":"SoftwareApplication","name":"Chantley","applicationCategory":"BusinessApplication","operatingSystem":"Web","description":"Turns your website content into an AI chatbot that answers customer questions 24/7.","offers":{"@type":"Offer","price":"29","priceCurrency":"USD"},"provider":{"@type":"Organization","name":"Eformics Systems"}}'
  WHERE page_key = 'products/chantley' AND jsonld IS NULL;

UPDATE seo_pages SET jsonld =
  '{"@context":"https://schema.org","@type":"Service","serviceType":"Custom software development","areaServed":"CA","provider":{"@type":"Organization","name":"Eformics Systems"},"description":"Custom web applications, customer portals and internal tools built around your business processes."}'
  WHERE page_key = 'services/development' AND jsonld IS NULL;

UPDATE seo_pages SET jsonld =
  '{"@context":"https://schema.org","@type":"Service","serviceType":"Website design","areaServed":"CA","provider":{"@type":"Organization","name":"Eformics Systems"},"description":"Fast, responsive, conversion-focused website design with SEO foundations from day one."}'
  WHERE page_key = 'services/web-design' AND jsonld IS NULL;

UPDATE seo_pages SET jsonld =
  '{"@context":"https://schema.org","@type":"Service","serviceType":"Progressive web app development","areaServed":"CA","provider":{"@type":"Organization","name":"Eformics Systems"},"description":"Installable, offline-capable progressive web apps for small and mid-sized businesses."}'
  WHERE page_key = 'services/pwa' AND jsonld IS NULL;
