<?php

function upsertIndice($pdo, $idbank, $date, $valeur) {

    $stmt = $pdo->prepare("
        INSERT INTO indices_cache (idbank, date, valeur)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE valeur = VALUES(valeur)
    ");

    $stmt->execute([$idbank, $date, $valeur]);
}