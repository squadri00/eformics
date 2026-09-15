<?php
/* ===========================================================================
   Contact form handler — required at the top of contact.php.
   ---------------------------------------------------------------------------
   Acts only on POST; on GET it returns immediately and contact.php renders
   the page as normal.

   Delivers every submission via SMTP (PHPMailer) to the address in
   mail-config.php (hello@eformics.com by default).

     - AJAX submit (assets/js/main.js)  -> JSON response
     - Plain POST (no JavaScript)        -> redirect to contact.php?sent=1 / ?error=1
   =========================================================================== */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    return; // GET: let the page render
}

require_once __DIR__ . '/config.php';

$SUCCESS_REDIRECT = url('/contact.php') . '?sent=1#contact-form';
$ERROR_REDIRECT   = url('/contact.php') . '?error=1#contact-form';

$isAjax = (
  (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
  || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
);

$respond = function (bool $ok, string $message) use ($isAjax, $SUCCESS_REDIRECT, $ERROR_REDIRECT) {
  if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($ok ? 200 : 400);
    echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'error' => $message]);
  } else {
    header('Location: ' . ($ok ? $SUCCESS_REDIRECT : $ERROR_REDIRECT));
  }
  exit;
};

$GENERIC_ERROR = 'Sorry — your message could not be sent right now. Please email ' . SITE_EMAIL . ' directly.';

/* --- Honeypot: real visitors never fill this hidden field ------------- */
if (!empty($_POST['cf_extra'])) {
  error_log('[contact] honeypot triggered — submission ignored.');
  $respond(true, '');
}

/* --- Collect + validate --------------------------------------------- */
$name     = trim($_POST['name']     ?? '');
$email    = trim($_POST['email']    ?? '');
$interest = trim($_POST['interest'] ?? '');
$message  = trim($_POST['message']  ?? '');

$allowedInterest = ['Meccora', 'Quotaire', 'Chantley', 'Custom Development', 'Website Designing', 'Progressive Web App', 'Something else'];
if (!in_array($interest, $allowedInterest, true)) {
  $interest = 'Not specified';
}

$missing = [];
if ($name === '' || mb_strlen($name) > 120)        $missing[] = 'a valid name';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))    $missing[] = 'a valid email address';
if ($message === '' || mb_strlen($message) > 5000) $missing[] = 'a message';

if ($missing) {
  $respond(false, 'Please provide ' . implode(', ', $missing) . '.');
}

/* Strip any CR/LF before values reach mail headers (Subject / names) */
$cleanName = preg_replace('/[\r\n]+/', ' ', $name);

/* --- Build the plain-text body ------------------------------------- */
$body  = "New enquiry from the Eformics Systems website\n";
$body .= "---------------------------------------------\n\n";
$body .= "Name:      " . $name . "\n";
$body .= "Email:     " . $email . "\n";
$body .= "Interest:  " . $interest . "\n";
$body .= "Sent:      " . date('Y-m-d H:i:s') . "\n";
if (!empty($_SERVER['REMOTE_ADDR'])) {
  $body .= "IP:        " . $_SERVER['REMOTE_ADDR'] . "\n";
}
$body .= "\nMessage:\n" . $message . "\n";

/* --- Dependencies + config --------------------------------------- */
$autoload   = __DIR__ . '/../vendor/autoload.php';
$configFile = __DIR__ . '/../mail-config.php';

if (!is_file($autoload)) {
  error_log('[contact] vendor/autoload.php missing — run "composer install".');
  $respond(false, $GENERIC_ERROR);
}
require $autoload;

if (!is_file($configFile)) {
  error_log('[contact] mail-config.php missing — copy it from mail-config.example.php.');
  $respond(false, $GENERIC_ERROR);
}
$cfg = require $configFile;

$placeholders = ['', 'smtp.example.com', 'SMTP_USERNAME', 'SMTP_PASSWORD', 'PASTE_BREVO_SMTP_LOGIN_HERE', 'PASTE_BREVO_SMTP_KEY_HERE'];
if (
  empty($cfg['host']) || empty($cfg['username']) || empty($cfg['password'])
  || in_array($cfg['host'], $placeholders, true)
  || in_array($cfg['username'], $placeholders, true)
  || in_array($cfg['password'], $placeholders, true)
) {
  error_log('[contact] SMTP not configured — edit mail-config.php with real credentials.');
  $respond(false, $GENERIC_ERROR);
}

/* --- Send via SMTP ------------------------------------------------- */
$mail = new PHPMailer(true);

try {
  $mail->isSMTP();
  $mail->Host       = $cfg['host'];
  $mail->SMTPAuth   = true;
  $mail->Username   = $cfg['username'];
  $mail->Password   = $cfg['password'];
  $mail->Port       = (int) ($cfg['port'] ?? 587);
  $mail->SMTPSecure = (($cfg['encryption'] ?? 'tls') === 'ssl')
    ? PHPMailer::ENCRYPTION_SMTPS
    : PHPMailer::ENCRYPTION_STARTTLS;
  $mail->CharSet    = PHPMailer::CHARSET_UTF8;

  if (!empty($cfg['debug'])) {
    $mail->SMTPDebug   = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = 'error_log';
  }

  $mail->setFrom($cfg['from'] ?? $cfg['username'], $cfg['from_name'] ?? 'Website');
  $mail->addAddress($cfg['to'] ?? SITE_EMAIL, $cfg['to_name'] ?? '');
  $mail->addReplyTo($email, $cleanName);

  $mail->isHTML(false);
  $mail->Subject = 'Website enquiry: ' . $interest . ' — ' . $cleanName;
  $mail->Body    = $body;

  $mail->send();

  $respond(true, '');

} catch (Exception $e) {
  error_log('[contact] SMTP send failed: ' . $mail->ErrorInfo);
  $respond(false, $GENERIC_ERROR);
}
