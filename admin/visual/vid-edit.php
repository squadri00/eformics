<?php
require __DIR__ . '/../_bootstrap.php';
require_login();

$id   = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$item = $id > 0 ? va_vid_find($id) : null;

if ($id > 0 && !$item) {
    flash('err', 'That video was not found.');
    redirect(admin_url('visual/vid-index.php'));
}

$isEdit = $item !== null;
$errors = [];
$form = [
    'title'       => $item['title']       ?? '',
    'source'      => $item['source']      ?? 'upload',
    'video_path'  => $item['video_path']  ?? '',
    'video_url'   => $item['video_url']   ?? '',
    'poster_path' => $item['poster_path'] ?? '',
    'is_enabled'  => (int) ($item['is_enabled'] ?? 1),
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_check()) {
        $errors[] = 'Your session expired — please try again.';
    } else {
        $form['title']       = trim((string) ($_POST['title'] ?? ''));
        $form['source']      = ($_POST['source'] ?? 'upload') === 'embed' ? 'embed' : 'upload';
        $form['video_path']  = trim((string) ($_POST['video_path'] ?? ''));
        $form['video_url']   = trim((string) ($_POST['video_url'] ?? ''));
        $form['poster_path'] = trim((string) ($_POST['poster_path'] ?? ''));
        $form['is_enabled']  = empty($_POST['is_enabled']) ? 0 : 1;

        [$v, $vErr] = admin_upload('video_file', 'video');
        if ($vErr) { $errors[] = 'Video ' . $vErr; } elseif ($v) { $form['video_path'] = $v; }
        [$p, $pErr] = admin_upload('poster_file', 'image');
        if ($pErr) { $errors[] = 'Poster ' . $pErr; } elseif ($p) { $form['poster_path'] = $p; }

        if ($form['title'] === '') {
            $errors[] = 'A title is required.';
        }
        if ($form['source'] === 'upload' && $form['video_path'] === '') {
            $errors[] = 'Upload an MP4/WebM file, or switch to "Link" and paste a URL.';
        }
        if ($form['source'] === 'embed') {
            if ($form['video_url'] === '') {
                $errors[] = 'Paste a YouTube or Vimeo URL, or switch to "Upload".';
            } elseif (!hero_youtube_id($form['video_url']) && !va_vimeo_id($form['video_url'])) {
                $errors[] = 'That does not look like a YouTube or Vimeo link.';
            }
        }
        if (!$errors && !db()) {
            $errors[] = 'The database is not reachable.';
        }
        if (!$errors) {
            $isEdit ? va_vid_update($id, $form) : ($ok = va_vid_create($form));
            if ($isEdit || !empty($ok)) {
                flash('ok', $isEdit ? 'Video saved.' : 'Video added.');
                redirect(admin_url('visual/vid-index.php'));
            }
            $errors[] = 'Could not save. Please try again.';
        }
    }
}

$pageTitle = $isEdit ? 'Edit video' : 'Add video';
$navActive = 'va-vid';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1><?= $isEdit ? 'Edit video' : 'Add video' ?></h1>
  <a class="btn btn-outline btn-sm" href="<?= admin_url('visual/vid-index.php') ?>">&larr; Back</a>
</div>

<?php foreach ($errors as $er): ?><div class="flash flash-err"><?= e($er) ?></div><?php endforeach; ?>

<form method="post" enctype="multipart/form-data" class="adm-form" id="vidForm">
  <?= csrf_field() ?>
  <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>

  <div class="adm-card">
    <div class="adm-field">
      <label for="title">Title <span class="adm-req">*</span> <span class="adm-muted">(shown over the video)</span></label>
      <input type="text" id="title" name="title" maxlength="150" value="<?= e($form['title']) ?>" required placeholder="e.g. Steaming ramen bowl">
    </div>
  </div>

  <div class="adm-card">
    <h2>Video source</h2>
    <div class="adm-seg" style="margin-bottom:16px;">
      <label><input type="radio" name="source" value="upload" <?= $form['source'] === 'upload' ? 'checked' : '' ?>> Upload a file</label>
      <label><input type="radio" name="source" value="embed" <?= $form['source'] === 'embed' ? 'checked' : '' ?>> YouTube / Vimeo link</label>
    </div>

    <div class="adm-field vid-src" data-src="upload"<?= $form['source'] === 'upload' ? '' : ' hidden' ?>>
      <label for="video_path">MP4 / WebM file</label>
      <?= adm_upload_field('video_path', $form['video_path'], ['kind' => 'video', 'id' => 'video_path', 'help' => 'Keep clips short (a few seconds). Max 60 MB.']) ?>
      <div class="va-prev" data-for="video_path"<?= $form['video_path'] === '' ? ' hidden' : '' ?>><video src="<?= e($form['video_path']) ?>" muted controls style="max-width:320px;border-radius:10px;"></video></div>
    </div>

    <div class="adm-field vid-src" data-src="embed"<?= $form['source'] === 'embed' ? '' : ' hidden' ?>>
      <label for="video_url">YouTube or Vimeo URL</label>
      <input type="text" id="video_url" name="video_url" maxlength="500" value="<?= e($form['video_url']) ?>" placeholder="https://youtu.be/… or https://vimeo.com/…">
      <p class="adm-help">The clip plays muted &amp; looping, like the uploaded ones.</p>
    </div>
  </div>

  <div class="adm-card">
    <h2>Poster image <span class="adm-muted">(optional)</span></h2>
    <div class="adm-field">
      <?= adm_upload_field('poster_path', $form['poster_path'], ['kind' => 'image', 'id' => 'poster_path', 'help' => 'Shown before the video starts and in the admin list. Optional.']) ?>
    </div>
    <div class="va-prev" data-for="poster_path"<?= $form['poster_path'] === '' ? ' hidden' : '' ?>><img src="<?= e($form['poster_path']) ?>" alt=""></div>
  </div>

  <div class="adm-card">
    <label class="adm-check"><input type="checkbox" name="is_enabled" value="1" <?= $form['is_enabled'] ? 'checked' : '' ?>> Enabled <span class="adm-muted">&mdash; shown on the Visual Enhancement page</span></label>
  </div>

  <div class="adm-form-actions">
    <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Save video' : 'Add video' ?></button>
    <a class="btn btn-outline" href="<?= admin_url('visual/vid-index.php') ?>">Cancel</a>
  </div>
</form>

<script>
(function () {
  var form = document.getElementById('vidForm');
  form.querySelectorAll('input[name="source"]').forEach(function (r) {
    r.addEventListener('change', function () {
      form.querySelectorAll('.vid-src').forEach(function (box) {
        box.hidden = box.getAttribute('data-src') !== r.value;
      });
    });
  });
  document.querySelectorAll('.va-prev').forEach(function (box) {
    var input = document.getElementById(box.getAttribute('data-for'));
    var media = box.querySelector('img,video');
    if (!input || !media) return;
    input.addEventListener('input', function () {
      var v = input.value.trim();
      if (v) { media.src = v; box.hidden = false; } else { box.hidden = true; }
    });
  });
})();
</script>

<?php require __DIR__ . '/../partials/foot.php'; ?>
