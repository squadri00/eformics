<?php
require __DIR__ . '/_bootstrap.php';

if (is_logged_in()) {
    redirect(admin_url());
}

$error = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_check()) {
        $error = 'Your session expired — please try again.';
    } else {
        $hash = admin_password_hash();
        if ($hash === '' || !password_verify((string) ($_POST['password'] ?? ''), $hash)) {
            usleep(400_000);
            $error = 'Incorrect password.';
        } else {
            session_regenerate_id(true);
            $_SESSION['admin_ok'] = true;
            $to = $_SESSION['after_login'] ?? admin_url();
            unset($_SESSION['after_login']);
            redirect($to);
        }
    }
}
?><!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<meta name="theme-color" content="#16131f">
<title>Sign in — Eformics Admin</title>
<script>
(function(){try{var t=localStorage.getItem('ef-theme');
if(t!=='light'&&t!=='dark'){t=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light';}
document.documentElement.setAttribute('data-theme',t);}catch(e){}})();
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('/assets/css/style.css') ?>">
<link rel="stylesheet" href="<?= asset('/admin/assets/admin.css') ?>">
</head>
<body class="adm-login-page">
<div class="adm-login-wrap">
  <div class="adm-login">
    <h1>Eformics Admin</h1>
    <p class="sub">Sign in to manage site content.</p>
    <?php if ($error !== ''): ?><div class="flash flash-err"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <?= csrf_field() ?>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" autocomplete="current-password" autofocus>
      <div style="margin-top:20px;"><button class="btn btn-primary" type="submit">Sign in</button></div>
    </form>
  </div>
</div>
<script src="<?= asset('/assets/js/theme.js') ?>"></script>
</body>
</html>
