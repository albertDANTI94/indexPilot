<?php
session_start();

// Si l'utilisateur n'est pas connecté, on redirige vers login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_error'] = "Veuillez vous connecter pour accéder à cette page.";
    header('Location: ./login.php');
    exit();
}

// (Optionnel) Protection CSRF ici si tu as des formulaires sur toutes les pages
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
