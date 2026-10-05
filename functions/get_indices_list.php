<?php

require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

$stmt = $pdo->query("
    SELECT DISTINCT idbank, libelle 
    FROM mapping_indices 
    ORDER BY libelle ASC
");

echo json_encode([
    'success' => true,
    'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);