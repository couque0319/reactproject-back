<?php
require_once(__DIR__ . '/../db_connect.php');
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$id = $_GET['id'] ?? null;
if (!$id) {
  echo json_encode(["error" => "게임 ID 누락"]);
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$id]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if ($game) {
  echo json_encode($game);
} else {
  echo json_encode(["error" => "게임을 찾을 수 없습니다."]);
}
