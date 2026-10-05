<?php
//require __DIR__ . '/includes/auth_check.php';
require __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';

// Gestion des messages flash
$flash_message = '';
$flash_class = '';
if (isset($_SESSION['flash_error'])) {
    $flash_message = $_SESSION['flash_error'];
    $flash_class = 'error';
    unset($_SESSION['flash_error']);
} elseif (isset($_SESSION['flash_success'])) {
    $flash_message = $_SESSION['flash_success'];
    $flash_class = 'success';
    unset($_SESSION['flash_success']);
}

// Récupération des infos utilisateur actuelles
$stmt = $pdo->prepare("SELECT prenom, nom, email FROM utilisateurs WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<main class="profilePage">
    <div class="connect">
        <p>Vous êtez connecté en tant que, <?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?></p>
    </div>
    <div class="profileContainer">
        <h3>Mon profil</h3>

        <?php if (!empty($flash_message)): ?>
            <div class="flash-message <?php echo $flash_class; ?>">
                <?php echo htmlspecialchars($flash_message); ?>
            </div>
        <?php endif; ?>

        <form action="functions/update_profile.php" method="POST" class="profileForm" novalidate>
            <!-- CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="formFlexer">
                <label>Prénom :</label>
                <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required>
            </div>

            <div class="formFlexer">
                <label>Nom :</label>
                <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
            </div>

            <div class="formFlexer">
                <label>Email :</label>
                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
            </div>

            <hr>
            <h3>Modifier le mot de passe</h3>

            <div class="formFlexer">
                <label>Nouveau mot de passe :</label>
                <input type="password" name="password" minlength="8">
            </div>

            <div class="formFlexer">
                <label>Confirmation :</label>
                <input type="password" name="confirm" minlength="8">
            </div>

            <button type="submit">Mettre à jour le profil</button>
        </form>


    </div>
</main>
<script src="./assets/js/validationProfile.js"></script>
</body>

</html>