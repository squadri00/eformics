<?php
require __DIR__ . '/../_bootstrap.php';
require_login();

$id   = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$item = $id > 0 ? social_links_find($id) : null;

if ($id > 0 && !$item) {
    flash('err', 'That social link was not found.');
    redirect(admin_url('social-links/'));
}

$isEdit = $item !== null;
$errors = [];
$icons  = social_icons();

$form = [
    'label'      => $item['label']      ?? '',
    'url'        => $item['url']        ?? '',
    'icon'       => $item['icon']       ?? 'facebook',
    'sort_order' => (int) ($item['sort_order'] ?? (count(social_links_all()) + 1) * 10),
    'is_enabled' => (int) ($item['is_enabled'] ?? 1),
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_check()) {
        $errors[] = 'Your session expired — please try again.';
    } else {
        $form['label']      = trim((string) ($_POST['label'] ?? ''));
        $form['url']        = trim((string) ($_POST['url'] ?? ''));
        $form['icon']       = (string) ($_POST['icon'] ?? 'link');
        $form['sort_order'] = (int) ($_POST['sort_order'] ?? 0);
        $form['is_enabled'] = empty($_POST['is_enabled']) ? 0 : 1;

        if (!isset($icons[$form['icon']])) {
            $form['icon'] = 'link';
        }

        // Normalise the URL: allow #, mailto:, tel:, http(s):; otherwise assume https.
        if ($form['url'] !== '' && $form['url'] !== '#'
            && !preg_match('~^(https?:|mailto:|tel:)~i', $form['url'])) {
            $form['url'] = 'https://' . ltrim($form['url'], '/');
        }

        if ($form['label'] === '') {
            $errors[] = 'A label is required (e.g. “Facebook”).';
        }
        if ($form['url'] === '') {
            $errors[] = 'A URL is required.';
        }

        if (!$errors && !db()) {
            $errors[] = 'The database is not reachable. Check db-config.php.';
        }

        if (!$errors) {
            if ($isEdit) {
                social_links_update($id, $form);
                flash('ok', 'Link saved.');
            } else {
                social_links_create($form)
                    ? flash('ok', 'Link added.')
                    : $errors[] = 'Could not save. Please try again.';
            }
            if (!$errors) {
                redirect(admin_url('social-links/'));
            }
        }
    }
}

$pageTitle = $isEdit ? 'Edit social link' : 'Add social link';
$navActive = 'social-links';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1><?= $isEdit ? 'Edit social link' : 'Add social link' ?></h1>
  <a class="btn btn-outline btn-sm" href="<?= admin_url('social-links/') ?>">&larr; Back to list</a>
</div>

<?php foreach ($errors as $er): ?><div class="flash flash-err"><?= e($er) ?></div><?php endforeach; ?>

<form method="post" class="adm-form">
  <?= csrf_field() ?>
  <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>

  <div class="adm-card">
    <div class="adm-field">
      <label for="label">Label <span class="adm-req">*</span></label>
      <input type="text" id="label" name="label" maxlength="80" value="<?= e($form['label']) ?>" required>
      <p class="adm-help">Shown to screen readers as &ldquo;Eformics Systems on <em><?= e($form['label'] !== '' ? $form['label'] : 'Facebook') ?></em>&rdquo;.</p>
    </div>

    <div class="adm-field">
      <label for="url">URL <span class="adm-req">*</span></label>
      <input type="text" id="url" name="url" maxlength="500" value="<?= e($form['url']) ?>" placeholder="https://www.facebook.com/your-page" required>
      <p class="adm-help">Opens in a new tab. <code>https://</code> is added automatically if you leave it off.</p>
    </div>

    <div class="adm-field">
      <label>Icon</label>
      <div class="icon-pick">
        <?php foreach ($icons as $slug => $meta): ?>
          <label class="icon-pick-item">
            <input type="radio" name="icon" value="<?= e($slug) ?>" <?= $form['icon'] === $slug ? 'checked' : '' ?>>
            <span class="icon-pick-swatch"><?= social_icon_svg($slug) ?></span>
            <span class="icon-pick-name"><?= e($meta['label']) ?></span>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="adm-field" style="max-width:160px;">
      <label for="sort_order">Order</label>
      <input type="text" inputmode="numeric" id="sort_order" name="sort_order" value="<?= (int) $form['sort_order'] ?>">
      <p class="adm-help">Lower shows first.</p>
    </div>

    <label class="adm-check">
      <input type="checkbox" name="is_enabled" value="1" <?= $form['is_enabled'] ? 'checked' : '' ?>>
      Show this link in the footer
    </label>
  </div>

  <div class="adm-form-actions">
    <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Save changes' : 'Add link' ?></button>
    <a class="btn btn-outline" href="<?= admin_url('social-links/') ?>">Cancel</a>
  </div>
</form>

<?php require __DIR__ . '/../partials/foot.php'; ?>
