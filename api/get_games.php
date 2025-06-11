<?php
// 에러 표시 설정 (개발환경용)
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// ✅ 정확한 상대경로로 DB 연결
require_once __DIR__ . '/../db_connect.php';

try {
    // 게임 데이터 가져오기
    $stmt = $pdo->prepare("SELECT id, title, description, image_url, meta_score, user_score FROM games");
    $stmt->execute();
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ⚠️ JSON 렌더링 불가능한 필드 제거 (선택적)
    /*
    foreach ($games as &$game) {
        foreach ($game as $key => $value) {
            if (is_object($value) || is_array($value)) {
                unset($game[$key]);
            }
        }
    }
    */

    echo json_encode($games);
} catch (Exception $e) {
    // ⛔ JSON 오류 응답
    http_response_code(500);
    echo json_encode([
        'error' => 'DB 오류: ' . $e->getMessage()
    ]);
}
