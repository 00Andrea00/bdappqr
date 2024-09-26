<?php
require '../../config/cors.php';
require '../../config/database.php';

$query = $_GET['query'];

$sql = "SELECT * FROM users WHERE name LIKE '%$query%' OR role LIKE '%$query%'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['users' => $users]);