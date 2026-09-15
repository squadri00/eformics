<?php
require __DIR__ . '/../_bootstrap.php';
require_login();

$id   = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$item = $id > 0 ? hero_media_find($id) : null;

if ($id > 0 && !$item) {
    flash('err', 'That hero media item was not found.');
    redirect(admin_url('hero-media/'));
}

$isEdit = $item !== null;
$errors = [];
$pages  = page_hero_pages();

$form = [
    'page'        => $item['page']        ?? (string) ($_GET['page'] ?? ''),
    'title'       => $item['title']       ?? '',
    'media_type'  => $item['media_type']  ?? 'image',
    'image_path'  => $item['image_path']  ?? '',
    'image_alt'   => $item['image_alt']   ?? '',
    'video_path'  => $item['video_path']  ?? '',
    'youtube_id'  => $item['youtube_id']  ?? '',
    'poster_path' => $item['poster_path'] ?? '',
    'is_active'   => (int) ($item['is_active'] ?? 0),
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_check()) {
        $errors[] = 'Your session expired — please try again.';
    } else {
        $form['page']        = (string) ($_POST['page'] ?? '');
        $form['title']       = trim((string) ($_POST['title'] ?? ''));
        $form['media_type']  = isset(hero_media_types()[$_POST['media_type'] ?? '']) ? (string) $_POST['media_type'] : 'image';
        $form['image_path']  = trim((string) ($_POST['image_path'] ?? ''));
        $form['image_alt']   = trim((string) ($_POST['image_alt'] ?? ''));
        $form['video_path']  = trim((string) ($_POST['video_path'] ?? ''));
        $form['youtube_id']  = hero_youtube_id((string) ($_POST['youtube_url'] ?? ''));
        $form['poster_path'] = trim((string) ($_POST['poster_path'] ?? ''));
        $form['is_active']   = empty($_POST['is_active']) ? 0 : 1;

        [$imgUp, $e1] = admin_upload('image_file', 'image');
        if ($e1) { $errors[] = 'Image ' . $e1; } elseif ($imgUp) { $form['image_path'] = $imgUp; }

        [$vidUp, $e2] = admin_upload('video_file', 'video');
        if ($e2) { $errors[] = 'Video ' . $e2; } elseif ($vidUp) { $form['video_path'] = $vidUp; }

        [$posUp, $e3] = admin_upload('poster_file', 'image');
        if ($e3) { $errors[] = 'Poster ' . $e3; } elseif ($posUp) { $form['poster_path'] = $posUp; }

        if (!isset($pages[$form['page']])) {
            $errors[] = 'Choose which page this hero belongs to.';
        }
        if ($form['title'] === '') {
            $errors[] = 'A title is required.';
        }
        if ($form['media_type'] === 'image' && $form['image_path'] === '') {
            $errors[] = 'Add an image — upload a file or paste a URL.';
        }
        if ($form['media_type'] === 'video' && $form['video_path'] === '') {
            $errors[] = 'Add a video — upload a file or paste a URL.';
        }
        if ($form['media_type'] === 'youtube' && $form['youtube_id'] === '') {
            $errors[] = 'Add a valid YouTube link or 11-character video ID.';
        }

        if (!$errors && !db()) {
            $errors[] = 'The database is not reachable. Check db-config.php.';
        }

        if (!$errors) {
            if ($isEdit) {
                hero_media_update($id, $form);
                flash('ok', 'Changes saved.');
            } else {
                $newId = hero_media_create($form);
                if (!$newId) {
                    $errors[] = 'Could not save. Please try again.';
                } else {
                    flash('ok', 'Hero media added.');
                }
            }
            if (!$errors) {
                redirect(admin_url('hero-media/'));
            }
        }
    }
}

$pageTitle = $isEdit ? 'Edit hero media' : 'Add hero media';
$navActive = 'hero-media';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1><?= $isEdit ? 'Edit hero media' : 'Add hero media' ?></h1>
  <a class="btn btn-outline btn-sm" href="<?= admin_url('hero-media/') ?>">&larr; Back to list</a>
</div>

<?php foreach ($errors as $er): ?><div class="flash flash-err"><?= e($er) ?></div><?php endforeach; ?>

<form method="post" enctype="multipart/form-data" class="adm-form" id="heroForm">
  <?= csrf_field() ?>
  <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>

  <div class="adm-card">
    <div class="adm-field">
      <label for="page">Page <span class="adm-req">*</span></label>
      <select id="page" name="page" required>
        <option value=""<?= $form['page'] === '' ? ' selected' : '' ?>>&mdash; choose a page &mdash;</option>
        <?php foreach ($pages as $key => $label): ?>
          <option value="<?= e($key) ?>"<?= $form['page'] === $key ? ' selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
      </select>
      <p class="adm-help">This media shows on the right of that page's hero banner.</p>
    </div>
    <div class="adm-field">
      <label for="title">Title <span class="adm-req">*</span></label>
      <input type="text" id="title" name="title" maxlength="150" value="<?= e($form['title']) ?>" required>
      <p class="adm-help">An internal label, e.g. &ldquo;Q2 promo video&rdquo;. Not shown on the site.</p>
    </div>
    <div class="adm-field">
      <label>Media type</label>
      <div class="adm-seg">
        <label><input type="radio" name="media_type" value="image" <?= $form['media_type'] === 'image' ? 'checked' : '' ?>> Image</label>
        <label><input type="radio" name="media_type" value="video" <?= $form['media_type'] === 'video' ? 'checked' : '' ?>> Video file</label>
        <label><input type="radio" name="media_type" value="youtube" <?= $form['media_type'] === 'youtube' ? 'checked' : '' ?>> YouTube</label>
      </div>
    </div>
  </div>

  <div class="adm-card" data-when="image">
    <h2>Image</h2>
    <div class="adm-field">
      <label for="image_file">Upload an image</label>
      <input type="file" id="image_file" name="image_file" accept="image/jpeg,image/png,image/webp,image/gif">
      <p class="adm-help">JPG, PNG, WebP or GIF. Leave empty to keep the current file.</p>
    </div>
    <div class="adm-field">
      <label for="image_path">&hellip; or paste an image URL</label>
      <input type="text" id="image_path" name="image_path" value="<?= e($form['image_path']) ?>" placeholder="<?= e(url('/assets/uploads/hero.jpg')) ?>">
    </div>
    <div class="adm-field">
      <label for="image_alt">Alt text</label>
      <input type="text" id="image_alt" name="image_alt" maxlength="255" value="<?= e($form['image_alt']) ?>">
      <p class="adm-help">Describes the image for screen readers and search engines.</p>
    </div>
    <?php if ($form['image_path'] !== ''): ?>
      <div class="adm-thumb adm-thumb-lg"><img src="<?= e($form['image_path']) ?>" alt=""></div>
    <?php endif; ?>
  </div>

  <div class="adm-card" data-when="video">
    <h2>Video file</h2>
    <div class="adm-field">
      <label for="video_file">Upload a video</label>
      <input type="file" id="video_file" name="video_file" accept="video/mp4,video/webm">
      <p class="adm-help">MP4 or WebM, up to 60&nbsp;MB. Leave empty to keep the current file.</p>
    </div>
    <div class="adm-field">
      <label for="video_path">&hellip; or paste a video URL</label>
      <input type="text" id="video_path" name="video_path" value="<?= e($form['video_path']) ?>" placeholder="<?= e(url('/assets/uploads/hero.mp4')) ?>">
    </div>
    <div class="adm-field">
      <label for="poster_file">Poster image (shown before the video plays)</label>
      <input type="file" id="poster_file" name="poster_file" accept="image/jpeg,image/png,image/webp">
      <input type="text" name="poster_path" value="<?= e($form['poster_path']) ?>" placeholder="<?= e(url('/assets/uploads/poster.jpg')) ?>" style="margin-top:8px;">
    </div>
    <?php if ($form['video_path'] !== ''): ?>
      <div class="adm-thumb adm-thumb-lg">
        <video src="<?= e($form['video_path']) ?>"<?= $form['poster_path'] !== '' ? ' poster="' . e($form['poster_path']) . '"' : '' ?> controls></video>
      </div>
    <?php endif; ?>
  </div>

  <div class="adm-card" data-when="youtube">
    <h2>YouTube</h2>
    <div class="adm-field">
      <label for="youtube_url">YouTube link or video ID</label>
      <input type="text" id="youtube_url" name="youtube_url" value="<?= e($form['youtube_id']) ?>" placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ">
      <p class="adm-help">Paste any YouTube URL (watch, youtu.be, embed, shorts) or the 11-character ID. It embeds as a privacy-friendly player.</p>
    </div>
    <?php if ($form['youtube_id'] !== ''): ?>
      <div class="adm-thumb adm-thumb-lg" style="aspect-ratio:16/9;">
        <img src="https://i.ytimg.com/vi/<?= e($form['youtube_id']) ?>/hqdefault.jpg" alt="">
      </div>
    <?php endif; ?>
  </div>

  <div class="adm-card">
    <label class="adm-check">
      <input type="checkbox" name="is_active" value="1" <?= $form['is_active'] ? 'checked' : '' ?>>
      Make this the live hero for its page
    </label>
    <p class="adm-help">Only one item is live per page. Turning this on replaces that page's current one.</p>
  </div>

  <div class="adm-form-actions">
    <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Save changes' : 'Add hero media' ?></button>
    <a class="btn btn-outline" href="<?= admin_url('hero-media/') ?>">Cancel</a>
  </div>
</form>

<script>
(function () {
  var form = document.getElementById('heroForm');
  if (!form) return;
  function sync() {
    var checked = form.querySelector('input[name=media_type]:checked');
    var type = checked ? checked.value : 'image';
    form.querySelectorAll('[data-when]').forEach(function (el) {
      el.hidden = el.getAttribute('data-when') !== type;
    });
  }
  form.querySelectorAll('input[name=media_type]').forEach(function (r) {
    r.addEventListener('change', sync);
  });
  sync();
})();
</script>

<?php require __DIR__ . '/../partials/foot.php'; ?>
