<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../services/mail_service.php';

// 🔥 récupérer derniers indices ajoutés (ex: aujourd’hui)
$stmt = $pdo->query("
    SELECT idbank, date, valeur 
    FROM indices_cache 
    WHERE date = DATE_FORMAT(NOW(), '%Y-%m')
");

$indices = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($indices as $indice) {

    // 🔥 récupérer abonnés
    $stmtUsers = $pdo->prepare("
        SELECT u.email 
        FROM subscriptions s
        JOIN users u ON u.id = s.user_id
        WHERE s.idbank = ?
    ");

    $stmtUsers->execute([$indice['idbank']]);
    $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {

        $message = "
Nouvel indice disponible :

IDBANK: {$indice['idbank']}
Date: {$indice['date']}
Valeur: {$indice['valeur']}
        ";

        sendEmail(
            $user['email'],
            "Nouvel indice disponible",
            $message
        );
    }
}

echo "📧 Alertes envoyées\n";