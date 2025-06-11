<?php
require_once '../db_connect.php';
header('Content-Type: application/json');
session_start();

$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
  $_SESSION['username'] = $user['username'];
  $_SESSION['nickname'] = $user['nickname'];
  $_SESSION['role'] = $user['role'];

  echo json_encode([
    'success' => true,
    'user' => [
      'username' => $user['username'],
      'nickname' => $user['nickname'],
      'role' => $user['role']
    ]
  ]);
} else {
  echo json_encode(['success' => false, 'message' => '아이디 또는 비밀번호가 틀렸습니다.']);
}
