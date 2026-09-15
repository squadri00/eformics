<?php
/* ===========================================================================
   visual_enhance — two admin-managed galleries for the Visual Enhancement
   service page: before/after image pairs, and video clips.
   Loaded by partials/config.php.
   =========================================================================== */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/hero_media.php';   // hero_youtube_id(), hero_media_unlink_upload()

/* -----------------------------------------------------------------
   BEFORE / AFTER pairs  (table: va_before_after)
   ----------------------------------------------------------------- */

function va_ba_all(): array
{
    $pdo = db();
    if (!$pdo) {
        return [];
    }
    try {
        return $pdo->query('SELECT * FROM va_before_after ORDER BY sort_order, id')->fetchAll();
    } catch (PDOException $e) {
        error_log('[va] ba_all: ' . $e->getMessage());
        return [];
    }
}

function va_ba_public(): array
{
    return array_values(array_filter(va_ba_all(), static fn ($r) => (int) $r['is_enabled'] === 1));
}

function va_ba_find(int $id): ?array
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return null;
    }
    try {
        $st = $pdo->prepare('SELECT * FROM va_before_after WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    } catch (PDOException $e) {
        error_log('[va] ba_find: ' . $e->getMessage());
        return null;
    }
}

function va_ba_create(array $d): int|false
{
    $pdo = db();
    if (!$pdo) {
        return false;
    }
    try {
        $next = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), -1) + 1 FROM va_before_after')->fetchColumn();
        $st = $pdo->prepare(
            'INSERT INTO va_before_after (title, before_path, after_path, before_alt, after_alt, is_enabled, sort_order)
             VALUES (:t, :bp, :ap, :ba, :aa, :en, :so)'
        );
        $st->execute([
            ':t'  => $d['title'],
            ':bp' => $d['before_path'] ?? '',
            ':ap' => $d['after_path'] ?? '',
            ':ba' => $d['before_alt'] ?? '',
            ':aa' => $d['after_alt'] ?? '',
            ':en' => !empty($d['is_enabled']) ? 1 : 0,
            ':so' => $d['sort_order'] ?? $next,
        ]);
        return (int) $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log('[va] ba_create: ' . $e->getMessage());
        return false;
    }
}

function va_ba_update(int $id, array $d): bool
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return false;
    }
    try {
        $st = $pdo->prepare(
            'UPDATE va_before_after SET title=:t, before_path=:bp, after_path=:ap,
                    before_alt=:ba, after_alt=:aa, is_enabled=:en WHERE id=:id'
        );
        return $st->execute([
            ':t'  => $d['title'],
            ':bp' => $d['before_path'] ?? '',
            ':ap' => $d['after_path'] ?? '',
            ':ba' => $d['before_alt'] ?? '',
            ':aa' => $d['after_alt'] ?? '',
            ':en' => !empty($d['is_enabled']) ? 1 : 0,
            ':id' => $id,
        ]);
    } catch (PDOException $e) {
        error_log('[va] ba_update: ' . $e->getMessage());
        return false;
    }
}

function va_ba_delete(int $id): bool
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return false;
    }
    $row = va_ba_find($id);
    try {
        $ok = $pdo->prepare('DELETE FROM va_before_after WHERE id = ?')->execute([$id]);
        if ($ok && $row) {
            hero_media_unlink_upload($row['before_path'] ?? '');
            hero_media_unlink_upload($row['after_path'] ?? '');
        }
        return (bool) $ok;
    } catch (PDOException $e) {
        error_log('[va] ba_delete: ' . $e->getMessage());
        return false;
    }
}

function va_ba_set_enabled(int $id, bool $on): bool
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return false;
    }
    try {
        return $pdo->prepare('UPDATE va_before_after SET is_enabled = ? WHERE id = ?')->execute([$on ? 1 : 0, $id]);
    } catch (PDOException $e) {
        error_log('[va] ba_flag: ' . $e->getMessage());
        return false;
    }
}

function va_ba_reorder(array $ids): bool
{
    return va_reorder('va_before_after', $ids);
}

/* -----------------------------------------------------------------
   VIDEO clips  (table: va_videos)
   source = 'upload' (video_path) | 'embed' (video_url -> YouTube/Vimeo)
   ----------------------------------------------------------------- */

function va_vid_all(): array
{
    $pdo = db();
    if (!$pdo) {
        return [];
    }
    try {
        return $pdo->query('SELECT * FROM va_videos ORDER BY sort_order, id')->fetchAll();
    } catch (PDOException $e) {
        error_log('[va] vid_all: ' . $e->getMessage());
        return [];
    }
}

function va_vid_public(): array
{
    return array_values(array_filter(va_vid_all(), static fn ($r) => (int) $r['is_enabled'] === 1));
}

function va_vid_find(int $id): ?array
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return null;
    }
    try {
        $st = $pdo->prepare('SELECT * FROM va_videos WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    } catch (PDOException $e) {
        error_log('[va] vid_find: ' . $e->getMessage());
        return null;
    }
}

function va_vid_create(array $d): int|false
{
    $pdo = db();
    if (!$pdo) {
        return false;
    }
    try {
        $next = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), -1) + 1 FROM va_videos')->fetchColumn();
        $st = $pdo->prepare(
            'INSERT INTO va_videos (title, source, video_path, video_url, poster_path, is_enabled, sort_order)
             VALUES (:t, :src, :vp, :vu, :pp, :en, :so)'
        );
        $st->execute([
            ':t'   => $d['title'],
            ':src' => $d['source'] === 'upload' ? 'upload' : 'embed',
            ':vp'  => $d['video_path'] ?? '',
            ':vu'  => $d['video_url'] ?? '',
            ':pp'  => $d['poster_path'] ?? '',
            ':en'  => !empty($d['is_enabled']) ? 1 : 0,
            ':so'  => $d['sort_order'] ?? $next,
        ]);
        return (int) $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log('[va] vid_create: ' . $e->getMessage());
        return false;
    }
}

function va_vid_update(int $id, array $d): bool
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return false;
    }
    try {
        $st = $pdo->prepare(
            'UPDATE va_videos SET title=:t, source=:src, video_path=:vp, video_url=:vu,
                    poster_path=:pp, is_enabled=:en WHERE id=:id'
        );
        return $st->execute([
            ':t'   => $d['title'],
            ':src' => $d['source'] === 'upload' ? 'upload' : 'embed',
            ':vp'  => $d['video_path'] ?? '',
            ':vu'  => $d['video_url'] ?? '',
            ':pp'  => $d['poster_path'] ?? '',
            ':en'  => !empty($d['is_enabled']) ? 1 : 0,
            ':id'  => $id,
        ]);
    } catch (PDOException $e) {
        error_log('[va] vid_update: ' . $e->getMessage());
        return false;
    }
}

function va_vid_delete(int $id): bool
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return false;
    }
    $row = va_vid_find($id);
    try {
        $ok = $pdo->prepare('DELETE FROM va_videos WHERE id = ?')->execute([$id]);
        if ($ok && $row) {
            hero_media_unlink_upload($row['video_path'] ?? '');
            hero_media_unlink_upload($row['poster_path'] ?? '');
        }
        return (bool) $ok;
    } catch (PDOException $e) {
        error_log('[va] vid_delete: ' . $e->getMessage());
        return false;
    }
}

function va_vid_set_enabled(int $id, bool $on): bool
{
    $pdo = db();
    if (!$pdo || $id <= 0) {
        return false;
    }
    try {
        return $pdo->prepare('UPDATE va_videos SET is_enabled = ? WHERE id = ?')->execute([$on ? 1 : 0, $id]);
    } catch (PDOException $e) {
        error_log('[va] vid_flag: ' . $e->getMessage());
        return false;
    }
}

function va_vid_reorder(array $ids): bool
{
    return va_reorder('va_videos', $ids);
}

/* -----------------------------------------------------------------
   shared helpers
   ----------------------------------------------------------------- */

/** Apply a new drag order to $table: sort_order = position in $ids. */
function va_reorder(string $table, array $ids): bool
{
    if (!in_array($table, ['va_before_after', 'va_videos'], true)) {
        return false;
    }
    $pdo = db();
    if (!$pdo) {
        return false;
    }
    try {
        $pdo->beginTransaction();
        $st = $pdo->prepare("UPDATE {$table} SET sort_order = ? WHERE id = ?");
        foreach (array_values($ids) as $pos => $id) {
            $st->execute([$pos, (int) $id]);
        }
        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("[va] reorder {$table}: " . $e->getMessage());
        return false;
    }
}

/** Vimeo numeric id from a URL or bare id. '' if not recognised. */
function va_vimeo_id(string $input): string
{
    $input = trim($input);
    if ($input === '') {
        return '';
    }
    if (ctype_digit($input)) {
        return $input;
    }
    if (preg_match('~vimeo\.com/(?:video/|channels/[^/]+/|groups/[^/]+/videos/)?(\d+)~i', $input, $m)) {
        return $m[1];
    }
    return '';
}

/**
 * Resolve a video row to what the front-end needs.
 * @return array{type: 'file'|'youtube'|'vimeo'|'', src: string, poster: string}
 */
function va_video_embed(array $row): array
{
    $poster = $row['poster_path'] ?? '';

    if (($row['source'] ?? '') === 'upload' && ($row['video_path'] ?? '') !== '') {
        return ['type' => 'file', 'src' => $row['video_path'], 'poster' => $poster];
    }

    $url = trim((string) ($row['video_url'] ?? ''));
    if ($url === '') {
        return ['type' => '', 'src' => '', 'poster' => $poster];
    }
    if ($yt = hero_youtube_id($url)) {
        // muted + loop + autoplay so it behaves like the uploaded clips
        return [
            'type'   => 'youtube',
            'src'    => 'https://www.youtube-nocookie.com/embed/' . $yt
                        . '?autoplay=1&mute=1&loop=1&playlist=' . $yt . '&controls=1&modestbranding=1&rel=0&playsinline=1',
            'poster' => $poster,
        ];
    }
    if ($vm = va_vimeo_id($url)) {
        return [
            'type'   => 'vimeo',
            'src'    => 'https://player.vimeo.com/video/' . $vm . '?autoplay=1&muted=1&loop=1&byline=0&title=0&portrait=0',
            'poster' => $poster,
        ];
    }
    return ['type' => '', 'src' => '', 'poster' => $poster];
}
