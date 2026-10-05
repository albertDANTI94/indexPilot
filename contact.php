<?php require './includes/header.php'; ?>

<?php

// Génération du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Messages flash
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


<main>
    <div class="contentBox">
        <h3>Contactez-nous</h3>

        <?php if ($flash_message): ?>
            <div class="flash-message <?php echo $flash_type; ?>">
                <?php echo htmlspecialchars($flash_message); ?>
            </div>
        <?php endif; ?>

        <form action="functions/contact_submit.php" method="POST" novalidate>
            <!-- CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="formFlexer">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" required>
            </div>

            <div class="formFlexer">
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="formFlexer">
                <label for="message">Message :</label>
                <textarea name="message" id="message" rows="6" cols="30" required></textarea>
            </div>

            <button type="submit">Envoyer</button>
        </form>
    </div>
</main>
</body>

</html>