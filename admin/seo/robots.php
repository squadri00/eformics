<?php
require __DIR__ . '/../_bootstrap.php';
require_login();
require_csrf();

$writeMsg = null;
$writeOk  = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (($_POST['action'] ?? '') === 'restore') {
        $content = seo_default_robots();
        set_setting('seo_robots_txt', $content);
    } else {
        $content = (string) ($_POST['content'] ?? '');
        set_setting('seo_robots_txt', $content);
    }
    [$writeOk, $path] = seo_write_robots(setting('seo_robots_txt', ''));
    flash($writeOk ? 'ok' : 'err', $writeOk ? 'robots.txt saved and written to the site root.' : $path);
    redirect(admin_url('seo/robots.php'));
}

$content = setting('seo_robots_txt', '');
if (trim($content) === '') {
    $content = seo_default_robots();
}
$filePath   = seo_webroot() . '/robots.txt';
$fileExists = is_file($filePath);

$pageTitle = 'SEO — robots.txt';
$navActive = 'seo-robots';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1>robots.txt</h1>
  <?php if ($fileExists): ?>
    <a class="btn btn-outline btn-sm" href="<?= url('/robots.txt') ?>" target="_blank" rel="noopener">View live file</a>
  <?php endif; ?>
</div>

<p class="adm-help" style="margin-top:0;">Saving writes <code><?= e($filePath) ?></code>. Search engines read this to learn which paths to crawl. The <code>Sitemap:</code> line points crawlers at your sitemap.</p>

<?php if (!$fileExists): ?>
  <div class="seo-warn">No <code>robots.txt</code> exists yet. Save below to create it.</div>
<?php endif; ?>

<form method="post" class="adm-form">
  <?= csrf_field() ?>
  <div class="adm-card">
    <div class="adm-field">
      <label for="content">Contents</label>
      <textarea id="content" name="content" rows="16" class="code"><?= e($content) ?></textarea>
    </div>
    <div class="adm-form-actions">
      <button class="btn btn-primary" type="submit" name="action" value="save">Save robots.txt</button>
      <button class="btn btn-outline" type="submit" name="action" value="restore" onclick="return confirm('Replace the contents with the recommended default?');">Restore default</button>
    </div>
  </div>
</form>

<?php require __DIR__ . '/../partials/foot.php'; ?>
