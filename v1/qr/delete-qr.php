<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); // Habilitar la visualización de errores

require '../../config/cors.php';
require '../../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

// Validar la entrada
$required_fields = ['name_qr'];

foreach ($required_fields as $field) {
	if (!isset($input[$field]) || empty($input[$field])) {
		http_response_code(400);
		echo json_encode(['message' => "Error: El campo '$field' es requerido"]);
		exit;
	}
}

// Sanitizar la entrada
$name_qr = htmlspecialchars($input['name_qr'], ENT_QUOTES, 'UTF-8');

$sql = "DELETE FROM qrs WHERE name_qr = ?";
$stmt = $pdo->prepare($sql);

if (!$stmt->execute([$name_qr])) {
	// Si la ejecución falla, captura el error
	$errorInfo = $stmt->errorInfo();
	http_response_code(500); // Error del servidor
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode(['message' => 'Error al eliminar código QR: ' . $errorInfo[2]]);
	exit;
}

// Respuesta exitosa
http_response_code(200);
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['message' => 'Código QR eliminado exitosamente']);
