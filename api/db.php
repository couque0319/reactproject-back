<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'metacritic';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'DB 연결 실패: ' . $conn->connect_error]));
}
?>