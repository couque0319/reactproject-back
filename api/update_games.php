<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../db_connect.php';

$apiKey = ''; // 🔑 여기에 본인의 RAWG API 키를 입력하세요
$totalPages = 3; // 최대 120개 게임 가져오기
$pageSize = 40;

// cURL 함수 정의
function curl_get($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo '❌ cURL 오류: ' . curl_error($ch);
        curl_close($ch);
        exit;
    }

    curl_close($ch);
    return $response;
}

for ($page = 1; $page <= $totalPages; $page++) {
    $apiUrl = "https://api.rawg.io/api/games?key={$apiKey}&page={$page}&page_size={$pageSize}";
    $response = curl_get($apiUrl);
    $data = json_decode($response, true);

    if (!isset($data['results'])) {
        echo "❌ page $page: 응답에 results 없음<br>";
        echo "<pre>"; print_r($data); echo "</pre>";
        continue;
    }

    echo "✅ page $page: " . count($data['results']) . "개 수신<br>";

    foreach ($data['results'] as $game) {
        $title = $game['name'];
        $meta_score = $game['metacritic'] ?? null;
        $user_score = $game['rating'] ?? null;
        $image_url = $game['background_image'] ?? 'https://via.placeholder.com/300x160?text=No+Image';

        // 상세 정보 가져오기 (description)
        $detailUrl = "https://api.rawg.io/api/games/{$game['id']}?key={$apiKey}";
        $detailData = json_decode(curl_get($detailUrl), true);
        $description = $detailData['description_raw'] ?? '';

        // INSERT 또는 UPDATE
        $stmt = $pdo->prepare("INSERT INTO games (title, description, meta_score, user_score, image_url)
                               VALUES (?, ?, ?, ?, ?)
                               ON DUPLICATE KEY UPDATE
                               description = VALUES(description),
                               meta_score = VALUES(meta_score),
                               user_score = VALUES(user_score),
                               image_url = VALUES(image_url)");

        if ($stmt->execute([$title, $description, $meta_score, $user_score, $image_url])) {
            echo "✅ 저장됨: $title<br>";
        } else {
            echo "❌ 저장 실패: $title<br>";
        }
    }

    echo "<hr>";
}

echo "<br>🎉 전체 완료!";
?>