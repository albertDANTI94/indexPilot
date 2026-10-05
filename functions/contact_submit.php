<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

// ✅ Vérification CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['flash_error'] = 'Token CSRF invalide.';
    header('Location: ../contact.php');
    exit;
}

// ✅ Vérification des champs
$required = ['nom', 'email', 'message'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['flash_error'] = 'Tous les champs sont requis.';
        header('Location: ../contact.php');
        exit;
    }
}

$nom = trim($_POST['nom']);
$email = trim($_POST['email']);
$message = trim($_POST['message']);

// ✅ Validation email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash_error'] = 'Adresse email invalide.';
    header('Location: ../contact.php');
    exit;
}

// ✅ Échapper les caractères HTML
$nom = htmlspecialchars($nom, ENT_QUOTES);
$email = htmlspecialchars($email, ENT_QUOTES);
$message = htmlspecialchars($message, ENT_QUOTES);

// --- Option 1 : Envoi par email ---
$to = "tonemail@example.com"; // ← remplace par ton adresse
$subject = "Message de contact de $nom";
$body = "Nom: $nom\nEmail: $email\n\nMessage:\n$message";
$headers = "From: $email\r\nReply-To: $email\r\n";

if (mail($to, $subject, $body, $headers)) {
    $_SESSION['flash_success'] = 'Votre message a été envoyé avec succès !';
} else {
    $_SESSION['flash_error'] = 'Erreur lors de l\'envoi du message.';
}

header('Location: ../contact.php');
exit;
