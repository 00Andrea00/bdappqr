<?php
require '../../config/database.php';
require '../../config/cors.php';

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'];
$password = $input['password'];
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch();
if ($user && password_verify($password, $user['password'])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Login exitoso', 'user' => $user]);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Credenciales incorrectas']);
}
