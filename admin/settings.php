<?php
require __DIR__ . '/_bootstrap.php';
require_login();
require_csrf();

$errors  = [];
$pwError = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {

    if (($_POST['action'] ?? '') === 'password') {
        $cur = (string) ($_POST['current_password'] ?? '');
        $new = (string) ($_POST['new_password'] ?? '');
        $rpt = (string) ($_POST['repeat_password'] ?? '');

        if (!password_verify($cur, admin_password_hash())) {
            $pwError = 'The current password is not correct.';
        } elseif (strlen($new) < 8) {
            $pwError = 'The new password must be at least 8 characters.';
        } elseif ($new !== $rpt) {
            $pwError = 'The two new-password boxes do not match.';
        } elseif (!db()) {
            $pwError = 'The database is not reachable, so the password cannot be changed here.';
        } else {
            set_setting('admin_password_hash', password_hash($new, PASSWORD_DEFAULT));
            flash('ok', 'Admin password changed.');
            redirect(admin_url('settings.php'));
        }
    } else {
        $origin = trim((string) ($_POST['site_origin'] ?? ''));
        if ($origin !== '' && !preg_match('~^https?://[^/\s]+~i', $origin)) {
            $errors[] = 'The website address must start with http:// or https://';
        }
        if (!$errors && !db()) {
            $errors[] = 'The database is not reachable.';
        }
        if (!$errors) {
            set_setting('site_name',       trim((string) ($_POST['site_name'] ?? 'Eformics Systems')));
            set_setting('site_origin',     rtrim($origin, '/'));
            set_setting('site_phone',      trim((string) ($_POST['site_phone'] ?? '')));
            set_setting('site_phone_href', trim((string) ($_POST['site_phone_href'] ?? '')));
            set_setting('site_email',      trim((string) ($_POST['site_email'] ?? '')));
            set_setting('site_location',   trim((string) ($_POST['site_location'] ?? '')));
            flash('ok', 'Site settings saved.');
            redirect(admin_url('settings.php'));
        }
    }
}

$s = static fn (string $k, string $d) => setting($k, $d);
$usingDbPassword = setting('admin_password_hash', '') !== '';

$pageTitle = 'Site settings';
$navActive = 'settings';
require __DIR__ . '/partials/head.php';
?>

<div class="adm-page-head"><h1>Site settings</h1></div>

<?php foreach ($errors as $er): ?><div class="flash flash-err"><?= e($er) ?></div><?php endforeach; ?>

<form method="post" class="adm-form">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="identity">
  <div class="adm-card">
    <h2>Business identity</h2>
    <p class="adm-help" style="margin-top:0;">Used in the header, footer, page titles, structured data and canonical links across the whole site.</p>
    <div class="adm-field" style="max-width:420px;">
      <label for="site_name">Business name</label>
      <input type="text" id="site_name" name="site_name" value="<?= e($s('site_name', 'Eformics Systems')) ?>">
    </div>
    <div class="adm-field" style="max-width:480px;">
      <label for="site_origin">Website address (for canonical &amp; sitemap URLs)</label>
      <input type="text" id="site_origin" name="site_origin" value="<?= e($s('site_origin', 'https://www.eformics.com')) ?>" placeholder="https://www.eformics.com">
      <p class="adm-help">No trailing slash. Change this when the real domain goes live, then regenerate the sitemap.</p>
    </div>
    <div class="adm-field" style="max-width:300px;">
      <label for="site_phone">Phone (displayed)</label>
      <input type="text" id="site_phone" name="site_phone" value="<?= e($s('site_phone', '+1 (866) 798-7860')) ?>">
    </div>
    <div class="adm-field" style="max-width:300px;">
      <label for="site_phone_href">Phone link (tel:)</label>
      <input type="text" id="site_phone_href" name="site_phone_href" value="<?= e($s('site_phone_href', 'tel:+18667987860')) ?>" placeholder="tel:+18667987860">
    </div>
    <div class="adm-field" style="max-width:360px;">
      <label for="site_email">Contact email</label>
      <input type="text" id="site_email" name="site_email" value="<?= e($s('site_email', 'hello@eformics.com')) ?>">
    </div>
    <div class="adm-field" style="max-width:420px;">
      <label for="site_location">Location</label>
      <input type="text" id="site_location" name="site_location" value="<?= e($s('site_location', 'Mississauga, Ontario, Canada')) ?>">
    </div>
    <div class="adm-form-actions"><button class="btn btn-primary" type="submit">Save site settings</button></div>
  </div>
</form>

<form method="post" class="adm-form">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="password">
  <div class="adm-card">
    <h2>Admin password</h2>
    <?php if ($pwError !== ''): ?><div class="flash flash-err"><?= e($pwError) ?></div><?php endif; ?>
    <p class="adm-help" style="margin-top:0;">
      <?= $usingDbPassword
        ? 'You are using a password set here in the admin.'
        : 'You are using the default password from <code>db-config.php</code>. Set your own below.' ?>
    </p>
    <div class="adm-field" style="max-width:340px;">
      <label for="current_password">Current password</label>
      <input type="password" id="current_password" name="current_password" autocomplete="current-password">
    </div>
    <div class="adm-field" style="max-width:340px;">
      <label for="new_password">New password</label>
      <input type="password" id="new_password" name="new_password" autocomplete="new-password">
      <p class="adm-help">At least 8 characters.</p>
    </div>
    <div class="adm-field" style="max-width:340px;">
      <label for="repeat_password">Repeat new password</label>
      <input type="password" id="repeat_password" name="repeat_password" autocomplete="new-password">
    </div>
    <div class="adm-form-actions"><button class="btn btn-primary" type="submit">Change password</button></div>
    <p class="adm-help">If you ever get locked out, delete the <code>admin_password_hash</code> row from the <code>site_settings</code> table to fall back to the <code>db-config.php</code> password.</p>
  </div>
</form>

<div class="adm-card">
  <h2>Not editable here</h2>
  <p class="adm-help" style="margin-top:0;">
    Database credentials live in <code>db-config.php</code> and are not editable from the web — a wrong value would lock you out of the admin.
    The URL base path (<code>BASE</code>) lives in <code>partials/config.php</code> because it is a deployment detail.
  </p>
</div>

<?php require __DIR__ . '/partials/foot.php'; ?>
