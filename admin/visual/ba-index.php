<?php
require __DIR__ . '/../_bootstrap.php';
require_login();
require_csrf();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    switch ($_POST['do'] ?? '') {
        case 'enable':  va_ba_set_enabled($id, true);  flash('ok', 'Pair shown on the page.'); break;
        case 'disable': va_ba_set_enabled($id, false); flash('ok', 'Pair hidden.'); break;
        case 'delete':
            va_ba_delete($id) ? flash('ok', 'Pair deleted.') : flash('err', 'Could not delete.');
            break;
    }
    redirect(admin_url('visual/ba-index.php'));
}

$items       = va_ba_all();
$shown       = count(array_filter($items, static fn ($r) => (int) $r['is_enabled'] === 1));
$pageEnabled = page_is_published('services/visual-enhancement');

$pageTitle = 'Before / After';
$navActive = 'va-ba';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1>Visual Enhancement &mdash; Before / After</h1>
  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <a class="btn btn-outline btn-sm" href="<?= url('/services/visual-enhancement.php') ?>" target="_blank" rel="noopener">View page</a>
    <a class="btn btn-outline btn-sm" href="<?= admin_url('visual/vid-index.php') ?>">Videos &rarr;</a>
    <a class="btn btn-primary btn-sm" href="<?= admin_url('visual/ba-edit.php') ?>"><?= adm_icon('plus') ?> Add pair</a>
  </div>
</div>

<?php if (!$pageEnabled): ?>
  <div class="seo-warn">The <strong>Visual Enhancement</strong> page is currently <strong>disabled</strong> (SEO &rarr; Pages). Enable it there to make it live.</div>
<?php endif; ?>

<div class="adm-card">
  <div class="adm-card-head">
    <h2>Before / after pairs</h2>
    <span class="adm-muted"><?= $shown ?> of <?= count($items) ?> shown</span>
  </div>

  <?php if (!$items): ?>
    <div class="adm-empty">
      <p>No before/after pairs yet.</p>
      <a class="btn btn-primary" href="<?= admin_url('visual/ba-edit.php') ?>">Add the first pair</a>
    </div>
  <?php else: ?>
    <p class="adm-help" style="margin-top:0;">Drag the <span aria-hidden="true">&#8942;&#8942;</span> handle to reorder. They appear on the page in this order. <span id="vaSaveState" class="adm-muted"></span></p>
    <div class="folio-admin-grid" id="vaSort">
      <?php foreach ($items as $p): ?>
        <?php $enabled = (int) $p['is_enabled'] === 1; ?>
        <div class="folio-admin-card<?= $enabled ? '' : ' is-off' ?>" data-id="<?= (int) $p['id'] ?>">
          <button type="button" class="folio-drag" aria-label="Drag to reorder" title="Drag to reorder">&#8942;&#8942;</button>
          <div class="folio-admin-thumb" style="display:flex;gap:2px;">
            <?php if ($p['before_path'] !== ''): ?><img src="<?= e($p['before_path']) ?>" alt="" style="width:50%;object-fit:cover;"><?php endif; ?>
            <?php if ($p['after_path'] !== ''): ?><img src="<?= e($p['after_path']) ?>" alt="" style="width:50%;object-fit:cover;"><?php endif; ?>
            <?php if ($p['before_path'] === '' && $p['after_path'] === ''): ?><span><?= adm_icon('image') ?></span><?php endif; ?>
          </div>
          <div class="folio-admin-body">
            <div class="folio-admin-badges">
              <?php if (!$enabled): ?><span class="badge" style="background:#fdecea;color:#a3271a;">Disabled</span><?php endif; ?>
            </div>
            <strong><?= e($p['title'] !== '' ? $p['title'] : 'Untitled pair') ?></strong>
            <span class="adm-muted" style="font-size:.8rem;">before + after</span>
            <div class="folio-admin-actions">
              <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $p['id'] ?>"><input type="hidden" name="do" value="<?= $enabled ? 'disable' : 'enable' ?>"><button class="btn btn-outline btn-sm" type="submit"><?= $enabled ? 'Disable' : 'Enable' ?></button></form>
              <a class="btn btn-outline btn-sm" href="<?= admin_url('visual/ba-edit.php?id=' . (int) $p['id']) ?>">Edit</a>
              <form method="post" onsubmit="return confirm('Delete this before/after pair?');"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $p['id'] ?>"><input type="hidden" name="do" value="delete"><button class="btn btn-danger btn-sm" type="submit">Delete</button></form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php if ($items): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.6/Sortable.min.js"></script>
<script>
(function () {
  var grid = document.getElementById('vaSort');
  var state = document.getElementById('vaSaveState');
  if (!grid || typeof Sortable === 'undefined') return;
  var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var BASE = document.body.getAttribute('data-admin-base');
  Sortable.create(grid, {
    handle: '.folio-drag', animation: 160, ghostClass: 'folio-ghost',
    onEnd: function () {
      var ids = Array.prototype.map.call(grid.children, function (el) { return el.getAttribute('data-id'); });
      state.textContent = 'Saving order…';
      var fd = new FormData(); fd.append('csrf', CSRF);
      ids.forEach(function (id) { fd.append('ids[]', id); });
      fetch(BASE + 'visual/ba-reorder.php', { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (d) { state.textContent = d.ok ? 'Order saved ✓' : 'Could not save order'; })
        .catch(function () { state.textContent = 'Could not save order'; });
    }
  });
})();
</script>
<?php endif; ?>

<?php require __DIR__ . '/../partials/foot.php'; ?>
