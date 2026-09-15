<?php
/* ===========================================================================
   Database access + site settings
   ---------------------------------------------------------------------------
   Loaded by partials/config.php, so setting() is available on every page.
   The connection is lazy: nothing touches the database until a function
   that needs it is actually called.
   =========================================================================== */

/**
 * The parsed db-config.php (or an empty array if it is missing).
 */
function app_config(): array
{
    static $cfg = null;
    if ($cfg === null) {
        $file = __DIR__ . '/../db-config.php';
        $cfg  = is_file($file) ? (array) require $file : [];
    }
    return $cfg;
}

/**
 * A shared PDO connection, or null if the database is unavailable.
 */
function db(): ?PDO
{
    static $pdo = false; // false = not tried yet
    if ($pdo !== false) {
        return $pdo;
    }

    $c = app_config()['db'] ?? null;
    if (!$c || empty($c['name'])) {
        return $pdo = null;
    }

    try {
        $pdo = new PDO(
            "mysql:host={$c['host']};dbname={$c['name']};charset=" . ($c['charset'] ?? 'utf8mb4'),
            $c['user'] ?? '',
            $c['pass'] ?? '',
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    } catch (PDOException $e) {
        error_log('[db] connection failed: ' . $e->getMessage());
        $pdo = null;
    }

    return $pdo;
}

/**
 * All rows from site_settings as key => value. Read once per request.
 */
function all_settings(bool $reload = false): array
{
    static $cache = null;
    if ($cache !== null && !$reload) {
        return $cache;
    }

    $cache = [];
    $pdo   = db();
    if ($pdo) {
        try {
            $rows = $pdo->query('SELECT setting_key, setting_value FROM site_settings');
            foreach ($rows as $row) {
                $cache[$row['setting_key']] = $row['setting_value'];
            }
        } catch (PDOException $e) {
            error_log('[db] settings read failed: ' . $e->getMessage());
        }
    }

    return $cache;
}

/**
 * One setting, with a fallback used when the key is missing OR stored empty.
 */
function setting(string $key, string $default = ''): string
{
    $all = all_settings();
    return isset($all[$key]) && $all[$key] !== '' ? $all[$key] : $default;
}

/**
 * Create or update one setting. Returns false if the database is unavailable.
 */
function set_setting(string $key, string $value): bool
{
    $pdo = db();
    if (!$pdo) {
        return false;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO site_settings (setting_key, setting_value)
         VALUES (:k, :v)
         ON DUPLICATE KEY UPDATE setting_value = :v2'
    );
    $ok = $stmt->execute([':k' => $key, ':v' => $value, ':v2' => $value]);

    if ($ok) {
        all_settings(true); // refresh the in-request cache
    }
    return $ok;
}
