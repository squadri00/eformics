<?php
require __DIR__ . '/../_bootstrap.php';
require_login();
require_csrf();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $key = (string) ($_POST['key'] ?? '');
    $do  = $_POST['do'] ?? '';
    if ($do === 'publish' || $do === 'unpublish') {
        seo_page_set_published($key, $do === 'publish')
            ? flash('ok', $do === 'publish' ? 'Page enabled — it is live again.' : 'Page disabled — it now returns 404 and is hidden from all menus.')
            : flash('err', 'Could not update that page.');
        seo_write_sitemap();
    }
    redirect(admin_url('seo/pages.php'));
}

$rows = seo_all_pages();

$pageTitle = 'SEO — Pages';
$navActive = 'seo-pages';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1>SEO by page</h1>
  <a class="btn btn-outline btn-sm" href="<?= admin_url('seo/sitemap.php') ?>">Sitemap</a>
</div>

<p class="adm-help" style="margin-top:0;">Every page has its own SEO record. <strong>Disable</strong> a page to take it offline — it returns a 404 and every link to it disappears from the menus, footer and sitemap.</p>

<div class="adm-card adm-card-flush">
  <table class="adm-table">
    <thead>
      <tr><th>Page</th><th>Path</th><th>Status</th><th>Indexing</th><th>Custom</th><th class="adm-t-right">Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <?php
        $custom = [];
        if (trim($r['title']) !== '')            $custom[] = 'title';
        if (trim($r['meta_description']) !== '') $custom[] = 'description';
        if (trim($r['og_image']) !== '')         $custom[] = 'image';
        if (trim((string) $r['jsonld']) !== '')  $custom[] = 'schema';
        if (trim((string) $r['head_snippet']) !== '' || trim((string) $r['body_snippet']) !== '') $custom[] = 'code';
        $indexable = (int) $r['robots_index'] === 1;
        $published = (int) ($r['published'] ?? 1) === 1;
        $isHome    = $r['page_key'] === 'home';
      ?>
      <tr>
        <td><strong><?= e($r['page_label'] !== '' ? $r['page_label'] : $r['page_key']) ?></strong></td>
        <td class="adm-muted"><?= e($r['page_path']) ?></td>
        <td>
          <?php if ($isHome): ?>
            <span class="badge badge-green">Live</span>
          <?php elseif ($published): ?>
            <span class="badge badge-green">Live</span>
          <?php else: ?>
            <span class="badge" style="background:#fdecea;color:#a3271a;">Disabled</span>
          <?php endif; ?>
        </td>
        <td>
          <?php if ($indexable): ?>
            <span class="badge badge-green">Indexable</span>
          <?php else: ?>
            <span class="badge" style="background:#fdecea;color:#a3271a;">noindex</span>
          <?php endif; ?>
        </td>
        <td class="adm-muted"><?= $custom ? e(implode(', ', $custom)) : '&mdash;' ?></td>
        <td class="adm-t-right">
          <div class="adm-row-actions">
            <?php if (!$isHome): ?>
            <form method="post"<?= $published ? ' onsubmit="return confirm(\'Take this page offline? It will return a 404 and disappear from all menus.\');"' : '' ?>>
              <?= csrf_field() ?>
              <input type="hidden" name="key" value="<?= e($r['page_key']) ?>">
              <input type="hidden" name="do" value="<?= $published ? 'unpublish' : 'publish' ?>">
              <button class="btn btn-<?= $published ? 'danger' : 'outline' ?> btn-sm" type="submit"><?= $published ? 'Disable' : 'Enable' ?></button>
            </form>
            <?php endif; ?>
            <a class="btn btn-outline btn-sm" href="<?= admin_url('seo/page.php?key=' . rawurlencode($r['page_key'])) ?>">Edit SEO</a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/../partials/foot.php'; ?>
