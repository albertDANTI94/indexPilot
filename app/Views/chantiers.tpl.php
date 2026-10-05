


<div>
<h1>Chantiers</h1>
<p>Gérez vos marchés et contrats de travaux.</p>
</div>

<div class="topbar-right">
<input class="search" placeholder="Rechercher un chantier...">
<a class="btn-primary" href="<?= $router->generate('Chantier-create') ?>">+ Nouveau chantier</a>
</div>

</div>

<div class="kpis">







<div class="kpi">
<h4>Chantiers actifs</h4>
<h2 id="kpiActifs">0</h2>
<p>+3 ce mois</p>
</div>

<div class="kpi">
<h4>Montant total HT</h4>
<h2 id="kpiArchives">0</h2>
<p>Tous marchés</p>
</div>

<div class="kpi">
<h4>Lots associés</h4>
<h2 id="kpiTermines">0</h2>
<p>Sur tous projets</p>
</div>

<div class="kpi">
<h4>Révisions à venir</h4>
<h2 id="kpiSuspendus">0</h2>
<p>Dans les 30 jours</p>
</div>

</div>

<div class="content">

<div class="card">

<div class="tabs">
<button class="active" data-status="all">Tous</button>
<button data-status="active">Actifs</button>
<button data-status="finished">Terminés</button>
<button data-status="suspended">Suspendus</button>
<button data-status="archived">Archivés</button>
</div>

<div class="filters">
<select id="filterClient">
<option>Tous les clients</option>
</select>

<!--<select id="filterStatus">
<option>Tous les statuts</option>
</select>-->

<button>Filtres</button>
</div>

<table>

<thead>
<tr>
<th>Chantier</th>
<th>Client</th>
<th>Description</th>
<th>Date de création</th>
<th>Statut</th>
<th></th>
<th></th>
</tr>
</thead>

<tbody>

<?php foreach ($chantiers as $chantier): ?>

  <tr>

    <td><?= htmlspecialchars($chantier->getNom()) ?></td>
    <td><?= htmlspecialchars($chantier->getClient()) ?></td>
    <td><?= htmlspecialchars($chantier->getDescription()) ?></td>
    <td><?= htmlspecialchars($chantier->getCreatedAt()) ?></td>
    <td ><span class="badge <?= (($current = $chantier->getStatus()) === "active") ? "active-badge" : "finished-badge" ?>"><?= $chantier->getStatus() ?></span></td>
    <td><a class="btn btn-primary-mini" href="<?= $this->router->generate('Chantier-update', ['id' => $chantier->getId()]) ?>">Modifier</a></td>
    <td><a class="btn btn-secondary-mini" href="<?= $this->router->generate('Chantier-delete', ['id' => $chantier->getId()]) ?>">Supprimer</a></td>

  </tr>

<?php endforeach; ?>
</tbody>



</table>

<div class="pagination">
<button>‹</button>
<button class="active">1</button>
<button>2</button>
<button>3</button>
<button>›</button>
</div>

</div>

<div class="sidepanel">

<div class="panel">

<h3>Détails du chantier</h3>







<div class="detail">
<span>Client</span>
<span id="spClient"></span>
</div>

<div class="detail desc">
<span>Description</span>
<span id="spDescription"></span>
</div>

<div class="detail">
<span>Statuts</span>
<span id="spStatus"></span>
</div>

<div class="detail">
<span>Date fin</span>
<span id="spDate"></span>
</div>

<button>Voir le chantier</button>

</div>

<div class="panel">

<h3>Révisions</h3>

<div class="detail">
<span>Lots</span>
<span id="spLotsCount">0</span>
</div>

<div class="detail">
<span>Montant total</span>
<span id="spTotal">0 €</span>
</div>

<div class="detail">
<span>Gain révisions</span>
<span id="spGain" style="color:#16a34a;font-weight:700;">
+0 €
</span>
</div>

</div>

<div class="panel">

<h3>Documents</h3>

<div class="documents">

<div class="document">📄 Marché.pdf</div>
<div class="document">📄 CCAP.pdf</div>
<div class="document">📄 Attestation de révision.pdf</div>

</div>

</div>

</div>

</div>

</main>
<script>
    window.CHANTIERS_DATA = <?= json_encode($chantiersData) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= $_SERVER['BASE_URI']?>/assets/js/chantier-data.js"></script>
