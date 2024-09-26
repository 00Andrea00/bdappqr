<?php
// Permitir CORS
require '../../config/cors.php'; // Incluye tu archivo de CORS
require '../../config/database.php'; // Incluye tu archivo de configuración de base de datos

// Si se envía una solicitud OPTIONS, responde con los encabezados CORS permitidos
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit();
}

// Obtener el cuerpo de la solicitud y decodificar el JSON
$input = json_decode(file_get_contents('php://input'), true);

// Validar que se proporcionen los campos requeridos
if (isset($input['role']) && isset($input['email'])) {
  $email = $input['email'];
  $role = $input['role'];

  // Consulta SQL para verificar el rol actual del usuario
  $sqlCheckRole = "SELECT role FROM users WHERE email = ?";
  $stmtCheckRole = $pdo->prepare($sqlCheckRole);
  $stmtCheckRole->execute([$email]);
  $currentRole = $stmtCheckRole->fetchColumn();

  // Verificar si el usuario existe
  if ($currentRole === false) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(404);
    echo json_encode(['message' => 'Usuario no encontrado']);
    exit();
  }

  // Verificar si el rol a actualizar es diferente al rol actual
  if ($currentRole === $role) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(400);
    echo json_encode(['message' => 'El usuario ya tiene asignado este rol']);
    exit();
  }

  // Consulta SQL para actualizar el usuario
  $sql = "UPDATE users SET role = ? WHERE email = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$role, $email]);

  // Verificar si se actualizó el rol
  if ($stmt->rowCount() > 0) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Rol actualizado exitosamente']);
  } else {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode(['message' => 'Error al actualizar el rol']);
  }
} else {
  header('Content-Type: application/json; charset=utf-8');
  http_response_code(400);
  echo json_encode(['message' => 'Datos incompletos']);
}
