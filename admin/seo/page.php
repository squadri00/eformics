<?php
require __DIR__ . '/../_bootstrap.php';
require_login();

$key = (string) ($_GET['key'] ?? $_POST['key'] ?? '');
$row = seo_page($key);

if ($key === '' || !$row) {
    flash('err', 'That page was not found.');
    redirect(admin_url('seo/pages.php'));
}

$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_check()) {
        $errors[] = 'Your session expired — please try again.';
    } elseif (($_POST['action'] ?? '') === 'reset') {
        seo_page_reset($key);
        seo_write_sitemap();
        flash('ok', 'This page was reset to the defaults.');
        redirect(admin_url('seo/page.php?key=' . rawurlencode($key)));
    } else {
        $jsonld = trim((string) ($_POST['jsonld'] ?? ''));
        if ($jsonld !== '' && json_decode($jsonld) === null && json_last_error() !== JSON_ERROR_NONE) {
            $errors[] = 'The page JSON-LD is not valid JSON — fix it or clear the box.';
        }

        $d = [
            'page_label'         => trim((string) ($_POST['page_label'] ?? $row['page_label'])),
            'page_path'          => trim((string) ($_POST['page_path'] ?? $row['page_path'])),
            'published'          => $key === 'home' ? 1 : !empty($_POST['published']),
            'title'              => trim((string) ($_POST['title'] ?? '')),
            'meta_description'   => trim((string) ($_POST['meta_description'] ?? '')),
            'meta_keywords'      => trim((string) ($_POST['meta_keywords'] ?? '')),
            'canonical'          => trim((string) ($_POST['canonical'] ?? '')),
            'robots_index'       => !empty($_POST['robots_index']),
            'robots_follow'      => !empty($_POST['robots_follow']),
            'robots_advanced'    => trim((string) ($_POST['robots_advanced'] ?? '')),
            'og_title'           => trim((string) ($_POST['og_title'] ?? '')),
            'og_description'     => trim((string) ($_POST['og_description'] ?? '')),
            'og_image'           => trim((string) ($_POST['og_image'] ?? '')),
            'og_type'            => trim((string) ($_POST['og_type'] ?? '')),
            'tw_card'            => trim((string) ($_POST['tw_card'] ?? '')),
            'tw_title'           => trim((string) ($_POST['tw_title'] ?? '')),
            'tw_description'     => trim((string) ($_POST['tw_description'] ?? '')),
            'tw_image'           => trim((string) ($_POST['tw_image'] ?? '')),
            'jsonld'             => $jsonld,
            'head_snippet'       => (string) ($_POST['head_snippet'] ?? ''),
            'body_snippet'       => (string) ($_POST['body_snippet'] ?? ''),
            'sitemap_include'    => !empty($_POST['sitemap_include']),
            'sitemap_priority'   => (float) ($_POST['sitemap_priority'] ?? 0.5),
            'sitemap_changefreq' => trim((string) ($_POST['sitemap_changefreq'] ?? 'monthly')),
        ];

        if (!$errors && !db()) {
            $errors[] = 'The database is not reachable. Check db-config.php.';
        }
        if (!$errors) {
            seo_page_save($key, $d);
            seo_write_sitemap();
            flash('ok', 'SEO for this page was saved.');
            redirect(admin_url('seo/page.php?key=' . rawurlencode($key)));
        }
        $row = array_merge($row, [
            'published' => $d['published'] ? 1 : 0,
            'title' => $d['title'], 'meta_description' => $d['meta_description'], 'meta_keywords' => $d['meta_keywords'],
            'canonical' => $d['canonical'], 'robots_index' => $d['robots_index'] ? 1 : 0,
            'robots_follow' => $d['robots_follow'] ? 1 : 0, 'robots_advanced' => $d['robots_advanced'],
            'og_title' => $d['og_title'], 'og_description' => $d['og_description'], 'og_image' => $d['og_image'],
            'og_type' => $d['og_type'], 'tw_card' => $d['tw_card'], 'tw_title' => $d['tw_title'],
            'tw_description' => $d['tw_description'], 'tw_image' => $d['tw_image'], 'jsonld' => $d['jsonld'],
            'head_snippet' => $d['head_snippet'], 'body_snippet' => $d['body_snippet'],
            'sitemap_include' => $d['sitemap_include'] ? 1 : 0, 'sitemap_priority' => $d['sitemap_priority'],
            'sitemap_changefreq' => $d['sitemap_changefreq'],
        ]);
    }
}

/* Effective (fallback) values, so the editor can show what will render if a field is blank. */
$g        = seo_globals();
$effTitle = seo_first($row['title'], $row['page_label'] . ' | ' . SITE_NAME);
$effDesc  = seo_first($row['meta_description'], $g['default_description']);
$canonUrl = seo_abs_url(seo_first($row['canonical'], $row['page_path']));

$pageTitle = 'SEO — ' . ($row['page_label'] !== '' ? $row['page_label'] : $key);
$navActive = 'seo-pages';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1>SEO: <?= e($row['page_label'] !== '' ? $row['page_label'] : $key) ?></h1>
  <a class="btn btn-outline btn-sm" href="<?= admin_url('seo/pages.php') ?>">&larr; All pages</a>
</div>

<?php foreach ($errors as $er): ?><div class="flash flash-err"><?= e($er) ?></div><?php endforeach; ?>

<div class="adm-card">
  <h2>Google preview</h2>
  <div class="serp">
    <div class="serp-title" id="serpTitle"><?= e(seo_truncate($effTitle, 60)) ?></div>
    <div class="serp-url"><?= e(preg_replace('~^https?://~', '', $canonUrl)) ?></div>
    <div class="serp-desc" id="serpDesc"><?= e(seo_truncate($effDesc !== '' ? $effDesc : 'This page has no description yet.', 160)) ?></div>
  </div>
</div>

<form method="post" class="adm-form" id="seoPageForm">
  <?= csrf_field() ?>
  <input type="hidden" name="key" value="<?= e($key) ?>">
  <input type="hidden" name="action" value="save">

  <?php if ($key !== 'home'): ?>
  <div class="adm-card">
    <label class="adm-check">
      <input type="checkbox" name="published" value="1" <?= (int) ($row['published'] ?? 1) === 1 ? 'checked' : '' ?>>
      Page is enabled (live on the site)
    </label>
    <p class="adm-help">Unchecking this takes the page offline: it returns a 404 and every link to it is removed from the menus, footer and sitemap.</p>
  </div>
  <?php endif; ?>

  <div class="adm-card">
    <h2>Basics</h2>
    <div class="adm-field">
      <label for="title">Title tag</label>
      <input type="text" id="title" name="title" maxlength="255" value="<?= e($row['title']) ?>" data-count="titleCount" placeholder="<?= e($row['page_label'] . ' | ' . SITE_NAME) ?>">
      <p class="adm-help"><span class="seo-count" id="titleCount"></span> &middot; aim for 50&ndash;60 characters. Include your brand name.</p>
    </div>
    <div class="adm-field">
      <label for="meta_description">Meta description</label>
      <textarea id="meta_description" name="meta_description" rows="3" maxlength="400" data-count="descCount"><?= e($row['meta_description']) ?></textarea>
      <p class="adm-help"><span class="seo-count" id="descCount"></span> &middot; aim for 120&ndash;160 characters.</p>
    </div>
    <div class="adm-field">
      <label for="meta_keywords">Meta keywords <span class="adm-muted">(optional)</span></label>
      <input type="text" id="meta_keywords" name="meta_keywords" maxlength="255" value="<?= e($row['meta_keywords']) ?>">
      <p class="adm-help">Google ignores this tag. Only fill it if a specific tool you use needs it.</p>
    </div>
  </div>

  <div class="adm-card">
    <h2>Indexing &amp; canonical</h2>
    <div class="adm-seg">
      <label><input type="checkbox" name="robots_index" value="1" <?= (int) $row['robots_index'] === 1 ? 'checked' : '' ?>> Allow indexing</label>
      <label><input type="checkbox" name="robots_follow" value="1" <?= (int) $row['robots_follow'] === 1 ? 'checked' : '' ?>> Follow links</label>
    </div>
    <p class="adm-help"><?= $g['noindex_site'] ? '<strong>Note:</strong> the site-wide "discourage indexing" switch is on, so this page is noindex regardless.' : 'Unchecking "Allow indexing" keeps this page out of search results.' ?></p>
    <div class="adm-field" style="max-width:420px;margin-top:14px;">
      <label for="robots_advanced">Extra robots directives <span class="adm-muted">(optional)</span></label>
      <input type="text" id="robots_advanced" name="robots_advanced" value="<?= e($row['robots_advanced']) ?>" placeholder="max-image-preview:large, noarchive">
    </div>
    <div class="adm-field" style="max-width:520px;">
      <label for="canonical">Canonical URL override <span class="adm-muted">(optional)</span></label>
      <input type="text" id="canonical" name="canonical" value="<?= e($row['canonical']) ?>" placeholder="<?= e($canonUrl) ?>">
      <p class="adm-help">Leave blank to use <code><?= e($canonUrl) ?></code>. Set this only to point at a different URL.</p>
    </div>
  </div>

  <div class="adm-card">
    <h2>Social sharing</h2>
    <div class="adm-field">
      <label for="og_title">Open Graph title <span class="adm-muted">(optional)</span></label>
      <input type="text" id="og_title" name="og_title" maxlength="255" value="<?= e($row['og_title']) ?>" placeholder="Falls back to the title tag">
    </div>
    <div class="adm-field">
      <label for="og_description">Open Graph description <span class="adm-muted">(optional)</span></label>
      <textarea id="og_description" name="og_description" rows="2" maxlength="400"><?= e($row['og_description']) ?></textarea>
    </div>
    <div class="adm-field">
      <label for="og_image">Share image <span class="adm-muted">(optional)</span></label>
      <?= adm_upload_field('og_image', $row['og_image'], [
        'kind' => 'image', 'id' => 'og_image',
        'placeholder' => $g['default_og_image'] !== '' ? seo_abs_url($g['default_og_image']) : url('/assets/uploads/social-card.png'),
        'help' => '1200&times;630 works best. Used for Facebook, LinkedIn, WhatsApp and Twitter/X. Falls back to the global default if left blank.',
      ]) ?>
    </div>
    <div class="adm-field" style="max-width:240px;">
      <label for="og_type">og:type</label>
      <input type="text" id="og_type" name="og_type" value="<?= e($row['og_type']) ?>" placeholder="website">
    </div>
    <hr style="border:none;border-top:1px solid var(--line);margin:6px 0 18px;">
    <div class="adm-field" style="max-width:280px;">
      <label for="tw_card">Twitter card type <span class="adm-muted">(optional)</span></label>
      <input type="text" id="tw_card" name="tw_card" value="<?= e($row['tw_card']) ?>" placeholder="<?= e($g['twitter_card']) ?>">
    </div>
    <div class="adm-field">
      <label for="tw_title">Twitter title <span class="adm-muted">(optional)</span></label>
      <input type="text" id="tw_title" name="tw_title" maxlength="255" value="<?= e($row['tw_title']) ?>" placeholder="Falls back to the Open Graph title">
    </div>
    <div class="adm-field">
      <label for="tw_description">Twitter description <span class="adm-muted">(optional)</span></label>
      <textarea id="tw_description" name="tw_description" rows="2" maxlength="400"><?= e($row['tw_description']) ?></textarea>
    </div>
    <div class="adm-field">
      <label for="tw_image">Twitter image <span class="adm-muted">(optional)</span></label>
      <?= adm_upload_field('tw_image', $row['tw_image'], [
        'kind' => 'image', 'id' => 'tw_image',
        'placeholder' => 'Falls back to the share image',
      ]) ?>
    </div>
  </div>

  <div class="adm-card">
    <h2>Structured data</h2>
    <div class="adm-field">
      <label for="jsonld">Page JSON-LD <span class="adm-muted">(optional)</span></label>
      <textarea id="jsonld" name="jsonld" rows="10" class="code"><?= e((string) $row['jsonld']) ?></textarea>
      <p class="adm-help">Added on top of the site-wide Organization block. Use for Product, FAQPage, BreadcrumbList, Service, etc.</p>
    </div>
  </div>

  <div class="adm-card">
    <h2>Custom code — this page only</h2>
    <div class="seo-warn">Added to this page exactly as typed. Paste code only from sources you trust.</div>
    <div class="adm-field">
      <label for="head_snippet">Before <code>&lt;/head&gt;</code></label>
      <textarea id="head_snippet" name="head_snippet" rows="5" class="code"><?= e((string) $row['head_snippet']) ?></textarea>
    </div>
    <div class="adm-field">
      <label for="body_snippet">Before <code>&lt;/body&gt;</code></label>
      <textarea id="body_snippet" name="body_snippet" rows="5" class="code"><?= e((string) $row['body_snippet']) ?></textarea>
    </div>
  </div>

  <div class="adm-card">
    <h2>Sitemap</h2>
    <label class="adm-check">
      <input type="checkbox" name="sitemap_include" value="1" <?= (int) $row['sitemap_include'] === 1 ? 'checked' : '' ?>>
      Include this page in sitemap.xml
    </label>
    <div class="adm-seg" style="margin-top:16px;gap:26px;">
      <div class="adm-field" style="margin:0;max-width:120px;">
        <label for="sitemap_priority">Priority</label>
        <input type="text" inputmode="decimal" id="sitemap_priority" name="sitemap_priority" value="<?= e(number_format((float) $row['sitemap_priority'], 1)) ?>">
      </div>
      <div class="adm-field" style="margin:0;max-width:180px;">
        <label for="sitemap_changefreq">Change frequency</label>
        <select id="sitemap_changefreq" name="sitemap_changefreq">
          <?php foreach (['always','hourly','daily','weekly','monthly','yearly','never'] as $cf): ?>
            <option value="<?= $cf ?>"<?= $row['sitemap_changefreq'] === $cf ? ' selected' : '' ?>><?= $cf ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <div class="adm-form-actions">
    <button class="btn btn-primary" type="submit">Save SEO</button>
    <a class="btn btn-outline" href="<?= e($canonUrl) ?>" target="_blank" rel="noopener">Open page</a>
  </div>
</form>

<form method="post" style="margin-top:6px;" onsubmit="return confirm('Reset every SEO field on this page back to the defaults?');">
  <?= csrf_field() ?>
  <input type="hidden" name="key" value="<?= e($key) ?>">
  <input type="hidden" name="action" value="reset">
  <button class="btn btn-danger btn-sm" type="submit">Reset this page</button>
</form>

<script>
(function () {
  var brand = <?= json_encode(' | ' . SITE_NAME) ?>;
  var pageLabel = <?= json_encode($row['page_label']) ?>;
  var titleEl = document.getElementById('title');
  var descEl  = document.getElementById('meta_description');
  var sTitle  = document.getElementById('serpTitle');
  var sDesc   = document.getElementById('serpDesc');

  function clamp(s, n) { s = (s || '').replace(/\s+/g, ' ').trim(); return s.length <= n ? s : s.slice(0, n - 1).trimEnd() + '…'; }
  function count(el, target, lo, hi) {
    var n = (el.value || '').length;
    target.textContent = n + ' characters';
    target.classList.toggle('over', n > hi);
  }
  function refresh() {
    var t = titleEl.value.trim() || (pageLabel + brand);
    var d = descEl.value.trim() || 'This page has no description yet.';
    sTitle.textContent = clamp(t, 60);
    sDesc.textContent  = clamp(d, 160);
    count(titleEl, document.getElementById('titleCount'), 50, 60);
    count(descEl,  document.getElementById('descCount'),  120, 160);
  }
  titleEl.addEventListener('input', refresh);
  descEl.addEventListener('input', refresh);
  refresh();
})();
</script>

<?php require __DIR__ . '/../partials/foot.php'; ?>
