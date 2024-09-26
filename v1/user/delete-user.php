<?php
// Manejar la solicitud preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("HTTP/1.1 200 OK");
    exit();
}

require '../../config/cors.php';
require '../../config/database.php';

try {
    // Leer la entrada JSON
    $input = json_decode(file_get_contents('php://input'), true);
    // Validar entrada
    if (isset($input['email']) && !empty($input['email'])) {
        $email = $input['email'];
        // Comprobar si el correo electrónico existe
        $checkEmailSql = "SELECT id FROM users WHERE email = ?";
        $checkStmt = $pdo->prepare($checkEmailSql);
        $checkStmt->execute([$email]);
        $userId = $checkStmt->fetchColumn();
        if ($userId) {
            // Iniciar una transacción
            $pdo->beginTransaction();
            try {
                // Eliminar los QR relacionados
                $deleteQrSql = "DELETE FROM qrs WHERE created_by = ?";
                $deleteQrStmt = $pdo->prepare($deleteQrSql);
                $deleteQrStmt->execute([$userId]);
                // Eliminar el usuario
                $deleteUserSql = "DELETE FROM users WHERE id = ?";
                $deleteUserStmt = $pdo->prepare($deleteUserSql);
                $deleteUserStmt->execute([$userId]);
                // Confirmar la transacción
                $pdo->commit();
                echo json_encode([
                    'message' => "El usuario y sus QR relacionados han sido eliminados exitosamente",
                    'email' => $email
                ]);
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                $pdo->rollBack();
                echo json_encode(['message' => 'Error al eliminar el usuario: ' . $e->getMessage()]);
            }
        } else {
            // El correo electrónico no existe en la base de datos
            http_response_code(404); // Establecer código de respuesta 404
            echo json_encode(['message' => 'El correo electrónico no existe en la base de datos']);
        }
    } else {
        http_response_code(400); // Establecer código de respuesta 400
        echo json_encode(['message' => 'Datos incompletos']);
    }
} catch (Exception $e) {
    http_response_code(500); // Establecer código de respuesta 500
    echo json_encode(['message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
