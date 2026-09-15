<?php
/* ===========================================================================
   admin/upload.php — shared AJAX upload endpoint
   Used by the "Upload" buttons next to image / file URL fields.
   Returns JSON: { ok: true, url: "..." }  or  { ok: false, error: "..." }
   =========================================================================== */

require __DIR__ . '/_bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Not signed in.']);
    exit;
}
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !csrf_check()) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Bad request — reload the page and try again.']);
    exit;
}

$kind = $_POST['kind'] ?? 'image';
$kind = in_array($kind, ['image', 'video', 'both'], true) ? $kind : 'image';

[$url, $err] = admin_upload('file', $kind);

if ($err) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => ucfirst($err)]);
    exit;
}
if (!$url) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'No file was received.']);
    exit;
}

echo json_encode(['ok' => true, 'url' => $url]);
