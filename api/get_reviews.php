<?php
require_once '../db_connect.php';
header('Content-Type: application/json');

$game_id = $_GET['game_id'] ?? null;

if (!$game_id) {
  echo json_encode(['error' => 'game_id 필요']);
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM game_reviews WHERE game_id = ? ORDER BY created_at DESC");
$stmt->execute([$game_id]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
