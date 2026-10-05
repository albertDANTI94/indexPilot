<?php

function sendAlerts($pdo, $idbank, $latest) {

    $stmt = $pdo->prepare("
        SELECT u.email
        FROM abonnements_indices a
        JOIN users u ON u.id = a.user_id
        WHERE a.idbank = ?
    ");

    $stmt->execute([$idbank]);
    $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($emails as $email) {

        mail(
            $email,
            "Nouvel indice publié",
            "Nouvelle valeur disponible pour $idbank : " . $latest['value'] . " (" . $latest['date'] . ")"
        );
    }
}