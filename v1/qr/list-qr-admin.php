<?php
require '../../config/cors.php';
require '../../config/database.php';
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$sql = "SELECT
    qrs.id AS qr_id,
    qrs.name_qr AS qr_name_qr,
    qrs.color_qr AS qr_color_qr,
    qrs.description AS qr_description,
    qrs.created_at AS qr_created_at,
    users.id AS user_id,
    users.name AS user_name,
    users.email AS user_email
FROM qrs
    JOIN users ON qrs.created_by = users.id;";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$qrs = $stmt->fetchAll();

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['qrs' => $qrs]);
?>