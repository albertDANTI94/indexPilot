<!DOCTYPE html>

<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IndexPilot - Automatisez vos révisions tarifaires</title>
  <link rel="icon" type="image/png" sizes="32x32" href="<?= $_SERVER['BASE_URI']?>/assets/images/indexpilot.png">
  <link rel="stylesheet" href="<?= $_SERVER['BASE_URI']?>/assets/css/styles-landing.css"
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@32,200,0,0" />
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    
  </style>
</head>
<body>
<header>
  <div class="container nav">
    <div class="logo"><a href="<?= $_SERVER['BASE_URI']?>"><img class="logo-img" src="<?= $_SERVER['BASE_URI']?>/assets/images/logo-indexpilot.png"></a></div>
    <nav class="nav-links">
        <a href="#enjeu">Pourquoi</a>
        <a href="#features">Fonctionnalités</a>
        <a href="#benefits">Bénéfices</a>
        <a href="#pricing">Tarifs</a>
        <!--<a href="#contact">Contact</a>-->
    </nav>
    <nav class="nav-links">
        <a href="<?= $_SERVER['BASE_URI']. '/login' ?>">connexion</a>
        <a class="btn btn-primary">Essai gratuit</a>
    </nav>
    
  </div>
</header>

<section class="hero container">
  <div>
    <div class="badge">SaaS métier premium • Plateforme de révision tarifaire • Marchés publics</div>
    <h1>Sécurisez vos marges.<br/>Automatisez vos révisions.<br/><span class="yellow-txt">Gagnez en sérénité.</span></h1>
    <p>Centralisez vos calculs, surveillez automatiquement les indices INSEE et réduisez vos risques contractuels grâce à une plateforme conçue pour les professionnels exigeants.</p>
    <div class="hero-buttons">
      <a class="btn btn-primary">Tester gratuitement</a>
      <a class="btn btn-secondary">Voir la démonstration</a>
    </div>
    <div class="trust-grid">
      <div class="trust-item">✔ Indices INSEE automatisés</div>
      <div class="trust-item">✔ Sécurité renforcée</div>
      <div class="trust-item">✔ Conformité métier</div>
      <div class="trust-item">✔ Support expert</div>
    </div>
  </div>

  <!--<div class="dashboard">
        <div class="dashboard-top">
          <div class="metric"><p>Chantiers actifs</p><h3>42</h3></div>
          <div class="metric"><p>Calculs mensuels</p><h3>148</h3></div>
          <div class="metric"><p>Marge sécurisée</p><h3>+18%</h3></div>
        </div>
        <div class="chart-card">
          <strong>Évolution des indices</strong>
          <div class="chart">
            <div class="bar" style="height:42%"></div>
            <div class="bar" style="height:55%"></div>
            <div class="bar" style="height:61%"></div>
            <div class="bar" style="height:58%"></div>
            <div class="bar" style="height:74%"></div>
            <div class="bar" style="height:88%"></div>
            <div class="bar" style="height:81%"></div>
          </div>
        </div>
        <div class="activity">
          <div class="activity-item"><span>Lot Gros œuvre</span><strong style="color:#22c55e">Révision validée</strong></div>
          <div class="activity-item"><span>Indice BT01</span><strong style="color:#facc15">Mise à jour détectée</strong></div>
        </div>
    </div>-->
    <img class="mockup" src="<?= $_SERVER['BASE_URI']?>/assets/images/Dashboard-Mockup.png">
  
</section>

<section id="enjeu" class="dark">
  <div class="container">
    <h3 class="section-id">UN ENJEU CRITIQUE</h3>
    <h2 class="section-title">Les révisions tarifaires <span class="yellow-txt">mal calculées</span><br/> peuvent coûter cher à votre entreprise.</h2>
    <p class="section-subtitle">Réduisez les erreurs, automatisez la veille et sécurisez votre rentabilité face à la complexité contractuelle.</p>
    <div class="grid-4">
        <div class="problem right-line">
            <div class="circle">
                <span class="material-symbols-rounded">warning</span>
            </div>
          <h4>Risque financier</h4>
          <p class="pbm-txt">Des erreurs de calcul peuvent vous faire perdre des milliers d'euros sur vos marchés.</p>
        </div>
        <div class="problem right-line">
            <div class="circle b">
                <span class="material-symbols-rounded">nest_clock_farsight_analog</span>
            </div>
            <h4>Perte de temps</h4>
            <p class="pbm-txt">Recherches d'indices, saisies manuelles, vérifications... un processus chronophage.</p>
        </div>
        <div class="problem right-line">
            <div class="circle b">
                <span class="material-symbols-rounded">gpp_bad</span>
            </div>
            <h4>Non-conformité</h4>
            <p class="pbm-txt">Des révisions non justifiées peuvent entrainer des Iitiges et des pénalités.</p>
        </div>
        <div class="problem">
            <div class="circle b">
                <span class="material-symbols-rounded">folder</span>
            </div>
            <h4>Suivi complexe</h4>
            <p class="pbm-txt">Difficile de suivre l'historique des indices et des révisions sur plusieurs chantiers.</p>
        </div>
    </div>
  </div>
</section>

<section id="features" class="light">
  <div class="container hero">
    <div>
        <h3 class="section-id">LA SOLUTION INDEXPILOT</h3>
        <h2 class="section-title left">Une plateforme unique pour piloter chaque révision <span class="yellow-txt">en toute confiance</span></h2>
        <p class="left">Une plateforme complète pour automatiser, sécuriser et justifier toutes vos révisions tarifaires.</p>
        <ul class="yellow-check">
            <li>Mise à jour automatique des indices INSEE</li>
            <li>Calculs fiables selon les formules de vos contrats</li>
            <li>Historique complet par chantiers et par lots</li>
            <li>Génération automatiques de documents prêts à l'emploi</li>
            <li>Alerte en cas de variation des indices</li>
            <li>Exports et partages facilités</li>
        </ul>
        <a class="btn btn-secondary">Découvrir toutes les fonctionnalités <span class="material-symbols-rounded mini">arrow_forward</span></a>
    </div>
    <div class="solution-right">
        <img src="<?= $_SERVER['BASE_URI']?>/assets/images/interface-dynamique-IndexPilot.png">
    </div>
  </div>
</section>

<section id="benefits">
  <div class="container">
    <h2 class="section-title">Pourquoi les professionnels l’adoptent</h2>
    <div class="benefits">
      <div class="benefit">Sécurisation durable des marges</div>
      <div class="benefit">Réduction massive des erreurs</div>
      <div class="benefit">Gain de temps opérationnel</div>
      <!--<div class="benefit">Conformité contractuelle renforcée</div>-->
      <div class="benefit">Centralisation stratégique</div>
      <div class="benefit">Rentabilité mesurable</div>
    </div>
  </div>
</section>

<section id="pricing" class="pricing">
  <div class="container">
    <h2 class="section-title">Des offres adaptées à votre activité</h2>
    <p class="section-subtitle">Montez en puissance avec des fonctionnalités alignées sur votre niveau d’exigence.</p>
    <div class="grid-3">
      <div class="card">
        <h3>Starter</h3>
        <p>Pour indépendants</p>
        <div class="price">39€</div>
        <ul>
            <li><span class="yellow-txt">✔</span> Jusqu'à 10 chantiers</li>
            <li><span class="yellow-txt">✔</span> Mise à jour des indices</li>
            <li><span class="yellow-txt">✔</span> Calculs illimités</li>
            <li><span class="yellow-txt">✔</span> Historique de base</li>
            <li><span class="yellow-txt">✔</span> Génération PDF</li>
        </ul>
        <a class="btn btn-secondary">Choisir</a>
      </div>
      <div class="card pricing-highlight">
        <div class="popular">Le plus populaire</div>
        <h3>Pro</h3>
        <p>Pour prestataires réguliers</p>
        <div class="price">99€</div>
        <ul>
            <li><span class="yellow-txt">✔</span> Chantiers illimités</li>
            <li><span class="yellow-txt">✔</span> Mise à jour des indices</li>
            <li><span class="yellow-txt">✔</span> Calculs illimités</li>
            <li><span class="yellow-txt">✔</span> Historique illimité</li>
            <li><span class="yellow-txt">✔</span> Génération PDF avancée</li>
            <li><span class="yellow-txt">✔</span> Alertes & notifications</li>
            <li><span class="yellow-txt">✔</span> Support prioritaire</li>
            
        </ul>
        <a class="btn btn-primary">Choisir Pro</a>
      </div>
      <div class="card">
        <h3>Enterprise</h3>
        <p>Structures complexes</p>
        <div class="price">Sur devis</div>
        <ul>
            <li><span class="yellow-txt">✔</span> Toutes les fonctionnalités Pro</li>
            <li><span class="yellow-txt">✔</span> Multi-utilisateurs</li>
            <li><span class="yellow-txt">✔</span> Permissions avancées</li>
            <li><span class="yellow-txt">✔</span> IA métier</li>
            <li><span class="yellow-txt">✔</span> Intégration API</li>
            <li><span class="yellow-txt">✔</span> Support dédié</li>
        </ul>
        <a class="btn btn-secondary">Nous contacter</a>
      </div>
    </div>
  </div>
</section>

<section class="final-cta">
  <div class="container">
    <h2>Reprenez le contrôle de votre rentabilité contractuelle.</h2>
    <p>Transformez une obligation complexe en avantage stratégique durable.</p>
    <a class="btn btn-primary" href="<?= $_SERVER['BASE_URI']. '/register' ?>">Commencer maintenant</a>
  </div>
</section>

<footer>
    <div class="footer-grid">
        <div>
            <div class="logo"><img class="logo-img" src="<?= $_SERVER['BASE_URI']?>/assets/images/logo-indexpilot.png"></div>
                <h2 class="section-title">Prêt à sécuriser vos révisions tarifaires ?</h2>
                <p>La référence SaaS de la révision tarifaire intelligente.</p>
        </div>
        <div class="footer-right-btns">
            <a class="btn btn-primary">Essayer gratuitement 14 jours </a>
            <a class="btn btn-tiercary">Planifier une démonstration</a>
        </div>
    </div>
    <div class="footer-grid">
        <!--<div class="grid-3">-->
            <div class="footer-value-points">
                <span class="material-symbols-rounded footer-mini">deployed_code</span>
                <div>
                    <h4>Mise en place rapide</h4>
                    <p class="pbm-txt footer-pbm-txt">Accédez à votre compte en quelques minutes.</p> 
                </div>
            </div>
            <div class="footer-value-points">
                <span class="material-symbols-rounded footer-mini">encrypted</span>
                <div>
                    <h4>Données sécurisées</h4>
                    <p class="pbm-txt footer-pbm-txt">Hébergement en France & conformité RGPD.</p> 
                </div>
            </div>
            <div class="footer-value-points">
                <span class="material-symbols-rounded footer-mini">call</span>
                <div>
                    <h4>Support réactif</h4>
                    <p class="pbm-txt footer-pbm-txt">Une assistance rapide et à votre écoute.</p> 
                </div>
            </div>
        <!--</div>-->
    </div>
    

    <div class="footer-grid footer-nav">
        <nav class="nav-links">
            <a href="#enjeu">Pourquoi</a>
            <a href="#features">Fonctionnalités</a>
            <a href="#benefits">Bénéfices</a>
            <a href="#pricing">Tarifs</a>
            <a href="#docu">Documentation</a>
            <a href="#mentions">Mentions légales</a>
            <a href="#conf">Confidentialité</a>
            <a href="#contact">Contact</a>
        </nav>
    </div>
</footer>
<script>lucide.createIcons();</script>
</body>
</html>

