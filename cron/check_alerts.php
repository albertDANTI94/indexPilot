<?php
require_once "../includes/db.php";

$alerts = $pdo->query("
    SELECT a.*, u.email 
    FROM insee_alerts a
    JOIN utilisateurs u ON u.id = a.user_id
")->fetchAll();

foreach ($alerts as $alert) {

    $stmt = $pdo->prepare("
        SELECT MAX(date) as last_date 
        FROM insee_indices 
        WHERE idbank = ?
    ");
    $stmt->execute([$alert['idbank']]);
    $lastDate = $stmt->fetch()['last_date'];

    if ($lastDate && $lastDate !== $alert['last_notified']) {

        mail(
            $alert['email'],
            "Nouvel indice publié",
            "Nouvelle valeur disponible pour votre indice INSEE."
        );

        $pdo->prepare("
            UPDATE insee_alerts 
            SET last_notified = ?
            WHERE id = ?
        ")->execute([$lastDate, $alert['id']]);
    }
}