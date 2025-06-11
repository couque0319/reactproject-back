<?php
require_once '../db_connect.php';
header('Content-Type: application/json');
session_start();

$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';
$nickname = $input['nickname'] ?? '';

if (!$username || !$password || !$nickname) {
  echo json_encode(['success' => false, 'message' => '모든 항목을 입력해주세요.']);
  exit;
}

// 중복 체크
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetch()) {
  echo json_encode(['success' => false, 'message' => '이미 존재하는 아이디입니다.']);
  exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password, nickname, role) VALUES (?, ?, ?, 'user')");
$success = $stmt->execute([$username, $hashed, $nickname]);

echo json_encode(['success' => $success]);
