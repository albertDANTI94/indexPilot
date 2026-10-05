<?php
/*require_once __DIR__ . '/../includes/db.php';
header('Content-Type: application/json; charset=UTF-8');

try {
    $stmt = $pdo->query("SELECT DISTINCT libelle FROM valeurs_mensuelles ORDER BY libelle ASC");
    $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($rows);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur', 'details' => $e->getMessage()]);
}*/


require_once __DIR__ . '/../includes/db.php';
header('Content-Type: application/json');

$stmt = $pdo->query("
    SELECT DISTINCT idbank, libelle 
    FROM mapping_indices
    ORDER BY libelle ASC
");

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);

