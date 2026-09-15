<?php
require __DIR__ . '/../_bootstrap.php';
require_login();
require_csrf();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    switch ($_POST['do'] ?? '') {
        case 'activate':
            hero_media_set_active($id)
                ? flash('ok', 'That item is now live for its page.')
                : flash('err', 'Could not update the live item.');
            break;
        case 'delete':
            hero_media_delete($id)
                ? flash('ok', 'Item deleted.')
                : flash('err', 'Could not delete that item.');
            break;
    }
    redirect(admin_url('hero-media/'));
}

$items    = hero_media_all();
$pageList = page_hero_pages();

$pageTitle = 'Page Hero Media';
$navActive = 'hero-media';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1>Page Hero Media</h1>
  <a class="btn btn-primary btn-sm" href="<?= admin_url('hero-media/edit.php') ?>"><?= adm_icon('plus') ?> Add new</a>
</div>

<div class="adm-card adm-card-flush">
<?php if (!$items): ?>
  <div class="adm-empty">
    <p>No hero media yet.</p>
    <a class="btn btn-primary" href="<?= admin_url('hero-media/edit.php') ?>">Add the first item</a>
  </div>
<?php else: ?>
  <table class="adm-table">
    <thead>
      <tr>
        <th>Preview</th><th>Title</th><th>Page</th><th>Type</th><th>Status</th><th>Updated</th><th class="adm-t-right">Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $it): ?>
      <?php
        $type      = $it['media_type'];
        $pageLabel = $pageList[$it['page']] ?? ($it['page'] !== '' ? $it['page'] : '(unassigned)');
        if ($type === 'youtube') {
            $thumb = $it['poster_path'] !== '' ? $it['poster_path']
                   : ($it['youtube_id'] !== '' ? 'https://i.ytimg.com/vi/' . rawurlencode($it['youtube_id']) . '/default.jpg' : '');
        } elseif ($type === 'video') {
            $thumb = $it['poster_path'];
        } else {
            $thumb = $it['image_path'];
        }
      ?>
      <tr>
        <td>
          <div class="adm-thumb adm-thumb-sm">
            <?php if ($thumb !== ''): ?>
              <img src="<?= e($thumb) ?>" alt="">
            <?php else: ?>
              <?= adm_icon($type === 'image' ? 'image' : 'video') ?>
            <?php endif; ?>
          </div>
        </td>
        <td><strong><?= e($it['title']) ?></strong></td>
        <td class="adm-muted"><?= e($pageLabel) ?></td>
        <td><?= e(hero_media_type_label($type)) ?></td>
        <td>
          <?php if ($it['is_active']): ?>
            <span class="badge badge-green">Live</span>
          <?php else: ?>
            <span class="badge">Draft</span>
          <?php endif; ?>
        </td>
        <td class="adm-muted"><?= e(date('j M Y', strtotime($it['updated_at']))) ?></td>
        <td class="adm-t-right">
          <div class="adm-row-actions">
            <?php if (!$it['is_active']): ?>
            <form method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $it['id'] ?>">
              <input type="hidden" name="do" value="activate">
              <button class="btn btn-outline btn-sm" type="submit">Set live</button>
            </form>
            <?php endif; ?>
            <a class="btn btn-outline btn-sm" href="<?= admin_url('hero-media/edit.php?id=' . (int) $it['id']) ?>">Edit</a>
            <form method="post" onsubmit="return confirm('Delete this hero media item? This cannot be undone.');">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $it['id'] ?>">
              <input type="hidden" name="do" value="delete">
              <button class="btn btn-danger btn-sm" type="submit">Delete</button>
            </form>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
</div>

<p class="adm-help">Each page shows its own live item on the right of its hero banner. Setting one item live replaces any other live item for the same page. Pages with no live item keep a plain, text-only hero.</p>

<?php require __DIR__ . '/../partials/foot.php'; ?>
