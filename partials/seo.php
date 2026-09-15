<?php
/* ===========================================================================
   seo — global + per-page SEO, robots.txt and sitemap.xml
   ---------------------------------------------------------------------------
   Loaded by partials/config.php.  partials/head.php calls seo_head($page) and
   partials/footer.php calls seo_body().  Nothing here touches the DB until a
   function that needs it runs.
   =========================================================================== */

require_once __DIR__ . '/db.php';

/* ---------- global defaults --------------------------------------- */

function seo_globals(): array
{
    static $g = null;
    if ($g !== null) {
        return $g;
    }
    return $g = [
        'default_description' => setting('seo_default_description', ''),
        'default_og_image'    => setting('seo_default_og_image', ''),
        'og_site_name'        => setting('seo_og_site_name', SITE_NAME),
        'og_locale'           => setting('seo_og_locale', 'en_CA'),
        'twitter_site'        => setting('seo_twitter_site', ''),
        'twitter_card'        => setting('seo_twitter_default_card', 'summary_large_image'),
        'google_verification' => setting('seo_google_verification', ''),
        'bing_verification'   => setting('seo_bing_verification', ''),
        'favicon'             => setting('seo_favicon', ''),
        'apple_icon'          => setting('seo_apple_icon', ''),
        'theme_color'         => setting('seo_theme_color', '#393193'),
        'noindex_site'        => setting('seo_noindex_site', '0') === '1',
        'org_jsonld'          => setting('seo_org_jsonld', ''),
        'head_snippet'        => setting('seo_head_snippet', ''),
        'body_snippet'        => setting('seo_body_snippet', ''),
    ];
}

/* ---------- page key + per-page rows ---------------------------- */

/** Derive the SEO key for the current request, e.g. "products/meccora", "home". */
function seo_current_key(): string
{
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    if (BASE !== '' && strncmp($script, BASE . '/', strlen(BASE) + 1) === 0) {
        $script = substr($script, strlen(BASE));
    }
    $script = preg_replace('~\.php$~', '', ltrim($script, '/'));
    return ($script === '' || $script === 'index') ? 'home' : $script;
}

function seo_page(string $key): ?array
{
    static $cache = [];
    if ($key === '') {
        return null;
    }
    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }
    $pdo = db();
    if (!$pdo) {
        return $cache[$key] = null;
    }
    try {
        $st = $pdo->prepare('SELECT * FROM seo_pages WHERE page_key = ?');
        $st->execute([$key]);
        return $cache[$key] = ($st->fetch() ?: null);
    } catch (PDOException $e) {
        error_log('[seo] page: ' . $e->getMessage());
        return $cache[$key] = null;
    }
}

function seo_all_pages(): array
{
    $pdo = db();
    if (!$pdo) {
        return [];
    }
    try {
        return $pdo->query('SELECT * FROM seo_pages ORDER BY page_key')->fetchAll();
    } catch (PDOException $e) {
        error_log('[seo] all: ' . $e->getMessage());
        return [];
    }
}

/** Is a page live? Unknown keys and 'home' are always live. */
function page_is_published(string $key): bool
{
    if ($key === '' || $key === 'home') {
        return true;
    }
    $row = seo_page($key);
    return $row ? (int) ($row['published'] ?? 1) === 1 : true;
}

/**
 * Called at the top of every page. If the current page is disabled in the admin,
 * send a 404 with a branded "not available" page and stop.
 */
function seo_guard_page(string $key): void
{
    if (page_is_published($key)) {
        return;
    }
    http_response_code(404);
    header('X-Robots-Tag: noindex, nofollow');
    $page = ['section' => ''];
    ?><!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Page not found | <?= e(SITE_NAME) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('/assets/css/style.css') ?>">
</head>
<body>
<?php require __DIR__ . '/header.php'; ?>
<section class="page-hero">
  <div class="container">
    <span class="pill">404</span>
    <h1 style="margin-top:16px;">This page isn't available.</h1>
    <p class="lede">The page you're looking for has been moved or is no longer published.</p>
    <div class="hero-cta"><a href="<?= url('/') ?>" class="btn btn-primary">Go to the homepage</a></div>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
<?php
    exit;
}

/** Admin: publish / unpublish one page. */
function seo_page_set_published(string $key, bool $on): bool
{
    $pdo = db();
    if (!$pdo || $key === '' || $key === 'home') {
        return false;
    }
    try {
        return $pdo->prepare('UPDATE seo_pages SET published = ? WHERE page_key = ?')
            ->execute([$on ? 1 : 0, $key]);
    } catch (PDOException $e) {
        error_log('[seo] publish: ' . $e->getMessage());
        return false;
    }
}

/* ---------- small helpers ------------------------------------- */

function seo_first(...$vals): string
{
    foreach ($vals as $v) {
        $v = trim((string) $v);
        if ($v !== '') {
            return $v;
        }
    }
    return '';
}

/** Absolute URL from an absolute URL, a "/path", or a "path". */
function seo_abs_url(string $u): string
{
    $u = trim($u);
    if ($u === '') {
        return '';
    }
    if (preg_match('~^https?://~i', $u)) {
        return $u;
    }
    $origin = rtrim(SITE_ORIGIN, '/');
    return $u[0] === '/' ? $origin . $u : $origin . '/' . $u;
}

/** Trim to n characters on a word boundary, adding an ellipsis. */
function seo_truncate(string $s, int $n): string
{
    $s = trim(preg_replace('/\s+/', ' ', $s));
    if (mb_strlen($s) <= $n) {
        return $s;
    }
    return rtrim(mb_substr($s, 0, $n - 1)) . '…';
}

/** Safe to drop inside <script type="application/ld+json">. */
function seo_clean_jsonld(string $s): string
{
    $decoded = json_decode($s, true);
    if (is_array($decoded)) {
        $s = json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    return str_replace(['</', '<!--'], ['<\/', '<\!--'], $s);
}

/** A sensible default Organization block built from the site's own data. */
function seo_default_org_jsonld(): string
{
    $sameAs = [];
    if (function_exists('social_links_enabled')) {
        foreach (social_links_enabled() as $s) {
            if (!empty($s['url']) && $s['url'] !== '#') {
                $sameAs[] = $s['url'];
            }
        }
    }
    $data = [
        '@context'  => 'https://schema.org',
        '@type'     => 'Organization',
        'name'      => SITE_NAME,
        'url'       => rtrim(SITE_ORIGIN, '/') . '/',
        'logo'      => seo_abs_url('/assets/img/eformics-logo-red.webp'),
        'email'     => SITE_EMAIL,
        'telephone' => SITE_PHONE,
        'address'   => [
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Mississauga',
            'addressRegion'   => 'ON',
            'addressCountry'  => 'CA',
        ],
    ];
    if ($sameAs) {
        $data['sameAs'] = $sameAs;
    }
    return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

/* ---------- the <head> SEO block ---------------------------- */

/**
 * Resolve every value with precedence:  per-page override → page file → global → fallback.
 * Returns a ready-to-print block of tags.
 */
function seo_head(array $page): string
{
    $g   = seo_globals();
    $key = $page['seo_key'] ?? seo_current_key();
    $row = seo_page($key) ?? [];

    $title = seo_first($row['title'] ?? '', $page['title'] ?? '', $g['og_site_name'], SITE_NAME);
    $desc  = seo_first($row['meta_description'] ?? '', $page['description'] ?? '', $g['default_description']);

    $canon = seo_abs_url(seo_first($row['canonical'] ?? '', $page['canonical'] ?? '/', '/'));

    $index  = !$g['noindex_site'] && (int) ($row['robots_index'] ?? 1) === 1;
    $follow = (int) ($row['robots_follow'] ?? 1) === 1;
    $robots = [$index ? 'index' : 'noindex', $follow ? 'follow' : 'nofollow'];
    if (trim($row['robots_advanced'] ?? '') !== '') {
        $robots[] = trim($row['robots_advanced']);
    }

    $ogTitle = seo_first($row['og_title'] ?? '', $title);
    $ogDesc  = seo_first($row['og_description'] ?? '', $desc);
    $ogImage = seo_abs_url(seo_first($row['og_image'] ?? '', $g['default_og_image']));
    $ogType  = seo_first($row['og_type'] ?? '', 'website');

    $twCard  = seo_first($row['tw_card'] ?? '', $g['twitter_card'], 'summary');
    $twTitle = seo_first($row['tw_title'] ?? '', $ogTitle);
    $twDesc  = seo_first($row['tw_description'] ?? '', $ogDesc);
    $twImage = seo_abs_url(seo_first($row['tw_image'] ?? '', $ogImage));

    $o = [];
    $o[] = '<title>' . e($title) . '</title>';
    if ($desc !== '') {
        $o[] = '<meta name="description" content="' . e($desc) . '">';
    }
    if (trim($row['meta_keywords'] ?? '') !== '') {
        $o[] = '<meta name="keywords" content="' . e($row['meta_keywords']) . '">';
    }
    $o[] = '<meta name="robots" content="' . e(implode(', ', $robots)) . '">';
    $o[] = '<link rel="canonical" href="' . e($canon) . '">';
    if ($g['theme_color'] !== '') {
        $o[] = '<meta name="theme-color" content="' . e($g['theme_color']) . '">';
    }
    if ($g['favicon'] !== '') {
        $o[] = '<link rel="icon" href="' . e(seo_abs_url($g['favicon'])) . '">';
    }
    if ($g['apple_icon'] !== '') {
        $o[] = '<link rel="apple-touch-icon" href="' . e(seo_abs_url($g['apple_icon'])) . '">';
    }
    if ($g['google_verification'] !== '') {
        $o[] = '<meta name="google-site-verification" content="' . e($g['google_verification']) . '">';
    }
    if ($g['bing_verification'] !== '') {
        $o[] = '<meta name="msvalidate.01" content="' . e($g['bing_verification']) . '">';
    }

    $o[] = '<meta property="og:title" content="' . e($ogTitle) . '">';
    if ($ogDesc !== '') {
        $o[] = '<meta property="og:description" content="' . e($ogDesc) . '">';
    }
    $o[] = '<meta property="og:type" content="' . e($ogType) . '">';
    $o[] = '<meta property="og:url" content="' . e($canon) . '">';
    $o[] = '<meta property="og:site_name" content="' . e($g['og_site_name']) . '">';
    if ($g['og_locale'] !== '') {
        $o[] = '<meta property="og:locale" content="' . e($g['og_locale']) . '">';
    }
    if ($ogImage !== '') {
        $o[] = '<meta property="og:image" content="' . e($ogImage) . '">';
    }

    $o[] = '<meta name="twitter:card" content="' . e($twCard) . '">';
    if ($g['twitter_site'] !== '') {
        $o[] = '<meta name="twitter:site" content="' . e($g['twitter_site']) . '">';
    }
    $o[] = '<meta name="twitter:title" content="' . e($twTitle) . '">';
    if ($twDesc !== '') {
        $o[] = '<meta name="twitter:description" content="' . e($twDesc) . '">';
    }
    if ($twImage !== '') {
        $o[] = '<meta name="twitter:image" content="' . e($twImage) . '">';
    }

    $org = seo_first($g['org_jsonld'], seo_default_org_jsonld());
    if ($org !== '') {
        $o[] = '<script type="application/ld+json">' . seo_clean_jsonld($org) . '</script>';
    }
    if (trim($row['jsonld'] ?? '') !== '') {
        $o[] = '<script type="application/ld+json">' . seo_clean_jsonld($row['jsonld']) . '</script>';
    }

    if (trim($g['head_snippet']) !== '') {
        $o[] = $g['head_snippet'];
    }
    if (trim($row['head_snippet'] ?? '') !== '') {
        $o[] = $row['head_snippet'];
    }

    return implode("\n", $o) . "\n";
}

/* ---------- pre-</body> snippets ------------------------- */

function seo_body(string $key = ''): string
{
    $g   = seo_globals();
    $row = seo_page($key !== '' ? $key : seo_current_key()) ?? [];
    $o   = [];
    if (trim($g['body_snippet']) !== '') {
        $o[] = $g['body_snippet'];
    }
    if (trim($row['body_snippet'] ?? '') !== '') {
        $o[] = $row['body_snippet'];
    }
    return $o ? implode("\n", $o) . "\n" : '';
}

/* ---------- admin: save / reset ------------------------- */

function seo_page_save(string $key, array $d): bool
{
    $pdo = db();
    if (!$pdo) {
        return false;
    }
    try {
        $st = $pdo->prepare(
            'UPDATE seo_pages SET
                page_label = :page_label, page_path = :page_path, published = :published,
                title = :title, meta_description = :meta_description, meta_keywords = :meta_keywords,
                canonical = :canonical, robots_index = :robots_index, robots_follow = :robots_follow,
                robots_advanced = :robots_advanced,
                og_title = :og_title, og_description = :og_description, og_image = :og_image, og_type = :og_type,
                tw_card = :tw_card, tw_title = :tw_title, tw_description = :tw_description, tw_image = :tw_image,
                jsonld = :jsonld, head_snippet = :head_snippet, body_snippet = :body_snippet,
                sitemap_include = :sitemap_include, sitemap_priority = :sitemap_priority,
                sitemap_changefreq = :sitemap_changefreq
             WHERE page_key = :key'
        );
        return $st->execute([
            ':page_label'         => $d['page_label'] ?? '',
            ':page_path'          => $d['page_path'] ?? '/',
            ':published'          => !empty($d['published']) ? 1 : 0,
            ':title'              => $d['title'] ?? '',
            ':meta_description'   => $d['meta_description'] ?? '',
            ':meta_keywords'      => $d['meta_keywords'] ?? '',
            ':canonical'          => $d['canonical'] ?? '',
            ':robots_index'       => !empty($d['robots_index']) ? 1 : 0,
            ':robots_follow'      => !empty($d['robots_follow']) ? 1 : 0,
            ':robots_advanced'    => $d['robots_advanced'] ?? '',
            ':og_title'           => $d['og_title'] ?? '',
            ':og_description'     => $d['og_description'] ?? '',
            ':og_image'           => $d['og_image'] ?? '',
            ':og_type'            => $d['og_type'] ?? '',
            ':tw_card'            => $d['tw_card'] ?? '',
            ':tw_title'           => $d['tw_title'] ?? '',
            ':tw_description'     => $d['tw_description'] ?? '',
            ':tw_image'           => $d['tw_image'] ?? '',
            ':jsonld'             => ($d['jsonld'] ?? '') !== '' ? $d['jsonld'] : null,
            ':head_snippet'       => ($d['head_snippet'] ?? '') !== '' ? $d['head_snippet'] : null,
            ':body_snippet'       => ($d['body_snippet'] ?? '') !== '' ? $d['body_snippet'] : null,
            ':sitemap_include'    => !empty($d['sitemap_include']) ? 1 : 0,
            ':sitemap_priority'   => number_format((float) ($d['sitemap_priority'] ?? 0.5), 1),
            ':sitemap_changefreq' => $d['sitemap_changefreq'] ?? 'monthly',
            ':key'                => $key,
        ]);
    } catch (PDOException $e) {
        error_log('[seo] save: ' . $e->getMessage());
        return false;
    }
}

function seo_page_reset(string $key): bool
{
    $pdo = db();
    if (!$pdo) {
        return false;
    }
    try {
        $st = $pdo->prepare(
            "UPDATE seo_pages SET title = '', meta_description = '', meta_keywords = '', canonical = '',
                robots_index = 1, robots_follow = 1, robots_advanced = '',
                og_title = '', og_description = '', og_image = '', og_type = '',
                tw_card = '', tw_title = '', tw_description = '', tw_image = '',
                jsonld = NULL, head_snippet = NULL, body_snippet = NULL
             WHERE page_key = ?"
        );
        return $st->execute([$key]);
    } catch (PDOException $e) {
        error_log('[seo] reset: ' . $e->getMessage());
        return false;
    }
}

/* ---------- robots.txt + sitemap.xml ------------------- */

function seo_webroot(): string
{
    return dirname(__DIR__);
}

function seo_default_robots(): string
{
    $origin = rtrim(SITE_ORIGIN, '/');
    return "User-agent: *\nAllow: /\n\n"
         . "Disallow: /admin/\nDisallow: /partials/\nDisallow: /vendor/\nDisallow: /sql/\nDisallow: /assets/uploads/\n\n"
         . "Sitemap: {$origin}/sitemap.xml\n";
}

/** Write robots.txt to the web root. Returns [ok, message]. */
function seo_write_robots(string $content): array
{
    $content = trim($content) === '' ? seo_default_robots() : $content;
    $path    = seo_webroot() . '/robots.txt';
    $ok      = @file_put_contents($path, $content) !== false;
    return [$ok, $ok ? $path : 'Could not write ' . $path];
}

function seo_build_sitemap_xml(): string
{
    $origin = rtrim(SITE_ORIGIN, '/');
    $xml    = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml   .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach (seo_all_pages() as $r) {
        if ((int) $r['sitemap_include'] !== 1 || (int) ($r['published'] ?? 1) !== 1) {
            continue;
        }
        $loc = $origin . ($r['page_path'] === '/' ? '/' : $r['page_path']);
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . htmlspecialchars($loc, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
        $xml .= '    <lastmod>' . substr((string) $r['updated_at'], 0, 10) . "</lastmod>\n";
        $xml .= '    <changefreq>' . htmlspecialchars($r['sitemap_changefreq'], ENT_XML1, 'UTF-8') . "</changefreq>\n";
        $xml .= '    <priority>' . number_format((float) $r['sitemap_priority'], 1) . "</priority>\n";
        $xml .= "  </url>\n";
    }
    $xml .= '</urlset>' . "\n";
    return $xml;
}

/** Write sitemap.xml to the web root. Returns [ok, message]. */
function seo_write_sitemap(): array
{
    $path = seo_webroot() . '/sitemap.xml';
    $ok   = @file_put_contents($path, seo_build_sitemap_xml()) !== false;
    return [$ok, $ok ? $path : 'Could not write ' . $path];
}
