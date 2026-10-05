<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (empty($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID du lot manquant']);
    exit;
}

$lot_id = (int)$input['id'];
$user_id = $_SESSION['user_id'];

// Vérifier que le lot appartient à un chantier de l'utilisateur
$stmt = $pdo->prepare("SELECT l.id FROM lots l JOIN chantiers c ON l.chantier_id = c.id WHERE l.id = :lot_id AND c.user_id = :user_id");
$stmt->execute(['lot_id' => $lot_id, 'user_id' => $user_id]);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Lot introuvable ou non autorisé']);
    exit;
}

// Supprimer le lot
$stmt = $pdo->prepare("DELETE FROM lots WHERE id = :id");
$result = $stmt->execute(['id' => $lot_id]);

echo json_encode(['success' => $result]);
