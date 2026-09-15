<?php
/* ===========================================================================
   portfolio — web design portfolio projects
   Loaded by partials/config.php.
   =========================================================================== */

require_once __DIR__ . '/db.php';

/** Grid column count for the public page (2, 3 or 4). */
function portfolio_columns(): int
{
    $c = (int) setting('portfolio_columns', '3');
    return in_array($c, [2, 3, 4], true) ? $c : 3;
}

/** All projects, in drag order — for the admin. */
function portfolio_all(): array
{
    $pdo = db();
    if (!$pdo) {
        return [];
    }
    try {
        return $pdo->query('SELECT * FROM portfolio_projects ORDER BY sort_order, id')->fetchAll();
    } catch (PDOException $e) {
        error_log('[portfolio] all: ' . $e->getMessage());
        return [];
    }
}

/** Enabled + featured projects, in drag order — feeds the Website Designing slider. */
function portfolio_featured(): array
{
    $pdo = db();
    if (!$pdo) {
        return [];
    }
    try {
        return $pdo->query(
            'SELECT * FROM portfolio_projects WHERE is_enabled = 1 AND is_featured = 1 ORDER BY sort_order, id'
        )->fetchAll();
    } catch (PDOException $e) {
        error_log('[portfolio] featured: ' . $e->getMessage());
        return [];
    }
}

/** Enabled projects for the public page — featured first, then drag order. */
function portfolio_public(): array
{
    $pdo = db();
    if (!$pdo) {
        return [];
    }
    try {
        return $pdo->query(
            'SELECT * FROM portfolio_projects WHERE is_enabled = 1 ORDER BY is_featured DESC, sort_order, id'
        )->fetchAll();
    } catch (PDOException $e) {
        error_log('[portfolio] public: ' . $e->getMessage());
        return [];
    }
}

function portfolio_find(int $id): ?array
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return null;
    }
    try {
        $st = $pdo->prepare('SELECT * FROM portfolio_projects WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    } catch (PDOException $e) {
        error_log('[portfolio] find: ' . $e->getMessage());
        return null;
    }
}

function portfolio_create(array $d): int|false
{
    $pdo = db();
    if (!$pdo) {
        return false;
    }
    try {
        $next = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), -1) + 1 FROM portfolio_projects')->fetchColumn();
        $st = $pdo->prepare(
            'INSERT INTO portfolio_projects (title, image_path, image_alt, website_url, info, client_name, is_featured, is_enabled, sort_order)
             VALUES (:title, :image_path, :image_alt, :website_url, :info, :client_name, :is_featured, :is_enabled, :sort_order)'
        );
        $st->execute([
            ':title'       => $d['title'],
            ':image_path'  => $d['image_path'] ?? '',
            ':image_alt'   => $d['image_alt'] ?? '',
            ':website_url' => $d['website_url'] ?? '',
            ':info'        => ($d['info'] ?? '') !== '' ? $d['info'] : null,
            ':client_name' => $d['client_name'] ?? '',
            ':is_featured' => !empty($d['is_featured']) ? 1 : 0,
            ':is_enabled'  => !empty($d['is_enabled']) ? 1 : 0,
            ':sort_order'  => $d['sort_order'] ?? $next,
        ]);
        return (int) $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log('[portfolio] create: ' . $e->getMessage());
        return false;
    }
}

function portfolio_update(int $id, array $d): bool
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return false;
    }
    try {
        $st = $pdo->prepare(
            'UPDATE portfolio_projects SET title = :title, image_path = :image_path, image_alt = :image_alt,
                    website_url = :website_url, info = :info, client_name = :client_name,
                    is_featured = :is_featured, is_enabled = :is_enabled
             WHERE id = :id'
        );
        return $st->execute([
            ':title'       => $d['title'],
            ':image_path'  => $d['image_path'] ?? '',
            ':image_alt'   => $d['image_alt'] ?? '',
            ':website_url' => $d['website_url'] ?? '',
            ':info'        => ($d['info'] ?? '') !== '' ? $d['info'] : null,
            ':client_name' => $d['client_name'] ?? '',
            ':is_featured' => !empty($d['is_featured']) ? 1 : 0,
            ':is_enabled'  => !empty($d['is_enabled']) ? 1 : 0,
            ':id'          => $id,
        ]);
    } catch (PDOException $e) {
        error_log('[portfolio] update: ' . $e->getMessage());
        return false;
    }
}

function portfolio_delete(int $id): bool
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return false;
    }
    $row = portfolio_find($id);
    try {
        $ok = $pdo->prepare('DELETE FROM portfolio_projects WHERE id = ?')->execute([$id]);
        if ($ok && $row) {
            hero_media_unlink_upload($row['image_path'] ?? ''); // only removes files inside /assets/uploads/
        }
        return (bool) $ok;
    } catch (PDOException $e) {
        error_log('[portfolio] delete: ' . $e->getMessage());
        return false;
    }
}

function portfolio_set_flag(int $id, string $col, bool $on): bool
{
    if (!in_array($col, ['is_enabled', 'is_featured'], true)) {
        return false;
    }
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return false;
    }
    try {
        return $pdo->prepare("UPDATE portfolio_projects SET {$col} = ? WHERE id = ?")->execute([$on ? 1 : 0, $id]);
    } catch (PDOException $e) {
        error_log('[portfolio] flag: ' . $e->getMessage());
        return false;
    }
}

/** Apply a new order: sort_order becomes the position in $ids. */
function portfolio_reorder(array $ids): bool
{
    $pdo = db();
    if (!$pdo) {
        return false;
    }
    try {
        $pdo->beginTransaction();
        $st = $pdo->prepare('UPDATE portfolio_projects SET sort_order = ? WHERE id = ?');
        foreach (array_values($ids) as $pos => $id) {
            $st->execute([$pos, (int) $id]);
        }
        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('[portfolio] reorder: ' . $e->getMessage());
        return false;
    }
}

/* ---------- images/portfolio/ folder ---------------------------- */

function portfolio_folder_images(): array
{
    $dir = dirname(__DIR__) . '/images/portfolio';
    if (!is_dir($dir)) {
        return [];
    }
    $files = glob($dir . '/*.{jpg,jpeg,JPG,JPEG,png,PNG,webp,WEBP,gif,GIF}', GLOB_BRACE) ?: [];
    $out = [];
    foreach ($files as $f) {
        $out[basename($f)] = url('/images/portfolio/' . rawurlencode(basename($f)));
    }
    ksort($out);
    return $out;
}

/** A tidy project title guessed from an image filename. */
function portfolio_title_from_filename(string $file): string
{
    $n = pathinfo($file, PATHINFO_FILENAME);
    $n = preg_replace('~-\d+x\d+$~', '', $n);
    $n = preg_replace('~-(?:after|before)(?:-\d+)?$~i', '', $n);
    $n = preg_replace('~-\d+$~', '', $n);
    $n = preg_replace('~\.(?:com|ca|net|org|io|co\.uk|co|us)$~i', '', $n);
    $n = trim(preg_replace('~\s+~', ' ', str_replace(['-', '_', '.'], ' ', $n)));
    return $n === '' ? 'Untitled project' : ucwords($n);
}

/** Create a disabled draft for every folder image not already used. Returns the count added. */
function portfolio_import_folder(): int
{
    $pdo = db();
    if (!$pdo) {
        return 0;
    }
    $used = [];
    foreach (portfolio_all() as $p) {
        $used[$p['image_path']] = true;
    }
    $added = 0;
    foreach (portfolio_folder_images() as $file => $urlPath) {
        if (isset($used[$urlPath])) {
            continue;
        }
        $ok = portfolio_create([
            'title'       => portfolio_title_from_filename($file),
            'image_path'  => $urlPath,
            'image_alt'   => portfolio_title_from_filename($file) . ' website',
            'website_url' => '',
            'info'        => '',
            'client_name' => '',
            'is_featured' => 0,
            'is_enabled'  => 0,
        ]);
        if ($ok) {
            $added++;
        }
    }
    return $added;
}
