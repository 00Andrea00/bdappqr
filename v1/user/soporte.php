<?php
require '../../config/cors.php';
$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'];
$mensaje = $input['mensaje'];
$to = "prueba.appqr@gmail.com";
$subject="El correo $email ha mandando un ticket de soporte";
$body= $mensaje;
$headers = "From: $email";
if (mail($to, $subject, $body, $headers)) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'El mensaje se ha enviado correctamente']);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Error al enviar el correo electrónico']);
}
?>