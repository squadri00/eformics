<?php
/* ===========================================================================
   Recodik download gate — OTP request/verify + the download itself.
   Loaded directly by products/recodik.php, partials/recodik-otp-handler.php,
   recodik-download.php and admin/recodik-downloads.php (not auto-loaded by
   config.php, since only those four files need it).
   =========================================================================== */

require_once __DIR__ . '/db.php';

const RECODIK_OTP_TTL_MINUTES    = 10;
const RECODIK_OTP_MAX_ATTEMPTS   = 5;
const RECODIK_OTP_RESEND_COOLDOWN = 60; // seconds

/** A standard country list for the download form's dropdown. */
function recodik_countries(): array
{
    return [
        'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Argentina', 'Armenia',
        'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados',
        'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina',
        'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia',
        'Cameroon', 'Canada', 'Cape Verde', 'Central African Republic', 'Chad', 'Chile',
        'China', 'Colombia', 'Comoros', 'Congo', 'Costa Rica', 'Croatia', 'Cuba', 'Cyprus',
        'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador',
        'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini',
        'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany',
        'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana',
        'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq',
        'Ireland', 'Israel', 'Italy', 'Ivory Coast', 'Jamaica', 'Japan', 'Jordan',
        'Kazakhstan', 'Kenya', 'Kiribati', 'Kosovo', 'Kuwait', 'Kyrgyzstan', 'Laos',
        'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania',
        'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta',
        'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia', 'Moldova',
        'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia',
        'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria',
        'North Korea', 'North Macedonia', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine',
        'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal',
        'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia',
        'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe',
        'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore',
        'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Korea',
        'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland',
        'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo',
        'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu',
        'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States',
        'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam', 'Yemen',
        'Zambia', 'Zimbabwe',
    ];
}

/**
 * Create (or refresh) an OTP for this email. Enforces a resend cooldown so
 * the same email can't be used to trigger unlimited emails.
 * Returns ['ok' => bool, 'error' => ?string, 'code' => ?string].
 */
function recodik_start_request(string $email, string $country, string $ip): array
{
    $pdo = db();
    if (!$pdo) {
        return ['ok' => false, 'error' => 'The download service is temporarily unavailable. Please try again shortly.'];
    }

    $st = $pdo->prepare('SELECT id, last_sent_at FROM recodik_downloads WHERE email = ? ORDER BY id DESC LIMIT 1');
    $st->execute([$email]);
    $existing = $st->fetch();

    if ($existing) {
        $elapsed = time() - strtotime((string) $existing['last_sent_at']);
        if ($elapsed < RECODIK_OTP_RESEND_COOLDOWN) {
            $wait = RECODIK_OTP_RESEND_COOLDOWN - $elapsed;
            return ['ok' => false, 'error' => "Please wait {$wait} second" . ($wait === 1 ? '' : 's') . ' before requesting another code.'];
        }
    }

    $code    = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $hash    = password_hash($code, PASSWORD_DEFAULT);
    $expires = date('Y-m-d H:i:s', time() + RECODIK_OTP_TTL_MINUTES * 60);
    $now     = date('Y-m-d H:i:s');

    if ($existing) {
        $pdo->prepare(
            'UPDATE recodik_downloads
             SET country = ?, otp_hash = ?, otp_expires_at = ?, attempts = 0,
                 last_sent_at = ?, verified_at = NULL, ip_address = ?
             WHERE id = ?'
        )->execute([$country, $hash, $expires, $now, $ip, $existing['id']]);
    } else {
        $pdo->prepare(
            'INSERT INTO recodik_downloads (email, country, otp_hash, otp_expires_at, last_sent_at, ip_address)
             VALUES (?, ?, ?, ?, ?, ?)'
        )->execute([$email, $country, $hash, $expires, $now, $ip]);
    }

    return ['ok' => true, 'code' => $code];
}

/**
 * Check a submitted code against the most recent request for this email.
 * Returns ['ok' => bool, 'error' => ?string].
 */
function recodik_verify(string $email, string $code): array
{
    $pdo = db();
    if (!$pdo) {
        return ['ok' => false, 'error' => 'The download service is temporarily unavailable. Please try again shortly.'];
    }

    $st = $pdo->prepare('SELECT * FROM recodik_downloads WHERE email = ? ORDER BY id DESC LIMIT 1');
    $st->execute([$email]);
    $row = $st->fetch();

    if (!$row) {
        return ['ok' => false, 'error' => 'Please request a code first.'];
    }
    if ((int) $row['attempts'] >= RECODIK_OTP_MAX_ATTEMPTS) {
        return ['ok' => false, 'error' => 'Too many incorrect attempts. Please request a new code.'];
    }
    if (strtotime((string) $row['otp_expires_at']) < time()) {
        return ['ok' => false, 'error' => 'That code has expired. Please request a new one.'];
    }
    if (!password_verify($code, (string) $row['otp_hash'])) {
        $pdo->prepare('UPDATE recodik_downloads SET attempts = attempts + 1 WHERE id = ?')->execute([$row['id']]);
        $left = RECODIK_OTP_MAX_ATTEMPTS - (int) $row['attempts'] - 1;
        return ['ok' => false, 'error' => 'That code is not correct.' . ($left > 0 ? " ({$left} attempt" . ($left === 1 ? '' : 's') . ' left)' : '')];
    }

    $pdo->prepare('UPDATE recodik_downloads SET verified_at = NOW() WHERE id = ?')->execute([$row['id']]);
    return ['ok' => true];
}

/** True if this email's most recent request has been verified. */
function recodik_is_verified(string $email): bool
{
    $pdo = db();
    if (!$pdo || $email === '') {
        return false;
    }
    $st = $pdo->prepare('SELECT verified_at FROM recodik_downloads WHERE email = ? ORDER BY id DESC LIMIT 1');
    $st->execute([$email]);
    $row = $st->fetch();
    return (bool) ($row['verified_at'] ?? null);
}

/** Send the OTP by email via the same Brevo/SMTP setup the contact form uses. */
function recodik_send_otp_email(string $email, string $code): bool
{
    $autoload   = __DIR__ . '/../vendor/autoload.php';
    $configFile = __DIR__ . '/../mail-config.php';
    if (!is_file($autoload) || !is_file($configFile)) {
        error_log('[recodik] mail dependencies missing (vendor/ or mail-config.php).');
        return false;
    }
    require_once $autoload;
    $cfg = require $configFile;

    $placeholders = ['', 'smtp.example.com', 'SMTP_USERNAME', 'SMTP_PASSWORD'];
    if (empty($cfg['host']) || in_array($cfg['host'], $placeholders, true)
        || empty($cfg['username']) || in_array($cfg['username'], $placeholders, true)) {
        error_log('[recodik] SMTP not configured.');
        return false;
    }

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $cfg['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $cfg['username'];
        $mail->Password   = $cfg['password'];
        $mail->Port       = (int) ($cfg['port'] ?? 587);
        $mail->SMTPSecure = (($cfg['encryption'] ?? 'tls') === 'ssl')
            ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS
            : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->CharSet    = \PHPMailer\PHPMailer\PHPMailer::CHARSET_UTF8;

        $mail->setFrom($cfg['from'] ?? $cfg['username'], $cfg['from_name'] ?? 'Eformics Systems');
        $mail->addAddress($email);
        $mail->isHTML(false);
        $mail->Subject = "Your Recodik download code: {$code}";
        $mail->Body    = "Your verification code is: {$code}\n\n"
                        . 'This code expires in ' . RECODIK_OTP_TTL_MINUTES . " minutes.\n\n"
                        . "If you didn't request this, you can safely ignore this email.\n\n"
                        . "— Eformics Systems\n";

        $mail->send();
        return true;
    } catch (\Throwable $e) {
        error_log('[recodik] OTP send failed: ' . $mail->ErrorInfo);
        return false;
    }
}

/** Every download request, newest first — for the admin listing. */
function recodik_all_downloads(): array
{
    $pdo = db();
    if (!$pdo) {
        return [];
    }
    return $pdo->query('SELECT * FROM recodik_downloads ORDER BY id DESC')->fetchAll();
}
