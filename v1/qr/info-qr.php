<?php
require '../../config/cors.php';
require '../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['name_qr'])) {
  echo json_encode(['error' => 'Parámetro name_qr faltante.']);
  exit;
}

$name_qr = $input['name_qr'];
$sql = "SELECT id, name_qr, color_qr, description, created_by, created_at FROM qrs WHERE name_qr = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$name_qr]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
  $created_by_id = $result["created_by"];

  $sql_user = "SELECT email FROM users WHERE id = ?";
  $stmt_user = $pdo->prepare($sql_user);
  $stmt_user->execute([$created_by_id]);
  $user_result = $stmt_user->fetch(PDO::FETCH_ASSOC);

  $created_by_email = $user_result ? $user_result["email"] : null;

  echo json_encode([
    'id' => $result["id"],
    'name_qr' => $result["name_qr"],
    'color_qr' => $result["color_qr"],
    'description' => $result["description"],
    'created_by' => $created_by_email,
    'created_at' => $result["created_at"],
  ]);
} else {
  echo json_encode(['message' => "No se encontró el qr con el nombre proporcionado."]);
}
