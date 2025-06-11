<?php
$host = 'localhost';
$dbname = 'metacritic';  // DB명 다시 한 번 확인
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  // 👉 에러 메시지를 클라이언트에게 전달
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'DB 연결 오류: ' . $e->getMessage()]);
  exit;
}
