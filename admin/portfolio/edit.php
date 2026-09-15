<?php
require __DIR__ . '/../_bootstrap.php';
require_login();

$id   = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$item = $id > 0 ? portfolio_find($id) : null;

if ($id > 0 && !$item) {
    flash('err', 'That project was not found.');
    redirect(admin_url('portfolio/'));
}

$isEdit = $item !== null;
$errors = [];

$form = [
    'title'       => $item['title']       ?? '',
    'image_path'  => $item['image_path']  ?? '',
    'image_alt'   => $item['image_alt']   ?? '',
    'website_url' => $item['website_url']  ?? '',
    'info'        => (string) ($item['info'] ?? ''),
    'client_name' => $item['client_name'] ?? '',
    'is_featured' => (int) ($item['is_featured'] ?? 0),
    'is_enabled'  => (int) ($item['is_enabled'] ?? 1),
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_check()) {
        $errors[] = 'Your session expired — please try again.';
    } else {
        $form['title']       = trim((string) ($_POST['title'] ?? ''));
        $form['image_path']  = trim((string) ($_POST['image_path'] ?? ''));
        $form['image_alt']   = trim((string) ($_POST['image_alt'] ?? ''));
        $form['website_url'] = trim((string) ($_POST['website_url'] ?? ''));
        $form['info']        = trim((string) ($_POST['info'] ?? ''));
        $form['client_name'] = trim((string) ($_POST['client_name'] ?? ''));
        $form['is_featured'] = empty($_POST['is_featured']) ? 0 : 1;
        $form['is_enabled']  = empty($_POST['is_enabled']) ? 0 : 1;

        [$up, $upErr] = admin_upload('image_file', 'image');
        if ($upErr) { $errors[] = 'Image ' . $upErr; } elseif ($up) { $form['image_path'] = $up; }

        if ($form['website_url'] !== '' && !preg_match('~^(https?:)?//~i', $form['website_url'])) {
            $form['website_url'] = 'https://' . ltrim($form['website_url'], '/');
        }

        if ($form['title'] === '') {
            $errors[] = 'A title is required.';
        }
        if ($form['image_path'] === '') {
            $errors[] = 'Pick an image from the folder, upload one, or paste a URL.';
        }

        if (!$errors && !db()) {
            $errors[] = 'The database is not reachable. Check db-config.php.';
        }
        if (!$errors) {
            if ($isEdit) {
                portfolio_update($id, $form);
                flash('ok', 'Project saved.');
            } else {
                portfolio_create($form) ? flash('ok', 'Project added.') : $errors[] = 'Could not save. Please try again.';
            }
            if (!$errors) {
                redirect(admin_url('portfolio/'));
            }
        }
    }
}

$folder = portfolio_folder_images();

$pageTitle = $isEdit ? 'Edit project' : 'Add project';
$navActive = 'portfolio';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1><?= $isEdit ? 'Edit project' : 'Add project' ?></h1>
  <a class="btn btn-outline btn-sm" href="<?= admin_url('portfolio/') ?>">&larr; Back</a>
</div>

<?php foreach ($errors as $er): ?><div class="flash flash-err"><?= e($er) ?></div><?php endforeach; ?>

<form method="post" enctype="multipart/form-data" class="adm-form" id="folioForm">
  <?= csrf_field() ?>
  <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>

  <div class="adm-card">
    <div class="adm-field">
      <label for="title">Title <span class="adm-req">*</span></label>
      <input type="text" id="title" name="title" maxlength="150" value="<?= e($form['title']) ?>" required>
    </div>
    <div class="adm-field" style="max-width:420px;">
      <label for="client_name">Client name</label>
      <input type="text" id="client_name" name="client_name" maxlength="150" value="<?= e($form['client_name']) ?>">
    </div>
    <div class="adm-field">
      <label for="website_url">Website URL <span class="adm-muted">(opens in a new tab)</span></label>
      <input type="text" id="website_url" name="website_url" maxlength="500" value="<?= e($form['website_url']) ?>" placeholder="https://clientsite.com">
    </div>
    <div class="adm-field">
      <label for="info">Project info</label>
      <textarea id="info" name="info" rows="4" maxlength="2000"><?= e($form['info']) ?></textarea>
      <p class="adm-help">Shown on the card. Keep it to a couple of sentences.</p>
    </div>
  </div>

  <div class="adm-card">
    <h2>Image</h2>
    <div class="adm-field">
      <label for="image_path">Image URL</label>
      <?= adm_upload_field('image_path', $form['image_path'], [
        'kind' => 'image', 'id' => 'image_path',
        'placeholder' => url('/images/portfolio/example.webp'),
        'help' => 'Pick from the folder below, upload a new file, or paste any URL.',
      ]) ?>
    </div>
    <div class="adm-field">
      <label for="image_alt">Alt text</label>
      <input type="text" id="image_alt" name="image_alt" maxlength="255" value="<?= e($form['image_alt']) ?>" placeholder="Describes the screenshot for screen readers">
    </div>

    <div id="folioPreview" class="folio-edit-preview"<?= $form['image_path'] === '' ? ' hidden' : '' ?>>
      <img src="<?= e($form['image_path']) ?>" alt="">
    </div>

    <?php if ($folder): ?>
    <details class="folio-picker">
      <summary>Browse images/portfolio/ (<?= count($folder) ?> files)</summary>
      <div class="folio-picker-grid">
        <?php foreach ($folder as $name => $urlPath): ?>
          <button type="button" class="folio-picker-item<?= $form['image_path'] === $urlPath ? ' is-picked' : '' ?>" data-url="<?= e($urlPath) ?>" title="<?= e($name) ?>">
            <img src="<?= e($urlPath) ?>" alt="" loading="lazy">
          </button>
        <?php endforeach; ?>
      </div>
    </details>
    <?php else: ?>
    <p class="adm-help">No images found in <code>images/portfolio/</code>. Upload above, or add files to that folder.</p>
    <?php endif; ?>
  </div>

  <div class="adm-card">
    <label class="adm-check"><input type="checkbox" name="is_featured" value="1" <?= $form['is_featured'] ? 'checked' : '' ?>> Featured <span class="adm-muted">&mdash; appears first on the site</span></label>
    <label class="adm-check" style="margin-top:12px;"><input type="checkbox" name="is_enabled" value="1" <?= $form['is_enabled'] ? 'checked' : '' ?>> Enabled <span class="adm-muted">&mdash; shown on the portfolio page</span></label>
  </div>

  <div class="adm-form-actions">
    <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Save project' : 'Add project' ?></button>
    <a class="btn btn-outline" href="<?= admin_url('portfolio/') ?>">Cancel</a>
  </div>
</form>

<script>
(function () {
  var input   = document.getElementById('image_path');
  var preview = document.getElementById('folioPreview');
  var pImg    = preview.querySelector('img');

  function show(url) {
    if (url) { pImg.src = url; preview.hidden = false; } else { preview.hidden = true; }
  }
  input.addEventListener('input', function () { show(input.value.trim()); });

  document.querySelectorAll('.folio-picker-item').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var url = btn.getAttribute('data-url');
      input.value = url;
      input.dispatchEvent(new Event('input', { bubbles: true }));
      document.querySelectorAll('.folio-picker-item.is-picked').forEach(function (x) { x.classList.remove('is-picked'); });
      btn.classList.add('is-picked');
      var alt = document.getElementById('image_alt');
      if (!alt.value.trim()) {
        alt.value = (btn.getAttribute('title') || '').replace(/\.[a-z0-9]+$/i, '').replace(/[-_]+/g, ' ').trim() + ' website';
      }
    });
  });
})();
</script>

<?php require __DIR__ . '/../partials/foot.php'; ?>
