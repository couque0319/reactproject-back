<?php
require_once '../db_connect.php';
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents("php://input"), true);

$id = isset($input['id']) ? (int)$input['id'] : null;
$role = isset($input['role']) ? trim($input['role']) : null;

// 정확하게 유효성 체크
if ($id === null || $id <= 0 || !$role || !in_array($role, ['user', 'admin'])) {
  echo json_encode([
    'success' => false,
    'message' => "잘못된 요청입니다",
    'debug' => ['id' => $id, 'role' => $role]
  ]);
  exit;
}

try {
  $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
  $success = $stmt->execute([$role, $id]);

  echo json_encode([
    'success' => $success,
    'message' => $success ? '권한 변경 성공' : '쿼리 실패',
    'debug' => ['id' => $id, 'role' => $role]
  ]);
} catch (PDOException $e) {
  echo json_encode([
    'success' => false,
    'message' => '쿼리 오류: ' . $e->getMessage()
  ]);
}
