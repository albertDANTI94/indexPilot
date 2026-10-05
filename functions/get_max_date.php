<?php
require_once __DIR__ . '/../includes/db.php';

$stmt = $pdo->query("SELECT MAX(date) as max_date FROM indices");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'max_date' => $row['max_date']
]);