<?php
session_start();

// ✅ Génération d'un token CSRF unique si non existant
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ✅ Gestion des messages flash (facultatif si tu souhaites en afficher sur la page inscription aussi)
$flash_message = '';
$flash_type = '';
if (isset($_SESSION['flash_error'])) {
    $flash_message = $_SESSION['flash_error'];
    $flash_type = 'error';
    unset($_SESSION['flash_error']);
} elseif (isset($_SESSION['flash_success'])) {
    $flash_message = $_SESSION['flash_success'];
    $flash_type = 'success';
    unset($_SESSION['flash_success']);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/assets/css/styles.css">
    <title>Inscription</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
</head>

<body>
    <header class="bigHeader">
        <div class="registerBox">
            <div class="leftSection">
                <figure>
                    <img class="registerPic" src="./public/assets/images/register_pic.png" alt="Illustration inscription">
                </figure>
            </div>
            <div class="rightSection">
                <div class="tittleSection">
                    <h3 class="formTittle">Inscrivez-vous !</h3>
                </div>

                <?php if (!empty($flash_message)): ?>
                    <div class="flash-message <?php echo htmlspecialchars($flash_type); ?>">
                        <?php echo htmlspecialchars($flash_message); ?>
                    </div>
                <?php endif; ?>

                <form action="functions/register_user.php" method="POST" class="register" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <div class="formFlexer">
                        <label>Prénom :</label>
                        <input type="text" name="prenom" required>
                    </div>

                    <div class="formFlexer">
                        <label>Nom :</label>
                        <input type="text" name="nom" required>
                    </div>

                    <div class="formFlexer">
                        <label>Email :</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="formFlexer">
                        <label>Mot de passe :</label>
                        <input type="password" name="password" required minlength="8">
                    </div>

                    <div class="formFlexer">
                        <label>Confirmation :</label>
                        <input type="password" name="confirm" required minlength="8">
                    </div>

                    <button type="submit">S’inscrire</button>
                </form>

                <p>Déjà inscrit ? <a href="./login.php">Connectez-vous ici</a></p>
            </div>
        </div>
    </header>

</body>

</html>