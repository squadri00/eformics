<?php
/* ===========================================================================
   Eformics — one-shot mail diagnostic.
   USAGE:  upload to public_html, then open:  https://eformics.com/mailcheck.php?run=1
   Paste the whole page back to your developer, then DELETE this file.
   It never prints your password.
   =========================================================================== */

header('Content-Type: text/plain; charset=utf-8');

if (($_GET['run'] ?? '') !== '1') {
    exit("Add ?run=1 to the URL to run the check:\n  https://eformics.com/mailcheck.php?run=1\n");
}

function line(string $s = ''): void { echo $s . "\n"; }

line('=== Eformics mail diagnostic ===');
line('Time: ' . date('Y-m-d H:i:s'));
line('PHP:  ' . PHP_VERSION);
line('OpenSSL extension: ' . (extension_loaded('openssl') ? 'YES' : 'NO  <-- STARTTLS/SSL cannot work without this'));
line('');

/* ---- 1. Can we even open a socket to the mail server? ---------------------- */
line('--- Step 1: network connection to smtp.titan.email ---');
foreach ([465, 587, 25] as $port) {
    $t  = microtime(true);
    $fp = @fsockopen('smtp.titan.email', $port, $errno, $errstr, 8);
    $ms = round((microtime(true) - $t) * 1000);
    if ($fp) {
        line(sprintf('  port %-3d : OPEN        (%d ms)', $port, $ms));
        fclose($fp);
    } else {
        line(sprintf('  port %-3d : BLOCKED     (%d ms)  [%s %s]', $port, $ms, $errno, trim($errstr)));
    }
}
line('');

/* ---- 2. Is PHPMailer installed? ------------------------------------------- */
line('--- Step 2: PHPMailer library ---');
$autoload = __DIR__ . '/vendor/autoload.php';
if (!is_file($autoload)) {
    line('  vendor/autoload.php  : MISSING  <-- upload the "vendor" folder to public_html');
    line('');
    line('VERDICT: The PHPMailer library is not on the server. Nothing can send until');
    line('         the "vendor" folder is uploaded (or "composer install" is run).');
    exit;
}
require $autoload;
line('  vendor/autoload.php  : found');
line('');

/* ---- 3. Read mail-config.php -------------------------------------------------- */
line('--- Step 3: mail-config.php ---');
$cfgFile = __DIR__ . '/mail-config.php';
if (!is_file($cfgFile)) {
    line('  mail-config.php : MISSING in public_html');
    exit;
}
$cfg = require $cfgFile;
line('  to         : ' . ($cfg['to']       ?? '(not set)'));
line('  from       : ' . ($cfg['from']     ?? '(not set)'));
line('  host       : ' . ($cfg['host']     ?? '(not set)'));
line('  port       : ' . ($cfg['port']     ?? '(not set)'));
line('  encryption : ' . ($cfg['encryption'] ?? '(not set)'));
line('  username   : ' . ($cfg['username'] ?? '(not set)'));
line('  password   : ' . (empty($cfg['password']) ? '(EMPTY!)' : '(set, ' . strlen((string) $cfg['password']) . ' chars)'));
$placeholders = ['', 'smtp.example.com', 'SMTP_USERNAME', 'SMTP_PASSWORD'];
if (in_array($cfg['host'] ?? '', $placeholders, true)
    || in_array($cfg['username'] ?? '', $placeholders, true)
    || in_array($cfg['password'] ?? '', $placeholders, true)) {
    line('');
    line('VERDICT: mail-config.php still has PLACEHOLDER values. Put in the real');
    line('         mailbox host / username / password and try again.');
    exit;
}
line('');

/* ---- 4. Real SMTP send with full transcript ---------------------------------- */
line('--- Step 4: live SMTP send test ---');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$m = new PHPMailer(true);
$m->SMTPDebug   = SMTP::DEBUG_SERVER;
$m->Debugoutput = static function ($str) { echo '  ' . rtrim($str) . "\n"; };

try {
    $m->isSMTP();
    $m->Host       = $cfg['host'];
    $m->SMTPAuth   = true;
    $m->Username   = $cfg['username'];
    $m->Password   = $cfg['password'];
    $m->Port       = (int) ($cfg['port'] ?? 587);
    $m->SMTPSecure = (($cfg['encryption'] ?? 'tls') === 'ssl')
        ? PHPMailer::ENCRYPTION_SMTPS
        : PHPMailer::ENCRYPTION_STARTTLS;
    $m->Timeout    = 12;
    $m->CharSet    = PHPMailer::CHARSET_UTF8;

    $m->setFrom($cfg['from'] ?? $cfg['username'], $cfg['from_name'] ?? 'Website');
    $m->addAddress($cfg['to'] ?? 'hello@eformics.com');
    $m->Subject = 'Eformics mailcheck ' . date('H:i:s');
    $m->Body    = 'If this arrives in ' . ($cfg['to'] ?? 'hello@eformics.com') . ', SMTP is working.';
    $m->isHTML(false);

    $m->send();
    line('');
    line('VERDICT: SENT OK. Now check the inbox for ' . ($cfg['to'] ?? 'hello@eformics.com')
         . ' (and its spam folder). If it never arrives, that address is not a real');
    line('         mailbox/alias — create it in your email panel.');
} catch (Throwable $e) {
    line('');
    line('FAILED: ' . $m->ErrorInfo);
    line('');
    line('How to read this:');
    line('  "Could not connect" / timeout      -> host is blocking outbound SMTP; ask Hostinger to open it,');
    line('                                        or try the other port (465<->587) in mail-config.php.');
    line('  "535 ... authentication failed"     -> wrong username or password for the mailbox.');
    line('  "STARTTLS" / SSL errors             -> enable the OpenSSL extension (see top of this page).');
    line('  "550 ... not allowed to send as"    -> "from" must equal "username" exactly.');
}
line('');
line('=== done — delete mailcheck.php from the server now ===');
