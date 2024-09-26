<?php
require '../../config/cors.php';
require '../../config/database.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    try {
        $sql = "SELECT * FROM qrs WHERE name_qr LIKE :query";
        $stmt = $pdo->prepare($sql);

        // Ejecuta la consulta con el valor de 'query'
        $stmt->execute(['query' => "%$query%"]);

        // Fetch de los resultados
        $qrs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Enviar los resultados como JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['qrs' => $qrs]);
    } catch (PDOException $e) {
        // En caso de error, devolver un mensaje JSON con el error
        http_response_code(500);
        echo json_encode(['error' => 'Error en la consulta a la base de datos: ' . $e->getMessage()]);
    }
} else {
    // Si no se pasa el parámetro 'query', devolver un error
    http_response_code(400);
    echo json_encode(['error' => 'No se proporcionó el parámetro query']);
}
