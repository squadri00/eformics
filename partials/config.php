<?php
/* ===========================================================================
   Eformics Systems — site-wide configuration + shared helpers
   ---------------------------------------------------------------------------
   Included automatically by partials/head.php. You normally don't need to
   require this yourself.
   =========================================================================== */

/*
 * URL path the site is served from — NO trailing slash.
 *   XAMPP local ...... '/ef'
 *   domain root ...... ''
 * This one lives in code (it is a deployment detail). Change it when you deploy.
 */
if (!defined('BASE')) {
    define('BASE', '/ef');
}

/**
 * Build a BASE-relative URL.
 *   url('/products/meccora.php')  ->  /ef/products/meccora.php
 *   url('/')                      ->  /ef/
 */
function url(string $path = '/'): string
{
    return BASE . '/' . ltrim($path, '/');
}

/**
 * Escape text for safe output inside HTML.
 */
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

/**
 * A BASE-relative URL for a static asset, with a ?v= cache-buster taken from
 * the file's modification time, so CSS/JS changes always reach the browser.
 */
function asset(string $path): string
{
    $rel  = '/' . ltrim($path, '/');
    $file = dirname(__DIR__) . $rel;
    $v    = is_file($file) ? filemtime($file) : 1;
    return url($rel) . '?v=' . $v;
}

/* Database access + site settings (lazy — no connection until used). */
require_once __DIR__ . '/db.php';

/*
 * Site identity. Editable from the admin (Site settings); the values here are
 * the fallback used when the database is unavailable or a value is unset.
 */
define('SITE_NAME',       setting('site_name',       'Eformics Systems'));
define('SITE_PHONE',      setting('site_phone',      '+1 (866) 798-7860'));
define('SITE_PHONE_HREF', setting('site_phone_href', 'tel:+18667987860'));
define('SITE_EMAIL',      setting('site_email',      'hello@eformics.com'));
define('SITE_LOCATION',   setting('site_location',   'Mississauga, Ontario, Canada'));
define('SITE_ORIGIN',     rtrim(setting('site_origin', 'https://www.eformics.com'), '/'));

require_once __DIR__ . '/hero_media.php';
require_once __DIR__ . '/social_links.php';
require_once __DIR__ . '/homepage_video.php';
require_once __DIR__ . '/portfolio.php';
require_once __DIR__ . '/visual_enhance.php';
require_once __DIR__ . '/seo.php';
