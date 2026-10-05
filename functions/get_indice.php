<?php
/*require_once __DIR__ . '/../includes/db.php';
header('Content-Type: application/json; charset=UTF-8');

$libelle = $_GET['libelle'] ?? null;
$date = $_GET['date'] ?? null;

if (!$libelle || !$date) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit;
}

// sanitize column name: must match YYYY-MM pattern
if (!preg_match('/^\d{4}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Format de date invalide']);
    exit;
}

try {
    // verify that column exists in the table
    $table = 'valeurs_mensuelles'; // ajuste si nécessaire
    $colCheck = $pdo->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table AND COLUMN_NAME = :col");
    $colCheck->execute(['table' => $table, 'col' => $date]);
    if ($colCheck->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Colonne introuvable dans la table: ' . $date]);
        exit;
    }

    $sql = "SELECT `$date` AS indice FROM `$table` WHERE libelle = :libelle LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['libelle' => $libelle]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['indice'] !== null && $row['indice'] !== '') {
        echo json_encode(['success' => true, 'indice' => $row['indice']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Indice non trouvé pour ce libellé/date']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur', 'details' => $e->getMessage()]);
}*/


require_once __DIR__ . '/../includes/db.php';
header('Content-Type: application/json; charset=UTF-8');

$idbank = $_GET['idbank'] ?? null;
$date = $_GET['date'] ?? null;

if (!$idbank || !$date) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit;
}

try {

    $stmt = $pdo->prepare("
        SELECT valeur 
        FROM indices 
        WHERE idbank = :idbank AND date = :date
        LIMIT 1
    ");

    $stmt->execute([
        'idbank' => $idbank,
        'date' => $date
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['valeur'] !== null) {
        echo json_encode([
            'success' => true,
            'valeur' => $row['valeur']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Indice non trouvé'
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur',
        'details' => $e->getMessage()
    ]);
}