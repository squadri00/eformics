<?php
/* AJAX endpoint — save a new portfolio order. POST: ids[]=1&ids[]=5&... + csrf */
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

$ids = array_map('intval', (array) ($_POST['ids'] ?? []));
$ids = array_values(array_filter($ids, static fn ($n) => $n > 0));

if (!$ids) {
    echo json_encode(['ok' => false, 'error' => 'No order received.']);
    exit;
}

echo json_encode(['ok' => portfolio_reorder($ids)]);
