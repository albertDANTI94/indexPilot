<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

ob_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';
require_once __DIR__ . '/../services/insee_api.php';
require_once __DIR__ . '/../services/insee_parser.php';

function getLastDate($pdo, $idbank) {
    $stmt = $pdo->prepare("
        SELECT MAX(date) 
        FROM indices 
        WHERE idbank = ?
    ");
    
    $stmt->execute([$idbank]);

    return $stmt->fetchColumn();
}

try {

    $stmt = $pdo->query("
        SELECT DISTINCT idbank 
        FROM mapping_indices 
        WHERE idbank IS NOT NULL 
        AND idbank != ''
        ORDER BY idbank");
    $idbanks = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($idbanks as $idbank) {

        $idbankFormatted = str_pad($idbank, 9, '0', STR_PAD_LEFT);

        echo "🔄 Sync IDBANK: $idbankFormatted\n";

        $xml = fetchSerieINSEE($idbankFormatted);

        $xml = trim($xml);

        $pos = strpos($xml, '<?xml');
        if ($pos !== false) {
            $xml = substr($xml, $pos);
        } else {
            echo "❌ XML introuvable pour IDBANK $idbankFormatted\n";
            continue;
        }

        if (strpos($xml, 'Aucun résultat') !== false) {
            echo "❌ Série introuvable : $idbankFormatted\n";
            continue;
        }

        $data = parseINSEE($xml);

        $lastDate = getLastDate($pdo, $idbank);

        foreach ($data as $row) {

            $date = $row['date'];
            $value = $row['value'];

            echo "Traitement: $idbank - $date = $value\n";

            // 🔥 Stop dès qu'on atteint une date déjà connue
            if ($lastDate && $date <= $lastDate) {
                break;
            }

            $insert = $pdo->prepare("
                INSERT IGNORE INTO indices (idbank, date, valeur)
                VALUES (?, ?, ?)
            ");

            $insert->execute([$idbank, $date, $value]);

            if ($insert->rowCount() > 0) {

                echo "🆕 Nouveau: $idbank - $date = $value\n";

                sendAlertEmail(
                    "ton@email.com",
                    $idbank,
                    $date,
                    $value
                );
            }
        }
    }

    echo "✅ Sync terminé\n";  
    
    ob_end_clean();
    
    header('Location: https://www.indexpilot.fr/public/indices');
    exit();

} catch (Exception $e) {
    echo "❌ " . $e->getMessage();
}