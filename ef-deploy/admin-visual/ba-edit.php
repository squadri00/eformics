<?php
require __DIR__ . '/../_bootstrap.php';
require_login();

$id   = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$item = $id > 0 ? va_ba_find($id) : null;

if ($id > 0 && !$item) {
    flash('err', 'That pair was not found.');
    redirect(admin_url('visual/ba-index.php'));
}

$isEdit = $item !== null;
$errors = [];
$form = [
    'title'       => $item['title']       ?? '',
    'before_path' => $item['before_path'] ?? '',
    'after_path'  => $item['after_path']  ?? '',
    'before_alt'  => $item['before_alt']  ?? '',
    'after_alt'   => $item['after_alt']   ?? '',
    'is_enabled'  => (int) ($item['is_enabled'] ?? 1),
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_check()) {
        $errors[] = 'Your session expired — please try again.';
    } else {
        $form['title']       = trim((string) ($_POST['title'] ?? ''));
        $form['before_path'] = trim((string) ($_POST['before_path'] ?? ''));
        $form['after_path']  = trim((string) ($_POST['after_path'] ?? ''));
        $form['before_alt']  = trim((string) ($_POST['before_alt'] ?? ''));
        $form['after_alt']   = trim((string) ($_POST['after_alt'] ?? ''));
        $form['is_enabled']  = empty($_POST['is_enabled']) ? 0 : 1;

        [$b, $bErr] = admin_upload('before_file', 'image');
        if ($bErr) { $errors[] = 'Before image ' . $bErr; } elseif ($b) { $form['before_path'] = $b; }
        [$a, $aErr] = admin_upload('after_file', 'image');
        if ($aErr) { $errors[] = 'After image ' . $aErr; } elseif ($a) { $form['after_path'] = $a; }

        if ($form['title'] === '')       { $errors[] = 'A title is required.'; }
        if ($form['before_path'] === '') { $errors[] = 'A "before" image is required.'; }
        if ($form['after_path'] === '')  { $errors[] = 'An "after" image is required.'; }
        if (!$errors && !db())           { $errors[] = 'The database is not reachable.'; }

        if (!$errors) {
            $isEdit ? va_ba_update($id, $form) : ($ok = va_ba_create($form));
            if ($isEdit || !empty($ok)) {
                flash('ok', $isEdit ? 'Pair saved.' : 'Pair added.');
                redirect(admin_url('visual/ba-index.php'));
            }
            $errors[] = 'Could not save. Please try again.';
        }
    }
}

$pageTitle = $isEdit ? 'Edit pair' : 'Add pair';
$navActive = 'va-ba';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1><?= $isEdit ? 'Edit before / after pair' : 'Add before / after pair' ?></h1>
  <a class="btn btn-outline btn-sm" href="<?= admin_url('visual/ba-index.php') ?>">&larr; Back</a>
</div>

<?php foreach ($errors as $er): ?><div class="flash flash-err"><?= e($er) ?></div><?php endforeach; ?>

<form method="post" enctype="multipart/form-data" class="adm-form">
  <?= csrf_field() ?>
  <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>

  <div class="adm-card">
    <div class="adm-field">
      <label for="title">Title <span class="adm-req">*</span> <span class="adm-muted">(shown over the images)</span></label>
      <input type="text" id="title" name="title" maxlength="150" value="<?= e($form['title']) ?>" required placeholder="e.g. Margherita pizza">
    </div>
  </div>

  <div class="adm-card">
    <h2>Before image <span class="adm-req">*</span></h2>
    <div class="adm-field">
      <?= adm_upload_field('before_path', $form['before_path'], ['kind' => 'image', 'id' => 'before_path', 'help' => 'Upload the original photo, or paste a URL.']) ?>
    </div>
    <div class="adm-field">
      <label for="before_alt">Alt text</label>
      <input type="text" id="before_alt" name="before_alt" maxlength="255" value="<?= e($form['before_alt']) ?>" placeholder="Original photo of…">
    </div>
    <div class="va-prev" data-for="before_path"<?= $form['before_path'] === '' ? ' hidden' : '' ?>><img src="<?= e($form['before_path']) ?>" alt=""></div>
  </div>

  <div class="adm-card">
    <h2>After image <span class="adm-req">*</span></h2>
    <div class="adm-field">
      <?= adm_upload_field('after_path', $form['after_path'], ['kind' => 'image', 'id' => 'after_path', 'help' => 'Upload the enhanced photo, or paste a URL.']) ?>
    </div>
    <div class="adm-field">
      <label for="after_alt">Alt text</label>
      <input type="text" id="after_alt" name="after_alt" maxlength="255" value="<?= e($form['after_alt']) ?>" placeholder="Enhanced photo of…">
    </div>
    <div class="va-prev" data-for="after_path"<?= $form['after_path'] === '' ? ' hidden' : '' ?>><img src="<?= e($form['after_path']) ?>" alt=""></div>
  </div>

  <div class="adm-card">
    <label class="adm-check"><input type="checkbox" name="is_enabled" value="1" <?= $form['is_enabled'] ? 'checked' : '' ?>> Enabled <span class="adm-muted">&mdash; shown on the Visual Enhancement page</span></label>
  </div>

  <div class="adm-form-actions">
    <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Save pair' : 'Add pair' ?></button>
    <a class="btn btn-outline" href="<?= admin_url('visual/ba-index.php') ?>">Cancel</a>
  </div>
</form>

<script>
document.querySelectorAll('.va-prev').forEach(function (box) {
  var input = document.getElementById(box.getAttribute('data-for'));
  var img = box.querySelector('img');
  if (!input) return;
  input.addEventListener('input', function () {
    var v = input.value.trim();
    if (v) { img.src = v; box.hidden = false; } else { box.hidden = true; }
  });
});
</script>

<?php require __DIR__ . '/../partials/foot.php'; ?>
