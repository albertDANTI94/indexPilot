<?php

require_once __DIR__ . "/includes/db.php";

$stmt = $pdo->query("SELECT * FROM valeurs_mensuelles");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {

    $libelle = $row['libelle'] ?? null;
    $idbank  = $row['idBank'] ?? null;

    if (!$libelle || !$idbank) {
        continue;
    }

    // 🔹 1. mapping_indices
    $stmtMap = $pdo->prepare("
        INSERT INTO mapping_indices (libelle, idbank)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE idbank = VALUES(idbank)
    ");

    $stmtMap->execute([$libelle, $idbank]);

    // 🔹 2. migration vers indices_cache
    foreach ($row as $column => $value) {

        if (!preg_match('/^\d{4}-\d{2}$/', $column)) {
            continue;
        }

        if ($value === null || $value === '') {
            continue;
        }

        $stmtInsert = $pdo->prepare("
            INSERT INTO indices_cache (idbank, date, valeur)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE valeur = VALUES(valeur)
        ");

        $stmtInsert->execute([
            $idbank,
            $column,
            floatval($value)
        ]);
    }
}

echo "Migration terminée ✅";