<?php
/* ===========================================================================
   homepage_video — the intro video shown in the homepage hero box
   ---------------------------------------------------------------------------
   Independent of the per-page hero_media system. Stored in site_settings:
     home_video_enabled    '1' | '0'
     home_video_type       'youtube' | 'file'
     home_video_youtube_id  11-char id
     home_video_src         file URL (mp4 / webm)
     home_video_poster      poster image URL (file videos)
     home_video_label       caption text
   =========================================================================== */

require_once __DIR__ . '/db.php';

/** Raw config for the admin form. */
function homepage_video_config(): array
{
    return [
        'enabled'    => setting('home_video_enabled', '0') === '1',
        'type'       => setting('home_video_type', 'youtube') === 'file' ? 'file' : 'youtube',
        'youtube_id' => setting('home_video_youtube_id', ''),
        'src'        => setting('home_video_src', ''),
        'poster'     => setting('home_video_poster', ''),
        'label'      => setting('home_video_label', 'See Eformics in 90 seconds'),
    ];
}

/** The playable config when a homepage video should show, otherwise null. */
function homepage_video(): ?array
{
    $c = homepage_video_config();
    if (!$c['enabled']) {
        return null;
    }
    if ($c['type'] === 'youtube' && $c['youtube_id'] !== '') {
        return $c;
    }
    if ($c['type'] === 'file' && $c['src'] !== '') {
        return $c;
    }
    return null;
}

/** Rendered player markup for the homepage hero box, or '' when none. */
function homepage_video_html(): string
{
    $v = homepage_video();
    if (!$v) {
        return '';
    }

    if ($v['type'] === 'youtube') {
        $src   = 'https://www.youtube-nocookie.com/embed/' . rawurlencode($v['youtube_id']) . '?rel=0';
        $title = $v['label'] !== '' ? $v['label'] : 'Video';
        return '<iframe src="' . e($src) . '" title="' . e($title) . '"'
             . ' loading="lazy" referrerpolicy="strict-origin-when-cross-origin"'
             . ' allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"'
             . ' allowfullscreen></iframe>';
    }

    $poster = $v['poster'] !== '' ? ' poster="' . e($v['poster']) . '"' : '';
    return '<video src="' . e($v['src']) . '"' . $poster . ' controls playsinline preload="metadata"></video>';
}
