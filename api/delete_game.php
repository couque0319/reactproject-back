<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? 0;

if (!$id) {
  echo json_encode(['success' => false, 'message' => '게임 ID 누락']);
  exit;
}

$stmt = $pdo->prepare("DELETE FROM games WHERE id = ?");
$success = $stmt->execute([$id]);

echo json_encode(['success' => $success]);