<?php
// 에러 출력 설정
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// 사용자 입력 받기
$input = json_decode(file_get_contents("php://input"), true);
$question = trim($input['question'] ?? '');

if (!$question) {
  echo json_encode(['error' => '질문이 비어있습니다.']);
  exit;
}

$apiKey = '';  // ← 반드시 본인 키로 교체

$endpoint = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro-002:generateContent?key=$apiKey";

$data = [
  'contents' => [
    [
      'parts' => [
        ['text' => $question]
      ]
    ]
  ]
];

$ch = curl_init($endpoint);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
  CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

// ✅ 성공 응답일 때
if ($httpCode === 200 && isset($result['candidates'][0]['content']['parts'][0]['text'])) {
  echo json_encode([
    'response' => $result['candidates'][0]['content']['parts'][0]['text']
  ]);
} else {
  echo json_encode([
    'error' => $result['error']['message'] ?? 'Gemini 응답 오류',
    'httpCode' => $httpCode,
    'raw' => $response // 필요 시 제거 가능
  ]);
}
