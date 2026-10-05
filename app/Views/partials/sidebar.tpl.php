<?php
    if (isset($_SESSION['userId'])){
?>
<aside class="sidebar">
    <div>
        <div class="logo"><a href="<?= $this->router->generate('home') ?>"><img class="logo-img" src="<?= $_SERVER['BASE_URI']?>/assets/images/logo-indexpilot.png"></a></div>
        <nav class="nav-menu">
            <a class="nav-item <?= ($currentPage === "dashboard") ? "active" : "" ?>" href="<?= $router->generate('dashboard') ?>"><span class="material-symbols-rounded nav-mini">home</span> Tableau de bord</a>
            <a class="nav-item <?= ($currentPage === "/chantiers") ? "active" : "" ?>" href="<?= $router->generate('Chantier-index') ?>"><span class="material-symbols-rounded nav-mini">home_repair_service</span> Chantiers</a>
            <a class="nav-item <?= ($currentPage === "/lots") ? "active" : "" ?>" href="<?= $router->generate('Lot-index') ?>"><span class="material-symbols-rounded nav-mini">folder</span> Lots</a>
            <a class="nav-item <?= ($currentPage === "/calculs") ? "active" : "" ?>" href="<?= $router->generate('Calcul-index') ?>"><span class="material-symbols-rounded nav-mini">home</span> Calculs</a>
            <a class="nav-item <?= ($currentPage === "/calculateur") ? "active" : "" ?>" href="<?= $router->generate('Calcul-ope') ?>"><span class="material-symbols-rounded nav-mini">calculate</span> Simulateur</a>
            <a class="nav-item <?= ($currentPage === "/indices") ? "active" : "" ?>" href="<?= $_SERVER['BASE_URI']. '/indices' ?>"><span class="material-symbols-rounded nav-mini">finance</span> Indices INSEE</a>
            <a class="nav-item <?= ($currentPage === "/docs") ? "active" : "" ?>" href="<?= $_SERVER['BASE_URI']. '/documents' ?>"><span class="material-symbols-rounded nav-mini">docs</span> Documents</a>
            <a class="nav-item <?= ($currentPage === "/alerts") ? "active" : "" ?>" href="<?= $_SERVER['BASE_URI']. '/alerts' ?>"><span class="material-symbols-rounded nav-mini">notifications</span> Alertes</a>
            <a class="nav-item <?= ($currentPage === "/reports") ? "active" : "" ?>" href="<?= $_SERVER['BASE_URI']. '/reports' ?>"><span class="material-symbols-rounded nav-mini">analytics</span> Rapports</a>
            <a class="nav-item <?= ($currentPage === "/settings") ? "active" : "" ?>" href="<?= $_SERVER['BASE_URI']. '/settings' ?>"><span class="material-symbols-rounded nav-mini">settings</span> Paramètres</a>
        </nav>

        <div class="plan-box">
            <strong>Plan Pro</strong>
            <p>Renouvellement le 20/06/2026</p>
            <button>Gérer mon abonnement</button>
        </div>
    </div>

    <div class="user-box">
        <div class="avatar">TM</div>
        <div>
          <strong>Thomas Martin</strong>
          <p style="color:#94a3b8; font-size:0.9rem;">Entreprise ABC</p>
        </div>
    </div>
</aside>
<div id="sidebarOverlay"></div>
<?php
}
?>