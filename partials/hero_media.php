<?php
/* ===========================================================================
   hero_media — per-page hero banner media (right-hand side of page heroes)
   ---------------------------------------------------------------------------
   Loaded by partials/config.php. Every function returns safe empty values
   when the database is unavailable.
   =========================================================================== */

require_once __DIR__ . '/db.php';

/** Pages that can carry a hero-media slot: key => admin label. */
function page_hero_pages(): array
{
    return [
        'about'          => 'About / Who We Are',
        'contact'        => 'Contact',
        'meccora'        => 'Meccora (product)',
        'quotaire'       => 'Quotaire (product)',
        'chantley'       => 'Chantley (product)',
        'smart-qr-menu'  => 'Smart QR Menu (product)',
        'development'        => 'Custom Development (service)',
        'web-design'         => 'Website Designing (service)',
        'pwa'                => 'Progressive Web Apps (service)',
        'visual-enhancement' => 'Visual Enhancement (service)',
        'privacy-policy' => 'Privacy Policy',
        'terms'          => 'Terms of Use',
        'sitemap'        => 'Sitemap',
    ];
}

/** Media types a hero item can be. */
function hero_media_types(): array
{
    return ['image' => 'Image', 'video' => 'Video file', 'youtube' => 'YouTube'];
}

/** Friendly label for a media_type value. */
function hero_media_type_label(string $type): string
{
    return hero_media_types()[$type] ?? ucfirst($type);
}

/**
 * Pull an 11-character YouTube video id out of a pasted URL or a bare id.
 * Handles watch?v=, youtu.be/, /embed/, /shorts/, /live/ and plain ids.
 */
function hero_youtube_id(string $input): string
{
    $input = trim($input);
    if ($input === '') {
        return '';
    }
    if (preg_match('~^[A-Za-z0-9_-]{11}$~', $input)) {
        return $input;
    }
    if (preg_match('~(?:v=|/(?:embed|shorts|live|v)/|youtu\.be/)([A-Za-z0-9_-]{11})~', $input, $m)) {
        return $m[1];
    }
    return '';
}

/**
 * Rendered right-hand media block for a page, or '' when nothing is live.
 * Drop the return value straight into the page-hero grid.
 */
function page_hero(string $pageKey): string
{
    $m = hero_media_active($pageKey);
    if (!$m) {
        return '';
    }

    if ($m['media_type'] === 'youtube' && ($m['youtube_id'] ?? '') !== '') {
        $src   = 'https://www.youtube-nocookie.com/embed/' . rawurlencode($m['youtube_id']) . '?rel=0';
        $title = $m['image_alt'] !== '' ? $m['image_alt'] : ($m['title'] !== '' ? $m['title'] : 'Video');
        $inner = '<div class="hero-embed"><iframe src="' . e($src) . '" title="' . e($title) . '"'
               . ' loading="lazy" referrerpolicy="strict-origin-when-cross-origin"'
               . ' allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"'
               . ' allowfullscreen></iframe></div>';
    } elseif ($m['media_type'] === 'video' && $m['video_path'] !== '') {
        $poster = $m['poster_path'] !== '' ? ' poster="' . e($m['poster_path']) . '"' : '';
        $inner  = '<video src="' . e($m['video_path']) . '"' . $poster
                . ' controls playsinline preload="metadata"></video>';
    } elseif ($m['media_type'] === 'image' && $m['image_path'] !== '') {
        $inner = '<img src="' . e($m['image_path']) . '" alt="' . e($m['image_alt']) . '">';
    } else {
        return '';
    }

    return '<div class="page-hero-media reveal">' . $inner . '</div>';
}

/* ---------- data access --------------------------------------------- */

/** All items across every page, grouped by page then active-first. */
function hero_media_all(): array
{
    $pdo = db();
    if (!$pdo) {
        return [];
    }
    try {
        return $pdo->query(
            'SELECT * FROM hero_media ORDER BY page, is_active DESC, updated_at DESC, id DESC'
        )->fetchAll();
    } catch (PDOException $e) {
        error_log('[hero_media] all: ' . $e->getMessage());
        return [];
    }
}

function hero_media_find(int $id): ?array
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return null;
    }
    try {
        $st = $pdo->prepare('SELECT * FROM hero_media WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    } catch (PDOException $e) {
        error_log('[hero_media] find: ' . $e->getMessage());
        return null;
    }
}

/** The live item for one page, or null. */
function hero_media_active(string $page): ?array
{
    $pdo = db();
    if (!$pdo || $page === '') {
        return null;
    }
    try {
        $st = $pdo->prepare(
            'SELECT * FROM hero_media WHERE page = ? AND is_active = 1 ORDER BY updated_at DESC LIMIT 1'
        );
        $st->execute([$page]);
        return $st->fetch() ?: null;
    } catch (PDOException $e) {
        error_log('[hero_media] active: ' . $e->getMessage());
        return null;
    }
}

/** Insert. Returns the new id, or false. */
function hero_media_create(array $d): int|false
{
    $pdo = db();
    if (!$pdo) {
        return false;
    }
    try {
        $st = $pdo->prepare(
            'INSERT INTO hero_media (page, title, media_type, image_path, image_alt, video_path, youtube_id, poster_path, is_active)
             VALUES (:page, :title, :media_type, :image_path, :image_alt, :video_path, :youtube_id, :poster_path, 0)'
        );
        $st->execute([
            ':page'        => $d['page'],
            ':title'       => $d['title'],
            ':media_type'  => isset(hero_media_types()[$d['media_type'] ?? '']) ? $d['media_type'] : 'image',
            ':image_path'  => $d['image_path']  ?? '',
            ':image_alt'   => $d['image_alt']   ?? '',
            ':video_path'  => $d['video_path']  ?? '',
            ':youtube_id'  => $d['youtube_id']  ?? '',
            ':poster_path' => $d['poster_path'] ?? '',
        ]);
        $id = (int) $pdo->lastInsertId();
        if (!empty($d['is_active'])) {
            hero_media_set_active($id);
        }
        return $id;
    } catch (PDOException $e) {
        error_log('[hero_media] create: ' . $e->getMessage());
        return false;
    }
}

/** Update fields. Syncs the active flag (scoped to the item's page). */
function hero_media_update(int $id, array $d): bool
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return false;
    }
    try {
        $st = $pdo->prepare(
            'UPDATE hero_media SET page = :page, title = :title, media_type = :media_type,
                    image_path = :image_path, image_alt = :image_alt,
                    video_path = :video_path, youtube_id = :youtube_id, poster_path = :poster_path
             WHERE id = :id'
        );
        $ok = $st->execute([
            ':page'        => $d['page'],
            ':title'       => $d['title'],
            ':media_type'  => isset(hero_media_types()[$d['media_type'] ?? '']) ? $d['media_type'] : 'image',
            ':image_path'  => $d['image_path']  ?? '',
            ':image_alt'   => $d['image_alt']   ?? '',
            ':video_path'  => $d['video_path']  ?? '',
            ':youtube_id'  => $d['youtube_id']  ?? '',
            ':poster_path' => $d['poster_path'] ?? '',
            ':id'          => $id,
        ]);
        if (!$ok) {
            return false;
        }
        if (!empty($d['is_active'])) {
            hero_media_set_active($id);
        } else {
            $pdo->prepare('UPDATE hero_media SET is_active = 0 WHERE id = ?')->execute([$id]);
        }
        return true;
    } catch (PDOException $e) {
        error_log('[hero_media] update: ' . $e->getMessage());
        return false;
    }
}

/** Delete a row and its uploaded files. */
function hero_media_delete(int $id): bool
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return false;
    }
    $row = hero_media_find($id);
    try {
        $ok = $pdo->prepare('DELETE FROM hero_media WHERE id = ?')->execute([$id]);
        if ($ok && $row) {
            foreach (['image_path', 'video_path', 'poster_path'] as $k) {
                hero_media_unlink_upload($row[$k] ?? '');
            }
        }
        return (bool) $ok;
    } catch (PDOException $e) {
        error_log('[hero_media] delete: ' . $e->getMessage());
        return false;
    }
}

/** Make one item live for its page; clears the flag on that page's other items. */
function hero_media_set_active(int $id): bool
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return false;
    }
    $row = hero_media_find($id);
    if (!$row) {
        return false;
    }
    try {
        $pdo->beginTransaction();
        $clr = $pdo->prepare('UPDATE hero_media SET is_active = 0 WHERE page = ? AND is_active = 1');
        $clr->execute([$row['page']]);
        $pdo->prepare('UPDATE hero_media SET is_active = 1 WHERE id = ?')->execute([$id]);
        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('[hero_media] set_active: ' . $e->getMessage());
        return false;
    }
}

/** Delete an uploaded file, but only inside /assets/uploads/. */
function hero_media_unlink_upload(string $publicUrl): void
{
    if ($publicUrl === '') {
        return;
    }
    $prefix = url('/assets/uploads/');
    if (strncmp($publicUrl, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $path = __DIR__ . '/../assets/uploads/' . basename($publicUrl);
    if (is_file($path)) {
        @unlink($path);
    }
}
