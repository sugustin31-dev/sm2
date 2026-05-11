<?php

require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$name    = trim($_POST['name'] ?? '');
$message = trim($_POST['message'] ?? '');

$name    = strip_tags($name);
$message = strip_tags($message);

if ($name === '' || $message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Name and message are required']);
    exit;
}

if (mb_strlen($name) > 60) {
    http_response_code(400);
    echo json_encode(['error' => 'Name too long (max 60 chars)']);
    exit;
}

if (mb_strlen($message) > 500) {
    http_response_code(400);
    echo json_encode(['error' => 'Message too long (max 500 chars)']);
    exit;
}

$stmt = $pdo->prepare('INSERT INTO comments (name, message) VALUES (:name, :message)');
$stmt->execute(['name' => $name, 'message' => $message]);

echo json_encode(['success' => true, 'id' => (int) $pdo->lastInsertId()]);
