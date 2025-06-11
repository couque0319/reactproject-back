<?php
require_once '../db_connect.php';
header('Content-Type: application/json');

$nickname = $_GET['nickname'] ?? '';

if (!$nickname) {
  echo json_encode(['error' => '닉네임이 필요합니다.']);
  exit;
}

$stmt = $pdo->prepare("
  SELECT r.id, r.rating, r.content, r.created_at, g.title AS game_title
  FROM game_reviews r
  JOIN games g ON r.game_id = g.id
  WHERE r.nickname = ?
  ORDER BY r.created_at DESC
");
$stmt->execute([$nickname]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
