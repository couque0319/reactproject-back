<?php
require_once '../db_connect.php';
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents("php://input"), true);

$game_id = $input['game_id'] ?? null;
$nickname = $input['nickname'] ?? null;
$rating = $input['rating'] ?? null;
$content = $input['content'] ?? null;

if (!$game_id || !$nickname || !$rating || !$content) {
  echo json_encode(['success' => false, 'message' => '모든 필드를 입력해주세요.']);
  exit;
}

$stmt = $pdo->prepare("INSERT INTO game_reviews (game_id, nickname, rating, content) VALUES (?, ?, ?, ?)");
$success = $stmt->execute([$game_id, $nickname, $rating, $content]);

echo json_encode(['success' => $success]);
