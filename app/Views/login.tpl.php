<!-- login.html -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - IndexPilot</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $_SERVER['BASE_URI']?>/assets/css/styles-auth.css">
</head>
<body>

<div class="login-container">

  <div class="login-left">

    <div class="logo">
      Index<span>Pilot</span>
    </div>

    <div class="hero-content">

      <h1>
        Pilotez.<br>
        Calculez.<br>
        <span>Sécurisez.</span>
      </h1>

      <p>
        La plateforme SaaS de référence pour automatiser vos révisions tarifaires et sécuriser vos marchés.
      </p>

      <div class="features">

        <div class="feature">
          <div class="feature-icon">✓</div>
          <div>
            <h3>Données INSEE automatiques</h3>
            <p>Mises à jour continues des indices officiels.</p>
          </div>
        </div>

        <div class="feature">
          <div class="feature-icon">⚡</div>
          <div>
            <h3>Calculs instantanés</h3>
            <p>Réduisez le temps administratif et les erreurs.</p>
          </div>
        </div>

        <div class="feature">
          <div class="feature-icon">🔒</div>
          <div>
            <h3>Sécurisé</h3>
            <p>Infrastructure robuste et données protégées.</p>
          </div>
        </div>

      </div>

    </div>

  </div>

  <div class="login-right">

    <div class="form-container">

      <h2>Connexion</h2>

      <p class="subtitle">
        Accédez à votre espace professionnel.
      </p>

      <form action="" method="POST">
        <?php
        // On inclut la sous-vue/partial form_errors.tpl.php
        include __DIR__ . '/partials/form_errors.tpl.php';
        ?>
        <div class="input-group">
          <label>Email professionnel</label>
          <input type="email" id="email" name="email" placeholder="exemple@entreprise.fr">
        </div>

        <div class="input-group">
          <label>Mot de passe</label>
          <input type="password" id="password" name="password" placeholder="Votre mot de passe">
        </div>

        <div class="forgot">
          <a href="#">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn-primary">
          Se connecter
        </button>

      </form>

      <div class="separator">
        ou continuer avec
      </div>

      <div class="social-buttons">

        <button class="social-btn">
          Continuer avec Google
        </button>

        <button class="social-btn">
          Continuer avec LinkedIn
        </button>

      </div>

      <div class="register-link">
        Pas encore de compte ?
        <a href="<?= $_SERVER['BASE_URI']. '/register' ?>">Créer un compte</a>
      </div>

    </div>

  </div>

</div>

</body>
</html>