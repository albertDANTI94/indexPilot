<!-- register.html -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - IndexPilot</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $_SERVER['BASE_URI']?>/assets/css/styles-auth.css">
 
</head>
<body>

<div class="register-wrapper">

  <div class="left-panel">

    <div class="logo">
      Index<span>Pilot</span>
    </div>

    <div>

      <h1>
        Automatisez vos<br>
        <span>révisions tarifaires.</span>
      </h1>

      <p>
        Rejoignez les professionnels qui sécurisent déjà leurs marchés grâce à une plateforme SaaS moderne, fiable et conçue pour les marchés complexes.
      </p>

    </div>

  </div>

  <div class="right-panel">

    <div class="form-card">

      <h2>Créer un compte</h2>

      <p>
        Commencez à piloter vos révisions tarifaires en quelques minutes.
      </p>

      <form action="" method="POST">
        <?php
        // On inclut la sous-vue/partial form_errors.tpl.php
        include __DIR__ . '/partials/form_errors.tpl.php';
        ?>

        <div class="grid">

          <div class="input-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" placeholder="Jean">
          </div>

          <div class="input-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Dupont">
          </div>

          <div class="input-group full">
            <label for="email">Email professionnel</label>
            <input type="email" id="email" name="email" placeholder="exemple@entreprise.fr">
          </div>

          <div class="input-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Créer un mot de passe">
          </div>

          <div class="input-group">
            <label for="confirm">Confirmation</label>
            <input type="password" id="confirm" name="confirm" placeholder="Confirmer le mot de passe">
          </div>

          <div class="input-group full">
            <label for="entreprise">Entreprise</label>
            <input type="text" id="entreprise" name="entreprise" placeholder="Nom de votre société">
          </div>

          <div class="input-group">
            <label>Secteur</label>
            <select name="secteur" id="secteur">
                <option value="construction">Construction</option>
                <option value="batiment">Bâtiment</option>
                <option value="TP">Travaux Publics</option>
              
            </select>
          </div>

          <div class="input-group">
            <label>Taille</label>
            <select name="size" id="size">
              <option value="1-10">1-10 salariés</option>
              <option value="10-50">10-50 salariés</option>
              <option value="50+">50+</option>
            </select>
          </div>

        </div>

        <div class="checkbox">
          <input type="checkbox">
          <div>
            J'accepte les
            <a href="#">Conditions Générales</a>
            et la
            <a href="#">Politique de confidentialité</a>.
          </div>
        </div>

        <button type="submit" class="btn-primary">
          Créer mon compte
        </button>

      </form>

      <div class="separator">
        ou continuer avec
      </div>

      <div class="social-grid">

        <button class="social-btn">
          Google
        </button>

        <button class="social-btn">
          LinkedIn
        </button>

      </div>

      <div class="login-link">
        Déjà un compte ?
        <a href="<?= $_SERVER['BASE_URI']. '/login' ?>">Se connecter</a>
      </div>

    </div>

  </div>

</div>

</body>
</html>