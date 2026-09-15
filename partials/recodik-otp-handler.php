<?php
/* ===========================================================================
   Recodik download gate — AJAX handler for products/recodik.php.
   POST action=request  {email, country}  -> emails a 6-digit code
   POST action=verify   {email, code}     -> marks the email verified,
                                              returns the download URL
   Always responds with JSON: {"ok": bool, "error": string|null, ...}
   =========================================================================== */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/recodik.php';

header('Content-Type: application/json; charset=utf-8');

$respond = function (bool $ok, string $message = '', array $extra = []): never {
    http_response_code($ok ? 200 : 400);
    echo json_encode(array_merge(['ok' => $ok, 'error' => $ok ? null : $message], $extra));
    exit;
};

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    $respond(false, 'Invalid request.');
}

/* Honeypot: a real visitor never fills this hidden field. Pretend success. */
if (!empty($_POST['website'])) {
    $respond(true);
}

$action = (string) ($_POST['action'] ?? '');
$email  = trim((string) ($_POST['email'] ?? ''));
$ip     = (string) ($_SERVER['REMOTE_ADDR'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 255) {
    $respond(false, 'Please enter a valid email address.');
}

if ($action === 'request') {
    $country = trim((string) ($_POST['country'] ?? ''));
    if (!in_array($country, recodik_countries(), true)) {
        $respond(false, 'Please choose your country from the list.');
    }

    $r = recodik_start_request($email, $country, $ip);
    if (!$r['ok']) {
        $respond(false, $r['error']);
    }
    if (!recodik_send_otp_email($email, $r['code'])) {
        $respond(false, 'Could not send the verification email right now. Please try again shortly.');
    }
    $respond(true, '', ['message' => "We've emailed a 6-digit code to {$email}. It expires in " . RECODIK_OTP_TTL_MINUTES . ' minutes.']);

} elseif ($action === 'verify') {
    $code = trim((string) ($_POST['code'] ?? ''));
    if (!preg_match('/^\d{6}$/', $code)) {
        $respond(false, 'Enter the 6-digit code from your email.');
    }

    $r = recodik_verify($email, $code);
    if (!$r['ok']) {
        $respond(false, $r['error']);
    }

    $_SESSION['recodik_verified_email'] = $email;
    $respond(true, '', ['download_url' => url('/recodik-download.php')]);

} else {
    $respond(false, 'Unknown request.');
}
