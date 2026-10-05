<?php
/*header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../includes/db.php';
try {
    $sql = "SELECT c.id AS id, c.nom AS nom, l.id AS lot_id, l.nom AS lot_nom, l.tarif_origine, l.coefficient, l.nouveau_tarif
            FROM chantiers c
            LEFT JOIN lots l ON c.id = l.chantier_id
            ORDER BY c.id DESC, l.id DESC";
    $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    $chantiers = [];
    foreach ($rows as $r) {
        $id = (int)$r['id'];
        if (!isset($chantiers[$id])) {
            $chantiers[$id] = ['id' => $id, 'nom' => $r['nom'], 'lots' => []];
        }
        if (!empty($r['lot_id'])) {
            $chantiers[$id]['lots'][] = [
                'id' => (int)$r['lot_id'],
                'nom' => $r['lot_nom'],
                'tarif_origine' => (float)$r['tarif_origine'],
                'coefficient' => (float)$r['coefficient'],
                'nouveau_tarif' => (float)$r['nouveau_tarif']
            ];
        }
    }
    echo json_encode(array_values($chantiers));
} catch (Exception $e) {
    echo json_encode([]);
}*/



session_start();
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../includes/db.php';

// ✅ Vérification que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // ✅ 1. Récupération des chantiers de l'utilisateur
    $stmt = $pdo->prepare("
        SELECT id, nom, created_at
        FROM chantiers
        WHERE user_id = :user_id
        ORDER BY created_at DESC
    ");
    $stmt->execute(['user_id' => $user_id]);
    $chantiers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($chantiers)) {
        echo json_encode([]);
        exit;
    }

    // ✅ 2. Récupération de tous les lots liés aux chantiers récupérés
    $chantierIds = array_column($chantiers, 'id');
    $placeholders = implode(',', array_fill(0, count($chantierIds), '?'));

    $stmtLots = $pdo->prepare("
        SELECT id, chantier_id, nom, tarif_origine, indice0, indiceN, coefficient, nouveau_tarif
        FROM lots
        WHERE chantier_id IN ($placeholders)
        ORDER BY id ASC
    ");
    $stmtLots->execute($chantierIds);
    $lots = $stmtLots->fetchAll(PDO::FETCH_ASSOC);

    // ✅ 3. Regrouper les lots par chantier_id
    $lotsParChantier = [];
    foreach ($lots as $lot) {
        $lotsParChantier[$lot['chantier_id']][] = $lot;
    }

    // ✅ 4. Ajouter les lots dans chaque chantier
    foreach ($chantiers as &$chantier) {
        $chantier_id = $chantier['id'];
        $chantier['lots'] = $lotsParChantier[$chantier_id] ?? [];
    }

    // ✅ 5. Retour JSON propre
    echo json_encode($chantiers);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur lors de la récupération des chantiers et lots',
        'details' => $e->getMessage()
    ]);
}
