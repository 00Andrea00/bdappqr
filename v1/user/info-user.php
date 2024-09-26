<?php
require '../../config/cors.php';
require '../../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

$email = $input['email'];

$sql = "SELECT id, name, email, role, created_at FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode([
    'id' => $result["id"],
    'name' => $result["name"],
    'email' => $result["email"],
    'role' => $result["role"],
    'created_at' => $result["created_at"]
  ]);
} else {
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['message' => "No se encontró el usuario con el email proporcionado."]);
}
