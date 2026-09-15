<?php
/* AJAX — save a new before/after order. POST: ids[]=…&csrf */
require __DIR__ . '/../_bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Not signed in.']);
    exit;
}
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !csrf_check()) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Bad request.']);
    exit;
}

$ids = array_values(array_filter(array_map('intval', (array) ($_POST['ids'] ?? [])), static fn ($n) => $n > 0));
echo json_encode(['ok' => $ids ? va_ba_reorder($ids) : false]);
