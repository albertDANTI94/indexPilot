<?php
// 1. Vérification de l'authentification
require_once __DIR__ . '/auth_check.php';
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/assets/css/styles.css">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const nav = document.querySelector("nav");
            const icons = document.getElementById("icons");
            const links = document.querySelectorAll("nav li");
        
            icons.addEventListener("click", () => {
                nav.classList.toggle("active");
            });
        
            links.forEach((link) => {
                link.addEventListener("click", () => {
                    nav.classList.remove("active");
                });
            });
        });
</script>


</head>

<body>
    
        <nav>
            <figure class="logoContainer">
                <img class="logo" src="./public/assets/images/logo.png" alt="Illustration inscription">
            </figure>
        
            <ul>
                <li><a class="navLink" href="dashboard.php">Tableau de bord</a></li>
                <li><a class="navLink" href="calculator.php">Calculateur</a></li>
                <li><a class="navLink" href="profile.php">Profil</a></li>
                <li><a class="navLink" href="contact.php">Contact</a></li>
                <li><a class="navLink" href="functions/logout.php" class="logout-btn">Se déconnecter</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php

                    $stmt = $pdo->prepare("SELECT role FROM utilisateurs WHERE id = :id");
                    $stmt->execute(['id' => $_SESSION['user_id']]);
                    $user = $stmt->fetch();
                    ?>

                    <?php if ($user && $user['role'] === 'admin'): ?>
                        <li>
                            <a class="navLink" href="admin.php">Espace Admin</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

            </ul>
            <div id="icons"></div>
        </nav>
    