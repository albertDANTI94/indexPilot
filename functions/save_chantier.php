<?php
require '../includes/db.php'; // ta connexion PDO

header('Content-Type: application/json');

try {
    $nomChantier = $_POST['nom_chantier'];
    $lotNoms = $_POST['lot_nom'];
    $lotTarifs = $_POST['lot_tarif'];
    $lotIndice0 = $_POST['lot_indice0'];
    $lotIndiceN = $_POST['lot_indiceN'];

    // Enregistrer le chantier
    $stmt = $pdo->prepare("INSERT INTO chantiers (nom) VALUES (:nom)");
    $stmt->execute(['nom' => $nomChantier]);
    $chantierId = $pdo->lastInsertId();

    $lotsData = [];

    // Enregistrer les lots avec calcul
    for ($i = 0; $i < count($lotNoms); $i++) {
        $tarif = (float)$lotTarifs[$i];
        $indice0 = (float)$lotIndice0[$i];
        $indiceN = (float)$lotIndiceN[$i];

        $Cn = 0.15 + 0.85 * ($indiceN / $indice0);
        $newTarif = $tarif * $Cn;

        $stmtLot = $pdo->prepare("
            INSERT INTO lots (chantier_id, nom, tarif_origine, indice0, indiceN, coefficient, nouveau_tarif)
            VALUES (:chantier_id, :nom, :tarif, :indice0, :indiceN, :coefficient, :nouveau_tarif)
        ");
        $stmtLot->execute([
            'chantier_id' => $chantierId,
            'nom' => $lotNoms[$i],
            'tarif' => $tarif,
            'indice0' => $indice0,
            'indiceN' => $indiceN,
            'coefficient' => $Cn,
            'nouveau_tarif' => $newTarif
        ]);

        $lotsData[] = [
            'nom' => $lotNoms[$i],
            'tarif' => $tarif,
            'coefficient' => $Cn,
            'nouveau_tarif' => $newTarif
        ];
    }

    echo json_encode([
        'success' => true,
        'chantier' => [
            'nom' => $nomChantier,
            'lots' => $lotsData
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
