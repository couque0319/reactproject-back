<?php
require_once __DIR__ . '/../db_connect.php';
header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? '');
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

// 유효성 검사
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '유효한 이메일 형식이 아닙니다']);
    exit;
}

if (strlen($username) < 4 || strlen($username) > 20) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID는 4~20자여야 합니다']);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '비밀번호는 최소 6자 이상이어야 합니다']);
    exit;
}

// 중복 체크
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => '이미 등록된 이메일입니다']);
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => '이미 사용 중인 ID입니다']);
    exit;
}

// 비밀번호 해싱
$hashed = password_hash($password, PASSWORD_DEFAULT);

// 저장
$stmt = $pdo->prepare("INSERT INTO users (email, username, password, signup_method, role) VALUES (?, ?, ?, 'manual', 'user')");
$success = $stmt->execute([$email, $username, $hashed]);

echo json_encode(['success' => $success]);
?>
