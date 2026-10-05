<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../includes/db.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Vérifie que chantier_id est passé en GET
$chantier_id = $_GET['chantier_id'] ?? null;
if (!$chantier_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Le champ chantier_id est requis']);
    exit;
}

try {
    // Vérifie que le chantier appartient à l'utilisateur
    $stmt = $pdo->prepare("SELECT id FROM chantiers WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $chantier_id, 'user_id' => $user_id]);
    if (!$stmt->fetch()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Chantier introuvable ou non autorisé']);
        exit;
    }

    // Récupère les lots
    $stmt = $pdo->prepare("SELECT id, nom, tarif_origine, indice0, indiceN, coefficient, nouveau_tarif 
                           FROM lots 
                           WHERE chantier_id = :chantier_id
                           ORDER BY id ASC");
    $stmt->execute(['chantier_id' => $chantier_id]);
    $lots = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($lots);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
