<?php
require '../../config/cors.php';
require '../../config/database.php';


$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'];
$admin_email ='prueba.appqr@gmail.com';
$subject="Solicitud de cambio de contraseña del correo $email";
$message = "El usuario con email $email solicita permisos para cambiar la contraseña" ;
$headers = "From: $email";


if (mail($admin_email, $subject, $message, $headers)) {
    header('Content-Type: application/json; charset=utf-8'); 
    echo json_encode([
        'message' => 'El mensaje se ha enviado correctamente',
    ]);
} else {
    header('Content-Type: application/json; charset=utf-8'); 
    echo json_encode(['message' => 'Error al enviar el correo electrónico']);
}
