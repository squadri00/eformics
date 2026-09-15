<?php
require __DIR__ . '/../_bootstrap.php';
require_login();
require_csrf();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    switch ($_POST['do'] ?? '') {
        case 'columns':
            $c = (int) ($_POST['cols'] ?? 3);
            set_setting('portfolio_columns', in_array($c, [2, 3, 4], true) ? (string) $c : '3');
            flash('ok', 'Column layout saved.');
            break;
        case 'pagetext':
            set_setting('portfolio_heading', trim((string) ($_POST['portfolio_heading'] ?? 'Our work')));
            set_setting('portfolio_intro',   trim((string) ($_POST['portfolio_intro'] ?? '')));
            flash('ok', 'Page text saved.');
            break;
        case 'slider':
            set_setting('wd_slider_enabled',  empty($_POST['wd_slider_enabled']) ? '0' : '1');
            $pv = (int) ($_POST['wd_slider_per_view'] ?? 3);
            set_setting('wd_slider_per_view', in_array($pv, [1, 2, 3], true) ? (string) $pv : '3');
            set_setting('wd_slider_autoplay', empty($_POST['wd_slider_autoplay']) ? '0' : '1');
            $iv = (int) ($_POST['wd_slider_interval'] ?? 4);
            set_setting('wd_slider_interval', (string) max(2, min(30, $iv)));
            set_setting('wd_slider_heading',  trim((string) ($_POST['wd_slider_heading'] ?? 'Recent work')));
            set_setting('wd_slider_intro',    trim((string) ($_POST['wd_slider_intro'] ?? '')));
            flash('ok', 'Slider settings saved.');
            break;
        case 'import':
            $n = portfolio_import_folder();
            flash($n > 0 ? 'ok' : 'err', $n > 0
                ? "$n project" . ($n === 1 ? '' : 's') . " imported from images/portfolio/ as disabled drafts. Edit each to add a link and details, then enable it."
                : 'Nothing new to import — every image in images/portfolio/ is already used.');
            break;
        case 'feature':   portfolio_set_flag($id, 'is_featured', true);  flash('ok', 'Marked as featured.'); break;
        case 'unfeature': portfolio_set_flag($id, 'is_featured', false); flash('ok', 'Removed from featured.'); break;
        case 'enable':    portfolio_set_flag($id, 'is_enabled', true);   flash('ok', 'Project shown.'); break;
        case 'disable':   portfolio_set_flag($id, 'is_enabled', false);  flash('ok', 'Project hidden.'); break;
        case 'delete':
            portfolio_delete($id) ? flash('ok', 'Project deleted.') : flash('err', 'Could not delete.');
            break;
    }
    redirect(admin_url('portfolio/'));
}

$items    = portfolio_all();
$cols     = portfolio_columns();
$shown    = count(array_filter($items, static fn ($p) => (int) $p['is_enabled'] === 1));
$featuredN = count(array_filter($items, static fn ($p) => (int) $p['is_enabled'] === 1 && (int) $p['is_featured'] === 1));
$pageEnabled = page_is_published('portfolio');

$pageTitle = 'Portfolio';
$navActive = 'portfolio';
require __DIR__ . '/../partials/head.php';
?>

<div class="adm-page-head">
  <h1>Portfolio</h1>
  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <a class="btn btn-outline btn-sm" href="<?= url('/portfolio.php') ?>" target="_blank" rel="noopener">View page</a>
    <a class="btn btn-primary btn-sm" href="<?= admin_url('portfolio/edit.php') ?>"><?= adm_icon('plus') ?> Add project</a>
  </div>
</div>

<?php if (!$pageEnabled): ?>
  <div class="seo-warn">The Portfolio page is currently <strong>disabled</strong> (SEO &rarr; Pages). Visitors get a 404 and it's hidden from the menus. Enable it there to make it live.</div>
<?php endif; ?>

<div class="adm-card">
  <div class="adm-card-head">
    <h2>Page settings</h2>
    <span class="adm-muted"><?= $shown ?> of <?= count($items) ?> project<?= count($items) === 1 ? '' : 's' ?> shown</span>
  </div>
  <form method="post" style="display:flex;flex-wrap:wrap;gap:26px;align-items:flex-end;">
    <?= csrf_field() ?>
    <input type="hidden" name="do" value="columns">
    <div>
      <label style="display:block;font-size:.85rem;font-weight:600;margin-bottom:8px;">Grid columns on the site</label>
      <div class="adm-seg">
        <?php foreach ([2, 3, 4] as $c): ?>
        <label><input type="radio" name="cols" value="<?= $c ?>" <?= $cols === $c ? 'checked' : '' ?>> <?= $c ?></label>
        <?php endforeach; ?>
      </div>
    </div>
    <button class="btn btn-primary btn-sm" type="submit">Save layout</button>
  </form>

  <form method="post" class="adm-form" style="margin-top:22px;max-width:560px;">
    <?= csrf_field() ?>
    <input type="hidden" name="do" value="pagetext">
    <div class="adm-field">
      <label for="portfolio_heading">Page heading</label>
      <input type="text" id="portfolio_heading" name="portfolio_heading" value="<?= e(setting('portfolio_heading', 'Our work')) ?>">
    </div>
    <div class="adm-field">
      <label for="portfolio_intro">Intro line</label>
      <textarea id="portfolio_intro" name="portfolio_intro" rows="2"><?= e(setting('portfolio_intro', '')) ?></textarea>
    </div>
    <div class="adm-form-actions"><button class="btn btn-primary btn-sm" type="submit">Save text</button></div>
  </form>
</div>

<div class="adm-card">
  <div class="adm-card-head">
    <h2>Website Designing slider</h2>
    <span class="adm-muted"><?= $featuredN ?> featured project<?= $featuredN === 1 ? '' : 's' ?> feed it</span>
  </div>
  <p class="adm-help" style="margin-top:0;">A carousel just below the hero on the <a href="<?= url('/services/web-design.php') ?>" target="_blank" rel="noopener">Website Designing</a> page. It shows projects that are <strong>enabled</strong> and marked <strong>Featured</strong>, in the order below. Each slide opens the project's website in a new tab.</p>
  <form method="post" class="adm-form" style="max-width:560px;">
    <?= csrf_field() ?>
    <input type="hidden" name="do" value="slider">
    <label class="adm-check"><input type="checkbox" name="wd_slider_enabled" value="1" <?= setting('wd_slider_enabled', '1') === '1' ? 'checked' : '' ?>> Show the slider</label>

    <div class="adm-field" style="margin-top:16px;">
      <label>Images shown at once</label>
      <div class="adm-seg">
        <?php $pv = (int) setting('wd_slider_per_view', '3'); foreach ([1, 2, 3] as $n): ?>
        <label><input type="radio" name="wd_slider_per_view" value="<?= $n ?>" <?= $pv === $n ? 'checked' : '' ?>> <?= $n ?></label>
        <?php endforeach; ?>
      </div>
      <p class="adm-help">Drops to 2 on tablet and 1 on phone automatically.</p>
    </div>

    <label class="adm-check"><input type="checkbox" name="wd_slider_autoplay" value="1" <?= setting('wd_slider_autoplay', '1') === '1' ? 'checked' : '' ?>> Auto-scroll (pauses when the mouse is over it)</label>
    <div class="adm-field" style="max-width:180px;margin-top:12px;">
      <label for="wd_slider_interval">Seconds between slides</label>
      <input type="text" inputmode="numeric" id="wd_slider_interval" name="wd_slider_interval" value="<?= e(setting('wd_slider_interval', '4')) ?>">
    </div>

    <div class="adm-field">
      <label for="wd_slider_heading">Heading above the slider</label>
      <input type="text" id="wd_slider_heading" name="wd_slider_heading" value="<?= e(setting('wd_slider_heading', 'Recent work')) ?>">
    </div>
    <div class="adm-field">
      <label for="wd_slider_intro">Intro line</label>
      <textarea id="wd_slider_intro" name="wd_slider_intro" rows="2"><?= e(setting('wd_slider_intro', '')) ?></textarea>
    </div>
    <div class="adm-form-actions"><button class="btn btn-primary btn-sm" type="submit">Save slider</button></div>
  </form>
</div>

<div class="adm-card">
  <div class="adm-card-head">
    <h2>Projects</h2>
    <form method="post" onsubmit="return confirm('Create a disabled draft for every image in images/portfolio/ that is not already used?');">
      <?= csrf_field() ?>
      <input type="hidden" name="do" value="import">
      <button class="btn btn-outline btn-sm" type="submit">Import from images/portfolio/</button>
    </form>
  </div>

  <?php if (!$items): ?>
    <div class="adm-empty">
      <p>No projects yet.</p>
      <a class="btn btn-primary" href="<?= admin_url('portfolio/edit.php') ?>">Add the first project</a>
    </div>
  <?php else: ?>
    <p class="adm-help" style="margin-top:0;">Drag the <span aria-hidden="true">&#8942;&#8942;</span> handle to reorder. Featured projects always appear first on the site, in this order. <span id="folioSaveState" class="adm-muted"></span></p>
    <div class="folio-admin-grid" id="folioSort">
      <?php foreach ($items as $p): ?>
        <?php
          $alt      = $p['image_alt'] !== '' ? $p['image_alt'] : $p['title'];
          $featured = (int) $p['is_featured'] === 1;
          $enabled  = (int) $p['is_enabled'] === 1;
        ?>
        <div class="folio-admin-card<?= $enabled ? '' : ' is-off' ?>" data-id="<?= (int) $p['id'] ?>">
          <button type="button" class="folio-drag" aria-label="Drag to reorder" title="Drag to reorder">&#8942;&#8942;</button>
          <div class="folio-admin-thumb">
            <?php if ($p['image_path'] !== ''): ?><img src="<?= e($p['image_path']) ?>" alt="<?= e($alt) ?>"><?php else: ?><span><?= adm_icon('image') ?></span><?php endif; ?>
          </div>
          <div class="folio-admin-body">
            <div class="folio-admin-badges">
              <?php if ($featured): ?><span class="badge badge-green">Featured</span><?php endif; ?>
              <?php if (!$enabled): ?><span class="badge" style="background:#fdecea;color:#a3271a;">Disabled</span><?php endif; ?>
            </div>
            <strong><?= e($p['title']) ?></strong>
            <?php if ($p['client_name'] !== ''): ?><span class="adm-muted"><?= e($p['client_name']) ?></span><?php endif; ?>
            <?php if (trim($p['website_url']) !== ''): ?>
              <a class="folio-admin-url" href="<?= e($p['website_url']) ?>" target="_blank" rel="noopener"><?= e(preg_replace('~^https?://~', '', $p['website_url'])) ?></a>
            <?php else: ?>
              <span class="adm-muted" style="font-size:.8rem;">no link yet</span>
            <?php endif; ?>
            <div class="folio-admin-actions">
              <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $p['id'] ?>"><input type="hidden" name="do" value="<?= $featured ? 'unfeature' : 'feature' ?>"><button class="btn btn-outline btn-sm" type="submit"><?= $featured ? 'Unfeature' : 'Feature' ?></button></form>
              <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $p['id'] ?>"><input type="hidden" name="do" value="<?= $enabled ? 'disable' : 'enable' ?>"><button class="btn btn-outline btn-sm" type="submit"><?= $enabled ? 'Disable' : 'Enable' ?></button></form>
              <a class="btn btn-outline btn-sm" href="<?= admin_url('portfolio/edit.php?id=' . (int) $p['id']) ?>">Edit</a>
              <form method="post" onsubmit="return confirm('Delete this project?');"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $p['id'] ?>"><input type="hidden" name="do" value="delete"><button class="btn btn-danger btn-sm" type="submit">Delete</button></form>
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
  var grid = document.getElementById('folioSort');
  var state = document.getElementById('folioSaveState');
  if (!grid || typeof Sortable === 'undefined') return;
  var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var BASE = document.body.getAttribute('data-admin-base');

  Sortable.create(grid, {
    handle: '.folio-drag',
    animation: 160,
    ghostClass: 'folio-ghost',
    onEnd: function () {
      var ids = Array.prototype.map.call(grid.children, function (el) { return el.getAttribute('data-id'); });
      state.textContent = 'Saving order…';
      var fd = new FormData();
      fd.append('csrf', CSRF);
      ids.forEach(function (id) { fd.append('ids[]', id); });
      fetch(BASE + 'portfolio/reorder.php', { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (d) { state.textContent = d.ok ? 'Order saved ✓' : 'Could not save order'; })
        .catch(function () { state.textContent = 'Could not save order'; });
    }
  });
})();
</script>
<?php endif; ?>

<?php require __DIR__ . '/../partials/foot.php'; ?>
