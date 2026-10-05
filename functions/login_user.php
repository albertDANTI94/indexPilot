<?php
/*session_start();
require_once __DIR__ . '/../includes/db.php'; // ⚠️ adapte le chemin si nécessaire

// 1. Vérification du token CSRF
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['flash_error'] = "Erreur de sécurité : token CSRF invalide.";
    header('Location: ../login.php');
    exit();
}

// 2. Récupération et nettoyage des données
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash_error'] = "Adresse email invalide.";
    header('Location: ../login.php');
    exit();
}

if (empty($password)) {
    $_SESSION['flash_error'] = "Veuillez entrer votre mot de passe.";
    header('Location: ../login.php');
    exit();
}

try {
    // 3. Récupération de l'utilisateur
    $stmt = $pdo->prepare("SELECT id, prenom, nom, email, password FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 4. Vérification du mot de passe
    if ($user && password_verify($password, $user['password'])) {
        // Authentification réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['prenom']  = $user['prenom'];
        $_SESSION['nom']     = $user['nom'];
        $_SESSION['email']   = $user['email'];

        $_SESSION['flash_success'] = "Connexion réussie. Bienvenue, " . htmlspecialchars($user['prenom']) . "!";

        // Regénération du token CSRF pour sécurité
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        header('Location: ../dashboard.php');
        exit();
    } else {
        // Identifiants incorrects
        $_SESSION['flash_error'] = "Email ou mot de passe incorrect.";
        header('Location: ../login.php');
        exit();
    }
} catch (PDOException $e) {
    // Gestion d'erreurs BDD
    error_log('Erreur BDD (login_user.php) : ' . $e->getMessage());
    $_SESSION['flash_error'] = "Une erreur est survenue. Veuillez réessayer.";
    header('Location: ../login.php');
    exit();
}*/


session_start();
require_once __DIR__ . '/../includes/db.php';

try {
    // ✅ Vérification des champs
    if (
        !isset($_POST['email'], $_POST['password']) ||
        empty($_POST['email']) || empty($_POST['password'])
    ) {
        $_SESSION['flash_error'] = 'Email et mot de passe sont requis.';
        header('Location: ../login.php');
        exit;
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // ✅ Recherche de l'utilisateur
    $stmt = $pdo->prepare('SELECT id, nom, prenom, email, password FROM utilisateurs WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ✅ Vérification du mot de passe
    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['flash_error'] = 'Identifiants incorrects.';
        header('Location: ../login.php');
        exit;
    }

    // ✅ Authentification réussie → on stocke les infos en session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['nom'] = $user['nom'];
    $_SESSION['prenom'] = $user['prenom'];

    $_SESSION['flash_success'] = 'Connexion réussie. Bienvenue ' . htmlspecialchars($user['prenom']) . ' !';

    // ✅ Redirection vers le tableau de bord
    header('Location: ../dashboard.php');
    exit;
} catch (Exception $e) {
    $_SESSION['flash_error'] = 'Erreur serveur : ' . $e->getMessage();
    header('Location: ../login.php');
    exit;
}
