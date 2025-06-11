<?php
session_start();
require_once '../db_connect.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$review_id = $input['id'] ?? null;
$nickname = $_SESSION['nickname'] ?? null;

if (!$review_id || !$nickname) {
  echo json_encode(['success' => false, 'message' => '인증 실패']);
  exit;
}

$stmt = $pdo->prepare("DELETE FROM game_reviews WHERE id = ? AND nickname = ?");
$success = $stmt->execute([$review_id, $nickname]);

echo json_encode(['success' => $success]);
