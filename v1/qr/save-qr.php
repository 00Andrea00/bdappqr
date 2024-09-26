<?php

require '../../config/cors.php';
require '../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

// Verificar el método de la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Obtener el cuerpo de la solicitud
$input = json_decode(file_get_contents('php://input'), true);

// Verificar si los datos JSON fueron decodificados correctamente
if (!$input) {
    echo json_encode(['message' => 'Invalid JSON input']);
    http_response_code(400); // 400 Bad Request
    exit();
}

// Validación de entrada
$required_fields = ['name_qr', 'description', 'color_qr', 'created_by'];
foreach ($required_fields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        echo json_encode(['message' => "Error: El campo '$field' es requerido"]);
        http_response_code(400); // 400 Bad Request
        exit();
    }
}

// Saneamiento de entrada
$name_qr = filter_var($input['name_qr'], FILTER_SANITIZE_STRING);
$color_qr = filter_var($input['color_qr'], FILTER_SANITIZE_STRING);
$description = filter_var($input['description'], FILTER_SANITIZE_STRING);
$created_by = filter_var($input['created_by'], FILTER_SANITIZE_NUMBER_INT);

// Comprobar duplicados
$sql_check = "SELECT COUNT(*) FROM qrs WHERE description = ? OR name_qr = ?";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->execute([$description, $name_qr]);
$existing_count = $stmt_check->fetchColumn();

if ($existing_count > 0) {
    echo json_encode(['message' => 'Error: Ya existe un registro con la misma información o nombre']);
    http_response_code(409); // 409 Conflict
    exit();
}

// Insertar datos
$sql = "INSERT INTO qrs (description, name_qr, color_qr, created_by) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$description, $name_qr, $color_qr, $created_by])) {
    echo json_encode(['message' => 'Código QR guardado exitosamente']);
    http_response_code(201); // 201 Created
} else {
    echo json_encode(['message' => 'Error al guardar código QR']);
    http_response_code(500); // 500 Internal Server Error
}