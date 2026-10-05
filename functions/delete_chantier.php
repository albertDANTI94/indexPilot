<?php
// functions/delete_chantier.php
/*header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../includes/db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $id = isset($input['id']) ? (int)$input['id'] : 0;

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID invalide']);
        exit;
    }

    // Si ta table lots a FOREIGN KEY avec ON DELETE CASCADE, la suppression suivante
    // supprimera automatiquement les lots. Sinon, tu peux d'abord supprimer les lots explicitement.
    $stmt = $pdo->prepare('DELETE FROM chantiers WHERE id = ?');
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
    exit;
} catch (Exception $e) {
    // En dev tu peux renvoyer $e->getMessage()
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}*/


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

// Récupération de l'ID du chantier depuis POST
$input = json_decode(file_get_contents('php://input'), true);
$chantier_id = $input['id'] ?? null;

if (!$chantier_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'L\'ID du chantier est requis']);
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

    // Supprime les lots associés
    $stmt = $pdo->prepare("DELETE FROM lots WHERE chantier_id = :chantier_id");
    $stmt->execute(['chantier_id' => $chantier_id]);

    // Supprime le chantier
    $stmt = $pdo->prepare("DELETE FROM chantiers WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $chantier_id, 'user_id' => $user_id]);

    echo json_encode(['success' => true, 'message' => 'Chantier supprimé avec succès']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
