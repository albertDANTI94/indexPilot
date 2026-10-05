<?php
/*header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../includes/db.php';
try {
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    if ($nom === '') {
        echo json_encode(['success' => false, 'message' => 'Nom requis']);
        exit;
    }
    $stmt = $pdo->prepare("INSERT INTO chantiers (nom) VALUES (:nom)");
    $stmt->execute(['nom' => $nom]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}*/




session_start();
require_once '../includes/db.php'; // ton PDO

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];
$nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';

if ($nom === '') {
    echo json_encode(['success' => false, 'message' => 'Nom du chantier vide']);
    http_response_code(400); // <-- c'est OK de renvoyer 400 ici
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO chantiers (user_id, nom) VALUES (:user_id, :nom)");
    $stmt->execute([':user_id' => $user_id, ':nom' => $nom]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
    http_response_code(500);
}
