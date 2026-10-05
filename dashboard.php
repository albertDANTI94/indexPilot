<?php require './includes/header.php'; ?>

<!--<div class="dashboardContainer">

    <div class="chantier">
        <p>Bienvenue, <?php //echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); 
                        ?></p>
        <h3>MES CHANTIERS</h3>
        <div class="nouveauChantier">
            <button id="addChantierBtn">Ajouter un chantier</button>
        </div>
    </div>

    <div class="globalContainer">
        <div class="chantierContainer" id="chantierContainer">-->
<!-- Chantiers chargés via JS -->
<!-- </div>

    </div>

</div>-->

<!-- Modal création chantier -->
<!--<div id="chantierModal" class="modal">
    <div class="modalContent">
        <span class="closeModal" id="closeChantierModal">&times;</span>
        <h3>Créer un nouveau chantier</h3>
        <form id="chantierForm">
            <label>Nom du chantier</label>
            <input type="text" name="nom" required>
            <button type="submit">Créer</button>
        </form>
    </div>
</div>-->

<!-- Modal ajout lot -->
<!--<div id="lotModal" class="modal">
    <div class="modalContent">
        <span class="closeModal" id="closeLotModal">&times;</span>
        <h3 class="formTittle">Ajouter un lot</h3>
        <form id="lotForm">
            <p>Pour trouver vos Indexes suivez ce lien : <a href="https://www.insee.fr/fr/statistiques/series/103173847" target="_blank">Indexes INSEE</a></p>
            <input type="hidden" name="chantier_id" id="lotChantierId">
            <div class="formFlexer">
                <label>Nom du lot</label>
                <input type="text" name="nom" required>
            </div>
            <div class="formFlexer">
                <label>Prix initial (€)</label>
                <input type="number" step="0.01" name="tarif_origine" required>
            </div>
            <div class="formFlexer">
                <label>Indice INSEE Mois 0</label>
                <input type="number" step="0.0001" name="indice0" required>
            </div>
            <div class="formFlexer">
                <label>Indice INSEE Mois N</label>
                <input type="number" step="0.0001" name="indiceN" required>
            </div>
            <div class="formFlexer">
                <button type="submit">Ajouter le lot</button>
            </div>
        </form>
    </div>
</div>


<script src="./assets/js/dashboardModal.js" defer></script>
</body>

</html>

<php

require_once __DIR__ . '/includes/db.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération des chantiers de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM chantiers WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute(['user_id' => $user_id]);
$chantiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>-->



<!--<div class="dashboardContainer">

    <div class="chantier">
        <p>Bienvenue, <php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?></p>
        <h3>MES CHANTIERS</h3>
        <button id="openCreateChantierModal">+ Nouveau chantier</button>
    </div>
    <div id="chantiersContainer">
        <php foreach ($chantiers as $chantier): ?>
            <div class="chantier-card" data-id="<= htmlspecialchars($chantier['id']) ?>">
                <h3><= htmlspecialchars($chantier['nom']) ?></h3>

                <-- Bouton suppression >
                <button class="delete-chantier" data-id="<?= $chantier['id'] ?>">Supprimer</button>

                <-- Liste des lots >
                <div class="lots">
                    <php
                    $stmt2 = $pdo->prepare("SELECT * FROM lots WHERE chantier_id = :chantier_id");
                    $stmt2->execute(['chantier_id' => $chantier['id']]);
                    $lots = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($lots as $lot):
                    ?>
                        <div class="lot">
                            <strong><= htmlspecialchars($lot['nom']) ?></strong><br>
                            Ancien tarif : <= htmlspecialchars($lot['tarif_origine']) ?> €<br>
                            Indice 0 : <= htmlspecialchars($lot['indice0']) ?><br>
                            Indice N : <= htmlspecialchars($lot['indiceN']) ?><br>
                            Coefficient : <= htmlspecialchars($lot['coefficient']) ?><br>
                            Nouveau tarif : <= htmlspecialchars($lot['nouveau_tarif']) ?> €
                        </div>
                    <php endforeach; ?>
                </div>


                <-- Ajout de lot >
                <form class="add-lot-form" data-chantier-id="<= $chantier['id'] ?>">
                    <input type="text" name="lot_nom" placeholder="Nom du lot" required>
                    <button type="submit">Ajouter</button>
                </form>
            </div>
        <php endforeach; ?>
    </div>
</div>

<-- Modale création chantier >
<div id="createChantierModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h2>Créer un chantier</h2>
        <form id="createChantierForm">
            <input type="text" name="nom" placeholder="Nom du chantier" required>
            <button type="submit">Créer</button>
        </form>
        <button id="closeCreateChantierModal">Annuler</button>
    </div>
</div>

<-- Modal ajout lot >
<div id="lotModal" class="modal">
    <div class="modalContent">
        <span class="closeModal" id="closeLotModal">&times;</span>
        <h3 class="formTittle">Ajouter un lot</h3>
        <form id="lotForm">
            <p>Pour trouver vos Indexes suivez ce lien : <a href="https://www.insee.fr/fr/statistiques/series/103173847" target="_blank">Indexes INSEE</a></p>
            <input type="hidden" name="chantier_id" id="lotChantierId">
            <div class="formFlexer">
                <label>Nom du lot</label>
                <input type="text" name="nom" required>
            </div>
            <div class="formFlexer">
                <label>Prix initial (€)</label>
                <input type="number" step="0.01" name="tarif_origine" required>
            </div>
            <div class="formFlexer">
                <label>Indice INSEE Mois 0</label>
                <input type="number" step="0.0001" name="indice0" required>
            </div>
            <div class="formFlexer">
                <label>Indice INSEE Mois N</label>
                <input type="number" step="0.0001" name="indiceN" required>
            </div>
            <div class="formFlexer">
                <button type="submit">Ajouter le lot</button>
            </div>
        </form>
    </div>
</div>

<script src="./assets/js/dashboardModal.js"></script>
</body>

</html>-->

<?php
require_once __DIR__ . '/includes/db.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>



<div class="dashboardContainer">

    <div class=" chantier">
        <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?></p>
        <h3>MES CHANTIERS</h3>
        <button id="openCreateChantierModal">+ Nouveau chantier</button>
    </div>

    <!-- ⚡ Conteneur vidé au départ — rempli par dashboardModal.js -->
    <div id="chantiersContainer">
        <p>Chargement des chantiers...</p>
    </div>
</div>

<!-- Modale création chantier -->
<div id="createChantierModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h2>Créer un chantier</h2>
        <form id="createChantierForm">
            <input type="text" name="nom" placeholder="Nom du chantier" required>
            <button type="submit">Créer</button>
        </form>
        <button id="closeCreateChantierModal">Annuler</button>
    </div>
</div>

<!-- Modale ajout lot 
<div id="lotModal" class="modal" style="display:none;">
    <div class="modalContent">
        <span class="closeModal" id="closeLotModal">&times;</span>
        <h3 class="formTittle">Ajouter un lot</h3>
        <form id="lotForm">
            <p>
                Pour trouver vos Indexes suivez ce lien :
                <a href="https://www.insee.fr/fr/statistiques/series/103173847" target="_blank">Indexes INSEE</a>
            </p>
            <input type="hidden" name="chantier_id" id="lotChantierId">

            <div class="formFlexer">
                <label>Nom du lot</label>
                <input type="text" name="nom" required>
            </div>
            <div class="formFlexer">
                <label>Prix initial (€)</label>
                <input type="number" step="0.01" name="tarif_origine" required>
            </div>
            <div class="formFlexer">
                <label>Indice INSEE Mois 0</label>
                <input type="number" step="0.0001" name="indice0" required>
            </div>
            <div class="formFlexer">
                <label>Indice INSEE Mois N</label>
                <input type="number" step="0.0001" name="indiceN" required>
            </div>
            <div class="formFlexer">
                <button type="submit">Ajouter le lot</button>
            </div>
        </form>
    </div>
</div>

<!-- Modale ajout lot -->
<div id="lotModal" class="modal" style="display:none;">
    <div class="modalContent">
        <span class="closeModal" id="closeLotModal">&times;</span>
        <h3 class="formTittle">Ajouter un lot</h3>

        <form id="lotForm">

            <input type="hidden" name="chantier_id" id="lotChantierId">

            <div class="formFlexer">
                <label>Nom du lot</label>
                <input type="text" name="nom" required>
            </div>

            <div class="formFlexer">
                <label for="tarif_origine">Montant acompte mensuel (M0)</label>
                <input type="number" step="0.01" id="tarif_origine" name="tarif_origine" required>
            </div>
            
            <div class="formFlexer">
                <label for="part_ferme">La formule de révision contient-elle une part ferme ?</label>
                <div class="verticalFlexer">
                    <input type="number" step="0.01" id="part_ferme" name="part_ferme" required>
                    <p>aide : <span>si la formule est Cn = 0.15 + 0.85 (In/Io) saisir 15%, si il y a révision intégrale saisir 0</span></p>
                </div>
            </div>

            <!-- Nouveau : Sélecteur de libellé -->
            <div class="formFlexer">
                <label>Choisir un indice INSEE</label>
                <select name="libelle" id="selectLibelle" required>
                    <option value="">-- Choisir --</option>
                </select>
            </div>

            <!-- Dates -->
            <div class="formFlexer">
                <label>Mois 0 (YYYY-MM)</label>
                <input type="month" id="date0" required>
            </div>

            <div class="formFlexer">
                <label>Mois N (YYYY-MM)</label>
                <input type="month" id="dateN" required>
            </div>

            <!-- Indices récupérés automatiquement -->
            <div class="formFlexer">
                <label>Indice INSEE Mois 0</label>
                <input type="number" step="0.0001" name="indice0" id="indice0" readonly required>
            </div>

            <div class="formFlexer">
                <label>Indice INSEE Mois N</label>
                <input type="number" step="0.0001" name="indiceN" id="indiceN" readonly required>
            </div>
            
            <div class="formFlexer">
                <label>S’abonner à cet indice</label>
                <input type="checkbox" id="subscribeIndice">
            </div>

            <div class="formFlexer">
                <button type="submit">Ajouter le lot</button>
            </div>
        </form>
    </div>
</div>

<script src="./public/assets/js/date.js"></script>
<script src="./public/assets/js/dashboardModal.js"></script>
</body>

</html>