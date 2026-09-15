<?php
require __DIR__ . '/_bootstrap.php';
require_login();
require_csrf();

$cfg  = homepage_video_config();
$form = [
    'enabled'    => $cfg['enabled'] ? 1 : 0,
    'type'       => $cfg['type'],
    'youtube_id' => $cfg['youtube_id'],
    'src'        => $cfg['src'],
    'poster'     => $cfg['poster'],
    'label'      => $cfg['label'],
];
$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {

    if (($_POST['action'] ?? '') === 'remove') {
        set_setting('home_video_enabled', '0');
        set_setting('home_video_youtube_id', '');
        set_setting('home_video_src', '');
        set_setting('home_video_poster', '');
        flash('ok', 'Homepage video removed. The hero shows the plain placeholder again.');
        redirect(admin_url('homepage-video.php'));
    }

    $form['type']       = ($_POST['type'] ?? 'youtube') === 'file' ? 'file' : 'youtube';
    $form['label']      = trim((string) ($_POST['label'] ?? ''));
    $form['enabled']    = empty($_POST['enabled']) ? 0 : 1;
    $form['youtube_id'] = hero_youtube_id((string) ($_POST['youtube_url'] ?? ''));
    $form['src']        = trim((string) ($_POST['src'] ?? ''));
    $form['poster']     = trim((string) ($_POST['poster'] ?? ''));

    [$vidUp, $e1] = admin_upload('video_file', 'video');
    if ($e1) { $errors[] = 'Video ' . $e1; } elseif ($vidUp) { $form['src'] = $vidUp; }

    [$posUp, $e2] = admin_upload('poster_file', 'image');
    if ($e2) { $errors[] = 'Poster ' . $e2; } elseif ($posUp) { $form['poster'] = $posUp; }

    if ($form['enabled'] && $form['type'] === 'youtube' && $form['youtube_id'] === '') {
        $errors[] = 'Add a valid YouTube link or 11-character video ID, or untick "Show on the homepage".';
    }
    if ($form['enabled'] && $form['type'] === 'file' && $form['src'] === '') {
        $errors[] = 'Add a video file or URL, or untick "Show on the homepage".';
    }

    if (!$errors && !db()) {
        $errors[] = 'The database is not reachable. Check db-config.php.';
    }

    if (!$errors) {
        set_setting('home_video_enabled',    $form['enabled'] ? '1' : '0');
        set_setting('home_video_type',       $form['type']);
        set_setting('home_video_youtube_id', $form['youtube_id']);
        set_setting('home_video_src',        $form['src']);
        set_setting('home_video_poster',     $form['poster']);
        set_setting('home_video_label',      $form['label'] !== '' ? $form['label'] : 'See Eformics in 90 seconds');
        flash('ok', 'Homepage video saved.');
        redirect(admin_url('homepage-video.php'));
    }
}

$hasVideo = $cfg['youtube_id'] !== '' || $cfg['src'] !== '';

$pageTitle = 'Homepage Video';
$navActive = 'homepage-video';
require __DIR__ . '/partials/head.php';
?>

<div class="adm-page-head">
  <h1>Homepage Video</h1>
  <a class="btn btn-outline btn-sm" href="<?= url('/') ?>" target="_blank" rel="noopener">View homepage</a>
</div>

<?php foreach ($errors as $er): ?><div class="flash flash-err"><?= e($er) ?></div><?php endforeach; ?>

<form method="post" enctype="multipart/form-data" class="adm-form" id="hvForm">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="save">

  <div class="adm-card">
    <p class="adm-help" style="margin-top:0;">Plays in the box below the headline on the homepage. This is separate from the per-page hero media.</p>

    <div class="adm-field">
      <label>Video type</label>
      <div class="adm-seg">
        <label><input type="radio" name="type" value="youtube" <?= $form['type'] !== 'file' ? 'checked' : '' ?>> YouTube</label>
        <label><input type="radio" name="type" value="file" <?= $form['type'] === 'file' ? 'checked' : '' ?>> Video file</label>
      </div>
    </div>

    <div class="adm-field">
      <label for="label">Caption (shown when no video is set)</label>
      <input type="text" id="label" name="label" maxlength="120" value="<?= e($form['label']) ?>">
    </div>
  </div>

  <div class="adm-card" data-when="youtube">
    <h2>YouTube</h2>
    <div class="adm-field">
      <label for="youtube_url">YouTube link or video ID</label>
      <input type="text" id="youtube_url" name="youtube_url" value="<?= e($form['youtube_id']) ?>" placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ">
      <p class="adm-help">Paste any YouTube URL (watch, youtu.be, embed, shorts) or the 11-character ID.</p>
    </div>
    <?php if ($form['youtube_id'] !== ''): ?>
      <div class="adm-thumb adm-thumb-lg" style="aspect-ratio:16/9;">
        <img src="https://i.ytimg.com/vi/<?= e($form['youtube_id']) ?>/hqdefault.jpg" alt="">
      </div>
    <?php endif; ?>
  </div>

  <div class="adm-card" data-when="file">
    <h2>Video file</h2>
    <div class="adm-field">
      <label for="video_file">Upload a video</label>
      <input type="file" id="video_file" name="video_file" accept="video/mp4,video/webm">
      <p class="adm-help">MP4 or WebM, up to 60&nbsp;MB. Leave empty to keep the current file.</p>
    </div>
    <div class="adm-field">
      <label for="src">&hellip; or paste a video URL</label>
      <input type="text" id="src" name="src" value="<?= e($form['src']) ?>" placeholder="<?= e(url('/assets/uploads/intro.mp4')) ?>">
    </div>
    <div class="adm-field">
      <label for="poster_file">Poster image (shown before it plays)</label>
      <input type="file" id="poster_file" name="poster_file" accept="image/jpeg,image/png,image/webp">
      <input type="text" name="poster" value="<?= e($form['poster']) ?>" placeholder="<?= e(url('/assets/uploads/poster.jpg')) ?>" style="margin-top:8px;">
    </div>
    <?php if ($form['src'] !== ''): ?>
      <div class="adm-thumb adm-thumb-lg">
        <video src="<?= e($form['src']) ?>"<?= $form['poster'] !== '' ? ' poster="' . e($form['poster']) . '"' : '' ?> controls></video>
      </div>
    <?php endif; ?>
  </div>

  <div class="adm-card">
    <label class="adm-check">
      <input type="checkbox" name="enabled" value="1" <?= $form['enabled'] ? 'checked' : '' ?>>
      Show this video on the homepage
    </label>
    <p class="adm-help">When off (or nothing is set), the homepage shows the plain placeholder box.</p>
  </div>

  <div class="adm-form-actions">
    <button class="btn btn-primary" type="submit">Save</button>
  </div>
</form>

<?php if ($hasVideo): ?>
<form method="post" style="margin-top:6px;" onsubmit="return confirm('Remove the homepage video?');">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="remove">
  <button class="btn btn-danger btn-sm" type="submit">Remove video</button>
</form>
<?php endif; ?>

<script>
(function () {
  var form = document.getElementById('hvForm');
  if (!form) return;
  function sync() {
    var checked = form.querySelector('input[name=type]:checked');
    var t = checked ? checked.value : 'youtube';
    form.querySelectorAll('[data-when]').forEach(function (el) {
      el.hidden = el.getAttribute('data-when') !== t;
    });
  }
  form.querySelectorAll('input[name=type]').forEach(function (r) {
    r.addEventListener('change', sync);
  });
  sync();
})();
</script>

<?php require __DIR__ . '/partials/foot.php'; ?>
