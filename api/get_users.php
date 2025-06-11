<?php
require_once '../db_connect.php';
header('Content-Type: application/json');

$stmt = $pdo->prepare("SELECT id, username, nickname, role FROM users");
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($users);
