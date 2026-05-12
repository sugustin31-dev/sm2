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
$rating  = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1, 'max_range' => 5]]);

$name    = strip_tags($name);
$message = strip_tags($message);

if ($rating === false || $rating === null) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Rating debe ser entre 1 y 5']);
    exit;
}

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

$stmt = $pdo->prepare('INSERT INTO comments (name, message, rating) VALUES (:name, :message, :rating)');
$stmt->execute(['name' => $name, 'message' => $message, 'rating' => $rating]);

echo json_encode(['success' => true, 'id' => (int) $pdo->lastInsertId()]);
