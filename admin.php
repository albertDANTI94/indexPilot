<?php
session_start();
require_once __DIR__ . '/includes/db.php';

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Vérification du rôle
$stmt = $pdo->prepare("SELECT role FROM utilisateurs WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    http_response_code(403);
    echo "<h1>Accès interdit</h1><p>Vous n'avez pas les droits pour accéder à cette page.</p>";
    exit;
}

// Récupération de tous les utilisateurs
$stmt = $pdo->query("SELECT id, nom, prenom, email, created_at FROM utilisateurs ORDER BY created_at DESC");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Administration</title>
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>

<body>
    <header>

        <nav>
            <ul>
                <li><a class="navLink" href="dashboard.php">Retour au tableau de bord</a></li>
                <li><a class="navLink" href="functions/logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h3>Tableau de bord administrateur</h3>
        <h3>Liste des utilisateurs</h3>
        <div class="tableau-wrapper-1">

        <!--<table border="1" cellpadding="8" cellspacing="0">-->
        <table class="tableau-responsive-1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Date d'inscription</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $c): ?>
                    <tr>
                        <td data-label="ID"><?= htmlspecialchars($c['id']) ?></td>
                        <td data-label="Nom"><?= htmlspecialchars($c['nom']) ?></td>
                        <td data-label="Prénom"><?= htmlspecialchars($c['prenom']) ?></td>
                        <td data-label="Email"><?= htmlspecialchars($c['email']) ?></td>
                        <td data-label="Date d'inscription"><?= htmlspecialchars($c['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </main>
</body>

</html>