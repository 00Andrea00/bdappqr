<?php
require '../../config/cors.php';
require '../../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

$email = $input['email'];
$name = $input['name'];

$sql = "UPDATE users SET name = ? WHERE email = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$name, $email])) {
  header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Nombre cambiado exitosamente']);
} else {
  header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Error al cambiar el nombre']);
}
