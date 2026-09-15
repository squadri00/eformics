<?php
if (!function_exists('admin_url')) {
    http_response_code(403);
    exit('Direct access not allowed.');
}
$pageTitle = $pageTitle ?? 'Admin';
$navActive = $navActive ?? '';
?><!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<meta name="theme-color" content="#16131f">
<meta name="csrf-token" content="<?= e(csrf_token()) ?>">
<title><?= e($pageTitle) ?> — Eformics Admin</title>
<script>
/* Match the theme chosen anywhere on the site, before first paint. */
(function(){try{var t=localStorage.getItem('ef-theme');
if(t!=='light'&&t!=='dark'){t=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light';}
document.documentElement.setAttribute('data-theme',t);}catch(e){}})();
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('/assets/css/style.css') ?>">
<link rel="stylesheet" href="<?= asset('/admin/assets/admin.css') ?>">
</head>
<body class="adm" data-admin-base="<?= e(admin_url()) ?>">
<aside class="adm-side" id="admSide">
  <div class="adm-brand"><a href="<?= admin_url() ?>">Eformics <span>Admin</span></a></div>
  <nav>
    <span class="adm-nav-label">General</span>
    <a href="<?= admin_url() ?>" class="<?= $navActive === 'dashboard' ? 'active' : '' ?>"><?= adm_icon('grid') ?> Dashboard</a>
    <a href="<?= admin_url('settings.php') ?>" class="<?= $navActive === 'settings' ? 'active' : '' ?>"><?= adm_icon('home') ?> Site settings</a>
    <span class="adm-nav-label">Content</span>
    <a href="<?= admin_url('hero-media/') ?>" class="<?= $navActive === 'hero-media' ? 'active' : '' ?>"><?= adm_icon('image') ?> Page Hero Media</a>
    <a href="<?= admin_url('homepage-video.php') ?>" class="<?= $navActive === 'homepage-video' ? 'active' : '' ?>"><?= adm_icon('video') ?> Homepage Video</a>
    <a href="<?= admin_url('portfolio/') ?>" class="<?= $navActive === 'portfolio' ? 'active' : '' ?>"><?= adm_icon('map') ?> Portfolio</a>
    <a href="<?= admin_url('visual/ba-index.php') ?>" class="<?= $navActive === 'va-ba' ? 'active' : '' ?>"><?= adm_icon('image') ?> Visual: Before / After</a>
    <a href="<?= admin_url('visual/vid-index.php') ?>" class="<?= $navActive === 'va-vid' ? 'active' : '' ?>"><?= adm_icon('video') ?> Visual: Videos</a>
    <a href="<?= admin_url('social-links/') ?>" class="<?= $navActive === 'social-links' ? 'active' : '' ?>"><?= adm_icon('link') ?> Social Links</a>
    <a href="<?= admin_url('recodik-downloads.php') ?>" class="<?= $navActive === 'recodik-downloads' ? 'active' : '' ?>"><?= adm_icon('file') ?> Recodik Downloads</a>
    <span class="adm-nav-label">SEO</span>
    <a href="<?= admin_url('seo/global.php') ?>" class="<?= $navActive === 'seo-global' ? 'active' : '' ?>"><?= adm_icon('search') ?> Global defaults</a>
    <a href="<?= admin_url('seo/pages.php') ?>" class="<?= $navActive === 'seo-pages' ? 'active' : '' ?>"><?= adm_icon('grid') ?> Pages</a>
    <a href="<?= admin_url('seo/robots.php') ?>" class="<?= $navActive === 'seo-robots' ? 'active' : '' ?>"><?= adm_icon('file') ?> robots.txt</a>
    <a href="<?= admin_url('seo/sitemap.php') ?>" class="<?= $navActive === 'seo-sitemap' ? 'active' : '' ?>"><?= adm_icon('map') ?> Sitemap</a>
  </nav>
  <div class="adm-side-foot">
    <a href="<?= url('/') ?>" target="_blank" rel="noopener"><?= adm_icon('external') ?> View site</a>
  </div>
</aside>
<div class="adm-main">
  <header class="adm-topbar">
    <button class="adm-side-toggle" id="admSideToggle" type="button" aria-label="Menu">&#9776;</button>
    <div class="adm-topbar-title"><?= e($pageTitle) ?></div>
    <div class="adm-topbar-user">
      <button class="theme-toggle" type="button" aria-label="Switch colour theme" aria-pressed="false" title="Switch to dark mode">
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
      </button>
      <a href="<?= admin_url('logout.php') ?>" class="btn btn-outline btn-sm">Sign out</a>
    </div>
  </header>
  <main class="adm-body">
<?php foreach (flash_take() as $fl): ?>
    <div class="flash flash-<?= $fl['type'] ?>"><?= e($fl['msg']) ?></div>
<?php endforeach; ?>
