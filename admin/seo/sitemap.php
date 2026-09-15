<?php
require __DIR__ . '/../_bootstrap.php';
require_login();
require_csrf();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    [$ok, $path] = seo_write_sitemap();
    flash($ok ? 'ok' : 'err', $ok ? 'sitemap.xml regenerated at the site root.' : $path);
    redirect(admin_url('seo/sitemap.php'));
}

$rows     = array_filter(seo_all_pages(), static fn ($r) => (int) $r['sitemap_include'] === 1);
$filePath = seo_webroot() . '/sitemap.xml';
$exists   = is_file($filePath);
$origin   = rtrim(SITE_ORIGIN, '/');

$pageTitle = 'SEO — Sitemap';
$navActive = 'seo-sitemap';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1>sitemap.xml</h1>
  <?php if ($exists): ?>
    <a class="btn btn-outline btn-sm" href="<?= url('/sitemap.xml') ?>" target="_blank" rel="noopener">View live file</a>
  <?php endif; ?>
</div>

<p class="adm-help" style="margin-top:0;">
  Lists every indexable page for search engines. It is rebuilt automatically whenever you save a page's SEO, and you can rebuild it here on demand.
  Submit <code><?= e($origin) ?>/sitemap.xml</code> once in Google Search Console and Bing Webmaster Tools.
</p>

<?php if (!$exists): ?>
  <div class="seo-warn">No <code>sitemap.xml</code> exists yet. Click "Regenerate now" to create it.</div>
<?php endif; ?>

<form method="post" style="margin-bottom:20px;">
  <?= csrf_field() ?>
  <button class="btn btn-primary" type="submit">Regenerate now</button>
</form>

<div class="adm-card adm-card-flush">
  <table class="adm-table">
    <thead><tr><th>URL</th><th>Priority</th><th>Frequency</th><th>Last modified</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= e($origin . ($r['page_path'] === '/' ? '/' : $r['page_path'])) ?></td>
        <td><?= e(number_format((float) $r['sitemap_priority'], 1)) ?></td>
        <td class="adm-muted"><?= e($r['sitemap_changefreq']) ?></td>
        <td class="adm-muted"><?= e(substr((string) $r['updated_at'], 0, 10)) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<p class="adm-help"><?= count($rows) ?> page<?= count($rows) === 1 ? '' : 's' ?> included. Change what's listed from each page's SEO screen.</p>

<?php require __DIR__ . '/../partials/foot.php'; ?>
