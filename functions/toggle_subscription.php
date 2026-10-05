<?php
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json; charset=UTF-8');

try {

    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['idbank'])) {
        throw new Exception("idbank manquant");
    }

    $idbank = trim($input['idbank']);

    // ✅ validation stricte
    if (!preg_match('/^[0-9]+$/', $idbank)) {
        throw new Exception("idbank invalide");
    }

    // (optionnel) user_id si système auth
    $user_id = 1;

    // vérifier si déjà abonné
    $stmt = $pdo->prepare("
        SELECT id FROM subscriptions 
        WHERE user_id = ? AND idbank = ?
    ");
    $stmt->execute([$user_id, $idbank]);

    $existing = $stmt->fetch();

    if ($existing) {
        // 🔹 désabonnement
        $del = $pdo->prepare("
            DELETE FROM subscriptions 
            WHERE user_id = ? AND idbank = ?
        ");
        $del->execute([$user_id, $idbank]);

        echo json_encode([
            'success' => true,
            'subscribed' => false
        ]);
        exit;
    }

    // 🔹 abonnement (sans crash duplicate)
    $insert = $pdo->prepare("
        INSERT INTO subscriptions (user_id, idbank)
        VALUES (?, ?)
    ");

    $insert->execute([$user_id, $idbank]);

    echo json_encode([
        'success' => true,
        'subscribed' => true
    ]);

} catch (Exception $e) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}