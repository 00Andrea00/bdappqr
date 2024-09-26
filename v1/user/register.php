<?php
require '../../config/cors.php';
require '../../config/database.php';

// Establecer la cabecera JSON una sola vez
header('Content-Type: application/json; charset=utf-8');

// Leer el cuerpo de la solicitud
$input = json_decode(file_get_contents('php://input'), true);
$required_fields = ['name', 'email', 'password'];

// Verificar los campos requeridos
foreach ($required_fields as $field) {
	if (empty($input[$field])) {
		echo json_encode(['message' => "Error: El campo '$field' es requerido"]);
		http_response_code(400);
		exit;
	}
}

// Sanitizar y validar los datos de entrada
$name = filter_var($input['name'], FILTER_SANITIZE_STRING);
$email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
$password = $input['password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	echo json_encode(['message' => "Error: El email '$email' no es válido"]);
	http_response_code(400);
	exit;
}

// Verificar si el email ya está registrado
$sql = "SELECT COUNT(*) FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$emailCount = $stmt->fetchColumn();

if ($emailCount > 0) {
	echo json_encode(['message' => "Error: El email '$email' ya está registrado"]);
	http_response_code(409);
	exit;
}

// Hash de la contraseña
$passwordHashed = password_hash($password, PASSWORD_DEFAULT);

// Enviar notificación al administrador
$admin_email = 'prueba.appqr@gmail.com';
$subject = "Se ha registrado $name en la app qrcode";
$message = "El usuario $name con email $email solicita permisos para utilizar la aplicación";
$headers = "From: $email";

// Insertar usuario en la base de datos
$sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$name, $email, $passwordHashed])) {
	// Enviar email de notificación al administrador
	mail($admin_email, $subject, $message, $headers);

	// Responder con éxito
	echo json_encode([
		'message' => "$name registrado exitosamente",
		'email' => $email,
	]);
	http_response_code(201);
} else {
	echo json_encode(['message' => "Error al registrar $name"]);
	http_response_code(500);
}
