<?php
require __DIR__ . '/_bootstrap.php';
require_login();

$items   = hero_media_all();
$total   = count($items);
$images  = count(array_filter($items, static fn ($r) => $r['media_type'] === 'image'));
$videos  = count(array_filter($items, static fn ($r) => $r['media_type'] === 'video'));
$youtube = count(array_filter($items, static fn ($r) => $r['media_type'] === 'youtube'));

$pages     = page_hero_pages();
$activeByPage = [];
foreach ($items as $it) {
    if ((int) $it['is_active'] === 1) {
        $activeByPage[$it['page']] = $it;
    }
}

$socials   = social_links_all();
$socialsOn = count(array_filter($socials, static fn ($r) => (int) $r['is_enabled'] === 1));

$homeVideoOn = homepage_video() !== null;

$folio       = portfolio_all();
$folioShown  = count(array_filter($folio, static fn ($p) => (int) $p['is_enabled'] === 1));

$seoPages     = seo_all_pages();
$seoIndexable = count(array_filter($seoPages, static fn ($r) => (int) $r['robots_index'] === 1));
$seoSiteNoindex = seo_globals()['noindex_site'];

$pageTitle = 'Dashboard';
$navActive = 'dashboard';
require __DIR__ . '/partials/head.php';
?>

<div class="adm-page-head">
  <h1>Dashboard</h1>
  <a class="btn btn-primary btn-sm" href="<?= admin_url('hero-media/edit.php') ?>"><?= adm_icon('plus') ?> Add hero media</a>
</div>

<div class="adm-stats">
  <div class="adm-stat"><span class="adm-stat-label">Hero media items</span><span class="adm-stat-value"><?= $total ?></span></div>
  <div class="adm-stat"><span class="adm-stat-label">Images</span><span class="adm-stat-value"><?= $images ?></span></div>
  <div class="adm-stat"><span class="adm-stat-label">Video files</span><span class="adm-stat-value"><?= $videos ?></span></div>
  <div class="adm-stat"><span class="adm-stat-label">YouTube</span><span class="adm-stat-value"><?= $youtube ?></span></div>
  <div class="adm-stat"><span class="adm-stat-label">Pages with a live hero</span><span class="adm-stat-value"><?= count($activeByPage) ?></span></div>
  <div class="adm-stat"><span class="adm-stat-label">Homepage video</span><span class="adm-stat-value"><?= $homeVideoOn ? 'On' : 'Off' ?></span></div>
  <div class="adm-stat"><span class="adm-stat-label">Portfolio shown</span><span class="adm-stat-value"><?= $folioShown ?></span></div>
  <div class="adm-stat"><span class="adm-stat-label">Social links shown</span><span class="adm-stat-value"><?= $socialsOn ?></span></div>
  <div class="adm-stat"><span class="adm-stat-label">SEO: indexable pages</span><span class="adm-stat-value"><?= $seoSiteNoindex ? '0' : $seoIndexable ?><?= $seoSiteNoindex ? ' <small style="font-size:.85rem;color:#a3271a;font-weight:500;">(site noindex on)</small>' : '' ?></span></div>
</div>

<div class="adm-card adm-card-flush">
  <div class="adm-card-head" style="padding:22px 24px 0;">
    <h2>Hero media by page</h2>
    <a class="btn btn-outline btn-sm" href="<?= admin_url('hero-media/') ?>">Manage all</a>
  </div>
  <table class="adm-table">
    <thead><tr><th>Page</th><th>Live hero</th><th class="adm-t-right">Action</th></tr></thead>
    <tbody>
    <?php foreach ($pages as $key => $label): ?>
      <?php $a = $activeByPage[$key] ?? null; ?>
      <tr>
        <td><strong><?= e($label) ?></strong></td>
        <td>
          <?php if ($a): ?>
            <?= e($a['title']) ?> <span class="badge"><?= e(hero_media_type_label($a['media_type'])) ?></span>
          <?php else: ?>
            <span class="adm-muted">&mdash; none &mdash;</span>
          <?php endif; ?>
        </td>
        <td class="adm-t-right">
          <?php if ($a): ?>
            <a class="btn btn-outline btn-sm" href="<?= admin_url('hero-media/edit.php?id=' . (int) $a['id']) ?>">Edit</a>
          <?php else: ?>
            <a class="btn btn-outline btn-sm" href="<?= admin_url('hero-media/edit.php?page=' . e($key)) ?>">Add</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="adm-card">
  <div class="adm-card-head"><h2>Quick actions</h2></div>
  <div class="adm-quick">
    <a class="btn btn-primary" href="<?= admin_url('portfolio/edit.php') ?>">Add portfolio project</a>
    <a class="btn btn-outline" href="<?= admin_url('hero-media/edit.php') ?>">Add hero media</a>
    <a class="btn btn-outline" href="<?= admin_url('homepage-video.php') ?>">Homepage video</a>
    <a class="btn btn-outline" href="<?= admin_url('social-links/') ?>">Social links</a>
    <a class="btn btn-outline" href="<?= admin_url('seo/pages.php') ?>">SEO by page</a>
    <a class="btn btn-outline" href="<?= admin_url('seo/global.php') ?>">SEO defaults</a>
  </div>
</div>

<?php require __DIR__ . '/partials/foot.php'; ?>
