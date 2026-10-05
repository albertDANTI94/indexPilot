<?php

/*require_once "../includes/db.php";
require_once "../services/insee_api.php";
require_once "../services/insee_parser.php";

// récupérer tous les idBank
$stmt = $pdo->query("SELECT idbank FROM indices");
$idbanks = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($idbanks as $idbank) {

    try {

        $xml = callINSEE($idbank);
        $data = parseINSEE($xml);

        if (empty($data)) {
            continue;
        }

        // 🔁 INSERT / UPDATE des valeurs
        foreach ($data as $row) {

            $stmt = $pdo->prepare("
                INSERT INTO valeurs_mensuelles (idbank, date, valeur)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE valeur = VALUES(valeur)
            ");

            $stmt->execute([
                $idbank,
                $row['date'],
                $row['value']
            ]);
        }

        // 🔔 vérifier si nouvel indice
        $latest = getLastValue($data);

        $stmt = $pdo->prepare("SELECT last_update FROM indices WHERE idbank = ?");
        $stmt->execute([$idbank]);
        $oldDate = $stmt->fetchColumn();

        if (!$oldDate || $latest['date'] > $oldDate) {

            // mise à jour date
            $stmt = $pdo->prepare("
                UPDATE indices SET last_update = ? WHERE idbank = ?
            ");
            $stmt->execute([$latest['date'], $idbank]);

            // envoi alertes
            sendAlerts($pdo, $idbank, $latest);
        }

        echo "OK $idbank\n";

    } catch (Exception $e) {
        echo "Erreur $idbank : " . $e->getMessage() . "\n";
    }
}*/


require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../services/insee_api.php';
require_once __DIR__ . '/../services/insee_parser.php';
require_once __DIR__ . '/../services/indice_service.php';

// 🔥 récupérer mapping
$stmt = $pdo->query("
    SELECT idbank, code_insee 
    FROM mapping_indices
    WHERE code_insee IS NOT NULL
");

$mappings = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($mappings as $map) {

    try {

        $xml = fetchSerieINSEE($map['code_insee']);

        $data = parseINSEE($xml);

        foreach ($data as $row) {

            upsertIndice(
                $pdo,
                $map['idbank'],
                substr($row['date'], 0, 7),
                $row['value']
            );
        }

    } catch (Exception $e) {
        error_log("Erreur INSEE: " . $e->getMessage());
    }
}

echo "✅ Sync terminé\n";