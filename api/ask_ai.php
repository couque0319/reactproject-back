<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$question = trim($input['question'] ?? '');

if (!$question) {
  echo json_encode(['error' => '질문이 비어있습니다.']);
  exit;
}

// 👉 여기에 본인의 Google AI API 키 입력
$apiKey = 'AIzaSyCOdZQkwRaVXqgVVwJ4lF_XH_PeNd7Nw_Q';

$endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=$apiKey";

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

// ✅ 응답 정상
if ($httpCode === 200 && isset($result['candidates'][0]['content']['parts'][0]['text'])) {
  echo json_encode([
    'response' => $result['candidates'][0]['content']['parts'][0]['text']
  ]);
} else {
  echo json_encode([
    'error' => $result['error']['message'] ?? 'Gemini 응답 오류',
    'httpCode' => $httpCode
  ]);
}
