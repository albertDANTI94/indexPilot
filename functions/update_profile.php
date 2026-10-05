<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Vérification CSRF
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['flash_error'] = "Erreur de sécurité : token CSRF invalide.";
    header('Location: ../profile.php');
    exit();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_error'] = "Utilisateur non connecté.";
    header('Location: ../login.php');
    exit();
}

// Récupération des données
$prenom = trim($_POST['prenom'] ?? '');
$nom = trim($_POST['nom'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm'] ?? '';

// Validation
if (empty($prenom) || empty($nom)) {
    $_SESSION['flash_error'] = "Prénom et nom obligatoires.";
    header('Location: ../profile.php');
    exit();
}

if (!empty($password) && $password !== $confirm) {
    $_SESSION['flash_error'] = "Les mots de passe ne correspondent pas.";
    header('Location: ../profile.php');
    exit();
}

try {
    if (!empty($password)) {
        // Mettre à jour prénom, nom et mot de passe
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET prenom = :prenom, nom = :nom, password = :password WHERE id = :id");
        $stmt->execute([
            'prenom'   => $prenom,
            'nom'      => $nom,
            'password' => $passwordHash,
            'id'       => $_SESSION['user_id']
        ]);
    } else {
        // Mettre à jour prénom et nom uniquement
        $stmt = $pdo->prepare("UPDATE users SET prenom = :prenom, nom = :nom WHERE id = :id");
        $stmt->execute([
            'prenom' => $prenom,
            'nom'    => $nom,
            'id'     => $_SESSION['user_id']
        ]);
    }

    // Mise à jour des sessions
    $_SESSION['prenom'] = $prenom;
    $_SESSION['nom'] = $nom;

    $_SESSION['flash_success'] = "Profil mis à jour avec succès.";
    header('Location: ../profile.php');
    exit();
} catch (PDOException $e) {
    error_log('Erreur BDD update_profile.php : ' . $e->getMessage());
    $_SESSION['flash_error'] = "Une erreur est survenue. Veuillez réessayer.";
    header('Location: ../profile.php');
    exit();
}
