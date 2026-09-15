<?php
require_once __DIR__ . '/config.php';

/* Per-page values. Set $page in the page file BEFORE requiring this partial:
     $page = [
       'title'       => 'Page Title | Eformics Systems',
       'description' => '…',
       'canonical'   => '/path.php',   // path only; combined with SITE_ORIGIN
       'section'     => 'products',    // home|products|services|about|contact
     ];
*/
$page = array_merge([
    'title'       => SITE_NAME,
    'description' => '',
    'canonical'   => '/',
    'section'     => '',
], $page ?? []);

/* Stop here with a 404 if this page has been disabled in the admin. */
seo_guard_page(seo_current_key());
?><!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>
/* Set the theme before first paint so there is no flash. */
(function(){try{var t=localStorage.getItem('ef-theme');
if(t!=='light'&&t!=='dark'){t=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light';}
document.documentElement.setAttribute('data-theme',t);}catch(e){}})();
</script>
<?= seo_head($page) ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('/assets/css/style.css') ?>">
</head>
<body>
<?php require __DIR__ . '/header.php'; ?>
