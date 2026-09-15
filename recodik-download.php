<?php
/* ===========================================================================
   Streams Recodik.exe — only after the visitor's email has been verified via
   the OTP flow on products/recodik.php. The file itself lives outside the
   web root (see recodik-config.php); a raw URL to this script is useless to
   anyone who hasn't verified, because the check below is re-done from the
   database every time, not just trusted from the session.
   =========================================================================== */

require __DIR__ . '/partials/config.php';
require_once __DIR__ . '/partials/recodik.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$email = (string) ($_SESSION['recodik_verified_email'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !recodik_is_verified($email)) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    exit("Please verify your email on the Recodik download page first:\n" . url('/products/recodik.php'));
}

$cfgFile = __DIR__ . '/recodik-config.php';
if (!is_file($cfgFile)) {
    error_log('[recodik] recodik-config.php missing.');
    http_response_code(500);
    exit('The download is temporarily unavailable. Please contact ' . SITE_EMAIL . '.');
}
$cfg  = require $cfgFile;
$path = (string) ($cfg['file_path'] ?? '');
$name = (string) ($cfg['download_name'] ?? 'Recodik.exe');

if ($path === '' || !is_file($path)) {
    error_log('[recodik] download file missing at: ' . $path);
    http_response_code(404);
    exit('The download is temporarily unavailable. Please contact ' . SITE_EMAIL . '.');
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . str_replace('"', '', $name) . '"');
header('Content-Length: ' . (string) filesize($path));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

readfile($path);
exit;
