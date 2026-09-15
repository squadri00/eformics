<?php
require __DIR__ . '/_bootstrap.php';
require_login();
require_once __DIR__ . '/../partials/recodik.php';

/* CSV export -- before any HTML output. */
if (($_GET['export'] ?? '') === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="recodik-downloads-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Email', 'Country', 'Requested', 'Verified', 'IP address']);
    foreach (recodik_all_downloads() as $row) {
        fputcsv($out, [
            $row['email'],
            $row['country'],
            $row['created_at'],
            $row['verified_at'] ?? '',
            $row['ip_address'] ?? '',
        ]);
    }
    fclose($out);
    exit;
}

$rows           = recodik_all_downloads();
$verifiedCount  = count(array_filter($rows, static fn ($r) => !empty($r['verified_at'])));

$pageTitle = 'Recodik downloads';
$navActive = 'recodik-downloads';
require __DIR__ . '/partials/head.php';
?>

<div class="adm-page-head">
  <h1>Recodik downloads</h1>
  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <a class="btn btn-outline btn-sm" href="<?= url('/products/recodik.php') ?>" target="_blank" rel="noopener">View page</a>
    <a class="btn btn-primary btn-sm" href="<?= admin_url('recodik-downloads.php?export=csv') ?>">Export CSV</a>
  </div>
</div>

<div class="adm-card">
  <div class="adm-card-head">
    <h2>Requests</h2>
    <span class="adm-muted"><?= $verifiedCount ?> verified of <?= count($rows) ?> total</span>
  </div>

  <?php if (!$rows): ?>
    <div class="adm-empty"><p>No download requests yet.</p></div>
  <?php else: ?>
    <div style="overflow-x:auto;">
      <table class="adm-table">
        <thead>
          <tr>
            <th>Email</th>
            <th>Country</th>
            <th>Requested</th>
            <th>Status</th>
            <th>IP address</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= e($r['email']) ?></td>
              <td><?= e($r['country']) ?></td>
              <td><?= e($r['created_at']) ?></td>
              <td>
                <?php if (!empty($r['verified_at'])): ?>
                  <span class="badge badge-green">Verified <?= e($r['verified_at']) ?></span>
                <?php else: ?>
                  <span class="badge" style="background:#fdecea;color:#a3271a;">Not verified</span>
                <?php endif; ?>
              </td>
              <td class="adm-muted"><?= e($r['ip_address'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/partials/foot.php'; ?>
