

<div>

<h1>Calculs</h1>

<p>
Consultez et gérez tous les calculs effectués sur vos lots.
</p>

</div>

<div class="topbar-right">

<input class="search" type="text" placeholder="Rechercher un calcul, lot, indice...">

<a href="<?= $router->generate('Calcul-ope') ?>" class="btn-primary">
+ Nouveau calcul
</a>

</div>

</div>

<!-- KPI -->

<div class="kpis">
<div class="kpi">
<h4>Calculs ce mois-ci</h4>
<h2 id="kpiCalculsMonth"></h2>
<p>Période en cours</p>
</div>

<div class="kpi">
<h4>Économies estimées</h4>
<h2 id="kpiEconomies"></h2>
<p>+12% vs mois précédent</p>
</div>

<div class="kpi">
<h4>Montant révisé</h4>
<h2 id="kpiMontantRevise"></h2>
<p>Tous calculs</p>
</div>

<div class="kpi">
<h4>Impact total HT</h4>
<h2 id="kpiImpact"></h2>
<p>Révisions cumulées</p>
</div>

</div>

<!-- CONTENT -->

<div class="content">

<!-- TABLE -->

<div class="table-card">

<div class="tabs">

<button data-status="all">Tous</button>
<button data-status="positive">Impact positif</button>
<button data-status="negative">Impact négatif</button>
<button data-status="month">Ce mois-ci</button>
<button data-status="year">Cette année</button>

</div>

<div class="filters">

<select id="filterLibelle">
    <option value="all">Tous les libellés</option>
</select>

<select id="filterPeriod">
    <option value="all">Toutes les périodes</option>
    <option value="month">Ce mois</option>
    <option value="year">Cette année</option>
</select>

<button>
Filtres
</button>

</div>

<table>

<thead>

<tr>
<th>Calcul</th>
<th>Tarif</th>
<th>Part Ferme</th>
<th>Libellé</th>
<th>Indice 0</th>
<th>Indice N</th>
<th>Variation</th>
<th>Nouveau Tarif</th>
<th>Date de création</th>
</tr>

</thead>

<tbody>
    <?php foreach ($calculs as $calcul): ?>

<tr>

    <td><?= htmlspecialchars($calcul->reference) ?></td>
    <td><?= htmlspecialchars($calcul->tarifOrigin) ?></td>
    <td><?= htmlspecialchars($calcul->partFerme) ?></td>

    <td>
        <?= htmlspecialchars($calcul->libelleTexte ?? $calcul->libelle) ?>
    </td>

    <td><?= htmlspecialchars($calcul->indiceN0) ?></td>
    <td><?= htmlspecialchars($calcul->indiceNn) ?></td>

    <td><?= htmlspecialchars(number_format($calcul->variation, 2)) ?>%</td>

    <td><?= htmlspecialchars($calcul->nouveauTarif) ?></td>

    <td><?= htmlspecialchars($calcul->date) ?></td>

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

<!-- SIDEPANEL -->

<div class="sidepanel">

<div class="panel-card">

<h3>Détails du calcul</h3>

<div class="detail-row">
    <span>Référence</span>
    <span id="spReference"></span>
</div>

<div class="detail-row">
    <span>Libellé</span>
    <span id="spLibelle"></span>
</div>

<div class="detail-row">
    <span>Période</span>
    <span id="spPeriode"></span>
</div>

<div class="detail-row">
    <span>Indice N0</span>
    <span id="spIndice0"></span>
</div>

<div class="detail-row">
    <span>Indice Nn</span>
    <span id="spIndiceN"></span>
</div>

<div class="detail-row">
    <span>Variation</span>
    <span id="spVariation"></span>
</div>

<div class="detail-row">
    <span>Impact HT</span>
    <span id="spImpact"></span>
</div>



</div>

<div class="panel-card">

<h3>Graphique d'évolution</h3>

<canvas id="calculChart"></canvas>

</div>

<div class="panel-card">

<h3>Documents liés</h3>

<div class="docs">

<div class="doc">
<div>
Attestation de révision<br>
<small>PDF - 245 Ko</small>
</div>
⬇
</div>

<div class="doc">
<div>
Détail du calcul<br>
<small>PDF - 320 Ko</small>
</div>
⬇
</div>

<div class="doc">
<div>
Historique des indices<br>
<small>PDF - 180 Ko</small>
</div>
⬇
</div>

</div>

</div>

</div>

</div>

</main>

<script>

const tabs = document.querySelectorAll('.tabs button');

tabs.forEach(tab => {

    tab.addEventListener('click', () => {

        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

    });

});

</script>
<script>
window.CALCULS_DATA = <?= json_encode(
    array_map(fn($c) => $c->toArray(), $calculs),
    JSON_UNESCAPED_UNICODE
) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= $_SERVER['BASE_URI']?>/assets/js/calcul-data.js"></script>
