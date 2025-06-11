<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$username = $_GET['username'] ?? '';

if (!$username) {
  echo json_encode(['exists' => false, 'message' => '입력 누락']);
  exit;
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);

$exists = $stmt->fetch() !== false;
echo json_encode(['exists' => $exists]);
