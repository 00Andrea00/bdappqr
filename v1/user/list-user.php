<?php
require '../../config/cors.php';
require '../../config/database.php';
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$sql = "SELECT * FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['users' => $users]);
