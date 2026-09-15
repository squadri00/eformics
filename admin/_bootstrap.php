<?php
/* ===========================================================================
   Eformics Admin — shared bootstrap
   Require this at the very top of every admin script.
   =========================================================================== */

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../partials/config.php';
// -> BASE, url(), e(), app_config(), db(), setting(), set_setting(), hero_media_*()

/* ---------- URLs ---------------------------------------------------- */

function admin_url(string $path = ''): string
{
    return url('/admin/' . ltrim($path, '/'));
}

/* ---------- auth -------------------------------------------------- */

function is_logged_in(): bool
{
    return !empty($_SESSION['admin_ok']);
}

/** Admin password hash: the DB value (set via Site settings) wins; db-config.php is the fallback. */
function admin_password_hash(): string
{
    $db = setting('admin_password_hash', '');
    return $db !== '' ? $db : (string) (app_config()['admin_password_hash'] ?? '');
}

function require_login(): void
{
    if (!is_logged_in()) {
        $_SESSION['after_login'] = $_SERVER['REQUEST_URI'] ?? admin_url();
        redirect(admin_url('login.php'));
    }
}

/* ---------- CSRF ------------------------------------------------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): bool
{
    return !empty($_SESSION['csrf'])
        && isset($_POST['csrf'])
        && hash_equals($_SESSION['csrf'], (string) $_POST['csrf']);
}

function require_csrf(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && !csrf_check()) {
        flash('err', 'Your session expired. Please try that again.');
        redirect($_SERVER['HTTP_REFERER'] ?? admin_url());
    }
}

/* ---------- flash messages ------------------------------------- */

function flash(string $type, string $msg): void
{
    $_SESSION['flash'][] = ['type' => $type === 'ok' ? 'ok' : 'err', 'msg' => $msg];
}

function flash_take(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

/* ---------- misc ---------------------------------------------- */

function redirect(string $to): never
{
    header('Location: ' . $to);
    exit;
}

/**
 * Handle one optional uploaded file.
 * @param string $field  the $_FILES key
 * @param string $accept 'image' | 'video' | 'both'
 * @return array{0: ?string, 1: ?string}  [publicUrl, errorMessage]
 */
function admin_upload(string $field, string $accept = 'both'): array
{
    $f = $_FILES[$field] ?? null;
    if (!$f || ($f['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE || $f['name'] === '') {
        return [null, null];
    }
    if ($f['error'] !== UPLOAD_ERR_OK) {
        return [null, 'upload failed (code ' . (int) $f['error'] . ').'];
    }
    if ($f['size'] > 60 * 1024 * 1024) {
        return [null, 'file is larger than 60 MB.'];
    }

    $images = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $videos = ['video/mp4' => 'mp4', 'video/webm' => 'webm'];
    $allowed = match ($accept) {
        'image' => $images,
        'video' => $videos,
        default => $images + $videos,
    };

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']) ?: '';
    if (!isset($allowed[$mime])) {
        return [null, 'unsupported file type.'];
    }

    $dir = __DIR__ . '/../assets/uploads';
    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
        return [null, 'could not create the uploads folder.'];
    }

    $name = 'hero-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($f['tmp_name'], $dir . '/' . $name)) {
        return [null, 'could not save the uploaded file.'];
    }

    return [url('/assets/uploads/' . $name), null];
}

/**
 * A text input paired with an "Upload" button (AJAX to admin/upload.php).
 * $opts: kind = image|video|both, placeholder, id, help
 */
function adm_upload_field(string $name, string $value, array $opts = []): string
{
    $kind = $opts['kind'] ?? 'image';
    $id   = $opts['id'] ?? preg_replace('~[^a-z0-9_]~i', '_', $name);
    $ph   = $opts['placeholder'] ?? '';
    $accept = $kind === 'video'
        ? 'video/mp4,video/webm'
        : ($kind === 'both' ? 'image/*,video/mp4,video/webm' : 'image/jpeg,image/png,image/webp,image/gif');

    $html  = '<div class="upfield" data-kind="' . e($kind) . '">';
    $html .= '<input type="text" name="' . e($name) . '" id="' . e($id) . '" value="' . e($value) . '"'
           . ($ph !== '' ? ' placeholder="' . e($ph) . '"' : '') . '>';
    $html .= '<button type="button" class="btn btn-outline btn-sm upfield-btn">Upload</button>';
    $html .= '<input type="file" class="upfield-file" accept="' . e($accept) . '" hidden>';
    $html .= '<span class="upfield-status"></span>';
    $html .= '</div>';
    if (!empty($opts['help'])) {
        $html .= '<p class="adm-help">' . $opts['help'] . '</p>';
    }
    return $html;
}

/**
 * Inline SVG for the sidebar / buttons.
 */
function adm_icon(string $name): string
{
    $paths = [
        'grid'     => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
        'home'     => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/>',
        'image'    => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.8"/><path d="m4 18 5.5-5.5a2 2 0 0 1 2.8 0L20 20"/>',
        'video'    => '<rect x="3" y="5" width="13" height="14" rx="2"/><path d="m16 9 5-2.5v11L16 15"/>',
        'external' => '<path d="M14 4h6v6"/><path d="M20 4 10 14"/><path d="M20 14v6H4V4h6"/>',
        'plus'     => '<path d="M12 5v14M5 12h14"/>',
        'link'     => '<path d="M10 13a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1.5 1.5"/><path d="M14 11a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1.5-1.5"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
        'file'     => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/>',
        'map'      => '<path d="m9 4-6 3v13l6-3 6 3 6-3V4l-6 3-6-3z"/><path d="M9 4v13M15 7v13"/>',
    ];
    $d = $paths[$name] ?? $paths['grid'];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
}
