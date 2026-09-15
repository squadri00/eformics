<?php
require __DIR__ . '/../_bootstrap.php';
require_login();
require_csrf();

$keys = [
    'seo_default_description', 'seo_default_og_image', 'seo_og_site_name', 'seo_og_locale',
    'seo_twitter_site', 'seo_twitter_default_card', 'seo_google_verification', 'seo_bing_verification',
    'seo_favicon', 'seo_apple_icon', 'seo_theme_color', 'seo_noindex_site',
    'seo_org_jsonld', 'seo_head_snippet', 'seo_body_snippet',
];

$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $jsonld = trim((string) ($_POST['seo_org_jsonld'] ?? ''));
    if ($jsonld !== '' && json_decode($jsonld) === null && json_last_error() !== JSON_ERROR_NONE) {
        $errors[] = 'The Organization JSON-LD is not valid JSON — fix it or clear the box.';
    }

    if (!$errors) {
        set_setting('seo_default_description',  trim((string) ($_POST['seo_default_description'] ?? '')));
        set_setting('seo_default_og_image',     trim((string) ($_POST['seo_default_og_image'] ?? '')));
        set_setting('seo_og_site_name',         trim((string) ($_POST['seo_og_site_name'] ?? 'Eformics Systems')));
        set_setting('seo_og_locale',            trim((string) ($_POST['seo_og_locale'] ?? 'en_CA')));
        set_setting('seo_twitter_site',         trim((string) ($_POST['seo_twitter_site'] ?? '')));
        set_setting('seo_twitter_default_card', ($_POST['seo_twitter_default_card'] ?? 'summary_large_image') === 'summary' ? 'summary' : 'summary_large_image');
        set_setting('seo_google_verification',  trim((string) ($_POST['seo_google_verification'] ?? '')));
        set_setting('seo_bing_verification',    trim((string) ($_POST['seo_bing_verification'] ?? '')));
        set_setting('seo_favicon',              trim((string) ($_POST['seo_favicon'] ?? '')));
        set_setting('seo_apple_icon',           trim((string) ($_POST['seo_apple_icon'] ?? '')));
        set_setting('seo_theme_color',          trim((string) ($_POST['seo_theme_color'] ?? '#393193')));
        set_setting('seo_noindex_site',         empty($_POST['seo_noindex_site']) ? '0' : '1');
        set_setting('seo_org_jsonld',           $jsonld);
        set_setting('seo_head_snippet',         (string) ($_POST['seo_head_snippet'] ?? ''));
        set_setting('seo_body_snippet',         (string) ($_POST['seo_body_snippet'] ?? ''));
        flash('ok', 'Global SEO settings saved.');
        redirect(admin_url('seo/global.php'));
    }
}

$v = static fn (string $k, string $d = '') => setting($k, $d);

$pageTitle = 'SEO — Global defaults';
$navActive = 'seo-global';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1>Global SEO defaults</h1>
  <a class="btn btn-outline btn-sm" href="<?= url('/') ?>" target="_blank" rel="noopener">View site</a>
</div>

<?php foreach ($errors as $er): ?><div class="flash flash-err"><?= e($er) ?></div><?php endforeach; ?>

<p class="adm-help" style="margin-top:0;">These apply to every page. A page's own SEO settings, and the text set in its file, always win over these.</p>

<form method="post" class="adm-form" id="seoGlobalForm">
  <?= csrf_field() ?>

  <div class="adm-card">
    <h2>Search &amp; sharing defaults</h2>
    <div class="adm-field">
      <label for="seo_default_description">Fallback meta description</label>
      <textarea id="seo_default_description" name="seo_default_description" rows="2" maxlength="320"><?= e($v('seo_default_description')) ?></textarea>
      <p class="adm-help">Used only when a page has no description of its own.</p>
    </div>
    <div class="adm-field">
      <label for="seo_default_og_image">Default social image</label>
      <?= adm_upload_field('seo_default_og_image', $v('seo_default_og_image'), [
        'kind' => 'image',
        'placeholder' => url('/assets/uploads/social-card.png'),
        'help' => 'Shown when a link is shared and the page has no image. Ideal size 1200&times;630. Paste a URL or upload a file.',
      ]) ?>
    </div>
    <div class="adm-field" style="max-width:320px;">
      <label for="seo_og_site_name">og:site_name</label>
      <input type="text" id="seo_og_site_name" name="seo_og_site_name" value="<?= e($v('seo_og_site_name', 'Eformics Systems')) ?>">
    </div>
    <div class="adm-field" style="max-width:200px;">
      <label for="seo_og_locale">og:locale</label>
      <input type="text" id="seo_og_locale" name="seo_og_locale" value="<?= e($v('seo_og_locale', 'en_CA')) ?>" placeholder="en_CA">
    </div>
    <div class="adm-field" style="max-width:260px;">
      <label for="seo_twitter_site">Twitter / X handle</label>
      <input type="text" id="seo_twitter_site" name="seo_twitter_site" value="<?= e($v('seo_twitter_site')) ?>" placeholder="@eformics">
    </div>
    <div class="adm-field" style="max-width:280px;">
      <label for="seo_twitter_default_card">Default Twitter card</label>
      <select id="seo_twitter_default_card" name="seo_twitter_default_card">
        <?php $tc = $v('seo_twitter_default_card', 'summary_large_image'); ?>
        <option value="summary_large_image"<?= $tc !== 'summary' ? ' selected' : '' ?>>summary_large_image</option>
        <option value="summary"<?= $tc === 'summary' ? ' selected' : '' ?>>summary</option>
      </select>
    </div>
  </div>

  <div class="adm-card">
    <h2>Indexing</h2>
    <label class="adm-check">
      <input type="checkbox" name="seo_noindex_site" value="1" <?= $v('seo_noindex_site') === '1' ? 'checked' : '' ?>>
      Discourage search engines from indexing the whole site
    </label>
    <p class="adm-help">Adds <code>noindex</code> to every page. Use this while the site is on a staging URL, then turn it off at launch.</p>
  </div>

  <div class="adm-card">
    <h2>Site verification &amp; icons</h2>
    <div class="adm-field" style="max-width:420px;">
      <label for="seo_google_verification">Google Search Console verification code</label>
      <input type="text" id="seo_google_verification" name="seo_google_verification" value="<?= e($v('seo_google_verification')) ?>">
      <p class="adm-help">The value from the "HTML tag" method. Renders <code>&lt;meta name="google-site-verification"&gt;</code>.</p>
    </div>
    <div class="adm-field" style="max-width:420px;">
      <label for="seo_bing_verification">Bing Webmaster verification code</label>
      <input type="text" id="seo_bing_verification" name="seo_bing_verification" value="<?= e($v('seo_bing_verification')) ?>">
    </div>
    <div class="adm-field">
      <label for="seo_favicon">Favicon</label>
      <?= adm_upload_field('seo_favicon', $v('seo_favicon'), [
        'kind' => 'image',
        'placeholder' => url('/assets/uploads/favicon.png'),
        'help' => 'A 32&times;32 or 48&times;48 PNG works well. Paste a URL or upload.',
      ]) ?>
    </div>
    <div class="adm-field">
      <label for="seo_apple_icon">Apple touch icon</label>
      <?= adm_upload_field('seo_apple_icon', $v('seo_apple_icon'), [
        'kind' => 'image',
        'placeholder' => url('/assets/uploads/apple-touch-icon.png'),
        'help' => '180&times;180 PNG. Used when someone adds the site to an iPhone home screen.',
      ]) ?>
    </div>
    <div class="adm-field" style="max-width:180px;">
      <label for="seo_theme_color">Theme colour</label>
      <input type="text" id="seo_theme_color" name="seo_theme_color" value="<?= e($v('seo_theme_color', '#393193')) ?>">
    </div>
  </div>

  <div class="adm-card">
    <h2>Structured data (Organization)</h2>
    <div class="adm-field">
      <label for="seo_org_jsonld">Organization JSON-LD</label>
      <textarea id="seo_org_jsonld" name="seo_org_jsonld" rows="12" class="code"><?= e($v('seo_org_jsonld')) ?></textarea>
      <p class="adm-help">Rendered on every page. Leave blank to use an automatic block built from your name, logo, phone, email, address and social links.
        <button type="button" class="btn btn-outline btn-sm" id="genOrg" style="margin-left:8px;">Generate default</button></p>
    </div>
  </div>

  <div class="adm-card">
    <h2>Custom code</h2>
    <div class="seo-warn">This code is added to every page exactly as typed. Only paste code from sources you trust (Google Analytics, Tag Manager, Search Console, and similar).</div>
    <div class="adm-field">
      <label for="seo_head_snippet">Before <code>&lt;/head&gt;</code> — every page</label>
      <textarea id="seo_head_snippet" name="seo_head_snippet" rows="6" class="code"><?= e($v('seo_head_snippet')) ?></textarea>
      <p class="adm-help">Analytics tags, Search Console script verification, preload hints, extra meta.</p>
    </div>
    <div class="adm-field">
      <label for="seo_body_snippet">Before <code>&lt;/body&gt;</code> — every page</label>
      <textarea id="seo_body_snippet" name="seo_body_snippet" rows="6" class="code"><?= e($v('seo_body_snippet')) ?></textarea>
      <p class="adm-help">Tag Manager <code>&lt;noscript&gt;</code>, chat widgets, deferred scripts.</p>
    </div>
  </div>

  <div class="adm-form-actions">
    <button class="btn btn-primary" type="submit">Save global settings</button>
  </div>
</form>

<script>
document.getElementById('genOrg').addEventListener('click', function () {
  var box = document.getElementById('seo_org_jsonld');
  if (box.value.trim() && !confirm('Replace the current JSON-LD with a fresh default?')) return;
  box.value = <?= json_encode(seo_default_org_jsonld()) ?>;
});
</script>

<?php require __DIR__ . '/../partials/foot.php'; ?>
