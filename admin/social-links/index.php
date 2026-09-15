<?php
require __DIR__ . '/../_bootstrap.php';
require_login();
require_csrf();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    switch ($_POST['do'] ?? '') {
        case 'enable':
            social_links_set_enabled($id, true) ? flash('ok', 'Link shown.') : flash('err', 'Could not update.');
            break;
        case 'disable':
            social_links_set_enabled($id, false) ? flash('ok', 'Link hidden.') : flash('err', 'Could not update.');
            break;
        case 'delete':
            social_links_delete($id) ? flash('ok', 'Link removed.') : flash('err', 'Could not remove that link.');
            break;
    }
    redirect(admin_url('social-links/'));
}

$links = social_links_all();

$pageTitle = 'Social Links';
$navActive = 'social-links';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1>Social Links</h1>
  <a class="btn btn-primary btn-sm" href="<?= admin_url('social-links/edit.php') ?>"><?= adm_icon('plus') ?> Add link</a>
</div>

<div class="adm-card adm-card-flush">
<?php if (!$links): ?>
  <div class="adm-empty">
    <p>No social links yet. The footer icons are hidden.</p>
    <a class="btn btn-primary" href="<?= admin_url('social-links/edit.php') ?>">Add the first link</a>
  </div>
<?php else: ?>
  <table class="adm-table">
    <thead>
      <tr><th>Icon</th><th>Label</th><th>URL</th><th>Order</th><th>Status</th><th class="adm-t-right">Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach ($links as $l): ?>
      <tr>
        <td><span class="adm-social-swatch"><?= social_icon_svg($l['icon']) ?></span></td>
        <td><strong><?= e($l['label']) ?></strong></td>
        <td class="adm-muted" style="max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($l['url']) ?></td>
        <td class="adm-muted"><?= (int) $l['sort_order'] ?></td>
        <td>
          <?php if ($l['is_enabled']): ?>
            <span class="badge badge-green">Shown</span>
          <?php else: ?>
            <span class="badge">Hidden</span>
          <?php endif; ?>
        </td>
        <td class="adm-t-right">
          <div class="adm-row-actions">
            <form method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $l['id'] ?>">
              <input type="hidden" name="do" value="<?= $l['is_enabled'] ? 'disable' : 'enable' ?>">
              <button class="btn btn-outline btn-sm" type="submit"><?= $l['is_enabled'] ? 'Hide' : 'Show' ?></button>
            </form>
            <a class="btn btn-outline btn-sm" href="<?= admin_url('social-links/edit.php?id=' . (int) $l['id']) ?>">Edit</a>
            <form method="post" onsubmit="return confirm('Remove this social link?');">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $l['id'] ?>">
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

<p class="adm-help">Links open in a new tab (<code>target="_blank"</code>) with <code>rel="noopener"</code>. Lower “Order” numbers appear first in the footer.</p>

<?php require __DIR__ . '/../partials/foot.php'; ?>
