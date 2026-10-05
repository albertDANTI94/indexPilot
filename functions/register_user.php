<?php
/*session_start();
require_once __DIR__ . '/../includes/db.php';

// Vérification CSRF
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['flash_error'] = "Erreur de sécurité. Veuillez réessayer.";
    header('Location: ../register.php');
    exit();
}

// Récupération et nettoyage des données
$prenom = trim($_POST['prenom'] ?? '');
$nom = trim($_POST['nom'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm'] ?? '';

// Validation
if (empty($prenom) || empty($nom)) {
    $_SESSION['flash_error'] = "Tous les champs sont obligatoires.";
    header('Location: ../register.php');
    exit();
}

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash_error'] = "Adresse email invalide.";
    header('Location: ../register.php');
    exit();
}

if (strlen($password) < 8) {
    $_SESSION['flash_error'] = "Le mot de passe doit contenir au moins 8 caractères.";
    header('Location: ../register.php');
    exit();
}

if ($password !== $confirm) {
    $_SESSION['flash_error'] = "Les mots de passe ne correspondent pas.";
    header('Location: ../register.php');
    exit();
}

// Vérification si l'email existe déjà
try {
    $check = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $check->execute(['email' => $email]);
    if ($check->fetch()) {
        $_SESSION['flash_error'] = "Cet email est déjà enregistré.";
        header('Location: ../register.php');
        exit();
    }

    // Hash du mot de passe
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insertion en BDD
    $stmt = $pdo->prepare("
        INSERT INTO users (prenom, nom, email, password, created_at)
        VALUES (:prenom, :nom, :email, :password, NOW())
    ");

    $stmt->execute([
        'prenom'   => $prenom,
        'nom'      => $nom,
        'email'    => $email,
        'password' => $passwordHash,
    ]);

    $_SESSION['flash_success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    header('Location: ../login.php');
    exit();
} catch (PDOException $e) {
    error_log('Erreur BDD register_user.php : ' . $e->getMessage());
    $_SESSION['flash_error'] = "Une erreur est survenue. Veuillez réessayer.";
    header('Location: ../register.php');
    exit();
}*/


session_start();
require_once __DIR__ . '/../includes/db.php';

try {
    // ✅ Vérification des champs obligatoires
    if (
        !isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['password'], $_POST['confirm']) ||
        empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) ||
        empty($_POST['password']) || empty($_POST['confirm'])
    ) {
        $_SESSION['flash_error'] = 'Tous les champs sont requis.';
        header('Location: ../register.php');
        exit;
    }

    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // ✅ Vérification mot de passe
    if ($password !== $confirm) {
        $_SESSION['flash_error'] = 'Les mots de passe ne correspondent pas.';
        header('Location: ../register.php');
        exit;
    }

    // ✅ Vérification si l'email est déjà utilisé
    $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        $_SESSION['flash_error'] = 'Cet email est déjà utilisé.';
        header('Location: ../register.php');
        exit;
    }

    // ✅ Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // ✅ Insertion dans la base de données
    $stmt = $pdo->prepare('
        INSERT INTO utilisateurs (nom, prenom, email, password, created_at)
        VALUES (:nom, :prenom, :email, :password, NOW())
    ');
    $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'password' => $hashedPassword
    ]);

    $user_id = $pdo->lastInsertId();

    // ✅ Connexion automatique après inscription
    $_SESSION['user_id'] = $user_id;
    $_SESSION['email'] = $email;
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;

    $_SESSION['flash_success'] = 'Inscription réussie. Bienvenue ' . htmlspecialchars($prenom) . ' !';

    // ✅ Redirection vers le dashboard
    header('Location: ../dashboard.php');
    exit;
} catch (Exception $e) {
    $_SESSION['flash_error'] = 'Erreur serveur : ' . $e->getMessage();
    header('Location: ../register.php');
    exit;
}
