

        <div>
            <h1>Lots</h1>
            <p>Gérez les lots associés à vos marchés.</p>
        </div>

        <div class="topbar-right">
            <input type="text"
                   class="search"
                   placeholder="Rechercher un lot...">

            <a class="btn-primary" href="<?= $router->generate('Lot-create') ?>">+ Nouveau lot</a>
        </div>

    </div>

    <!-- KPI -->

    <div class="kpis">

        <div class="kpi">
            <h4>Lots actifs</h4>
            <h2 id="kpiActiveLots">0</h2>
            
        </div>
        
        <div class="kpi">
            <h4>Montant total HT</h4>
            <h2 id="kpiTotalAmount">0 €</h2>
            <p>Tous lots confondus</p>
        </div>
        
        <div class="kpi">
            <h4>Calculs réalisés</h4>
            <h2 id="kpiCalculs">0</h2>
            <p>Depuis l'ouverture</p>
        </div>
        
        <div class="kpi">
            <h4>Révisions à venir</h4>
            <h2 id="kpiUpcoming">0</h2>
            <p>Dans les 30 prochains jours</p>
        </div>

    </div>

    <div class="content">

        <!-- TABLEAU -->

        <div class="table-card">

            <div class="tabs">

                <button data-status="all" class="active">
                    Tous
                </button>
            
                <button data-status="draft">
                    Brouillons
                </button>
            
                <button data-status="calculated">
                    Calculés
                </button>
            
                <button data-status="validated">
                    Validés
                </button>
            
            </div>

            <div class="filters">



                <select id="filterChantier">
                    <option value="all">
                        Tous les chantiers
                    </option>
                </select>

                <select id="filterIndice">
                    <option>Tous les indices</option>
                </select>

                <select id="filterStatus">
                    <option value="all">Tous les statuts</option>
                    <option value="draft">Brouillon</option>
                    <option value="calculated">Calculé</option>
                    <option value="validated">Validé</option>
                </select>

                <button>
                    Filtres
                </button>

            </div>

            <table>

                <thead>

                <tr>
                    <th>Lot</th>
                    <th>Chantier</th>
                    <th>Tarif</th>
                    <th>Part ferme</th>
                    <th>Coef.</th>
                    <th>Nouveau montant</th>
                    <th>Dernière révision</th>
                    <th>Statut</th>
                </tr>

                </thead>

                <tbody>

                    <?php foreach ($lots as $lot): ?>
    
                      <tr>
                    
                        <td><?= htmlspecialchars($lot->getNom()) ?></td>
                        <td><?= htmlspecialchars($lot->getChantierId()) ?></td><!-- remplancer l'ID par le nom -->
                        <td><?= htmlspecialchars($lot->getTarifOrigine()) ?></td>
                        <td><?= htmlspecialchars($lot->getPartFerme()) ?></td>
                        <td><?= htmlspecialchars($lot->getCoefficient()) ?></td>
                        <td><?= htmlspecialchars($lot->getNouveauTarif()) ?></td>
                        <td><?= htmlspecialchars($lot->getUpdatedAt()) ?></td>
                        <td ><span class="badge <?= (($current = $lot->getStatus()) === "active") ? "active-badge" : "finished-badge" ?>"><?= $lot->getStatus() ?></span></td>
                        <td><a class="btn btn-primary-mini" href="<?= $this->router->generate('Lot-update', ['id' => $lot->getId()]) ?>">Modifier</a></td>
                        <td><a class="btn btn-secondary-mini" href="<?= $this->router->generate('Lot-delete', ['id' => $lot->getId()]) ?>">Supprimer</a></td>
                      </tr>
    
                    <?php endforeach; ?>

                </tbody>

            </table>

            <div class="pagination">

                <button>&laquo;</button>
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button>&raquo;</button>

            </div>

        </div>

        <!-- PANNEAU LATÉRAL -->

        <div class="sidepanel">

            <div class="panel-card">

                <h3>Détails du lot</h3>
                







                <div class="detail-row">
                    <span>Nom</span>
                    <span id="spNom"></span>
                </div>

                <div class="detail-row">
                    <span>Indice</span>
                    <span id="spIndice"></span>
                </div>

                <div class="detail-row">
                    <span>Coefficient</span>
                    <span id="spCoef"></span>
                </div>

                <div class="detail-row">
                    <span>Montant HT</span>
                    <span id="spMontant"></span>
                </div>

                <div class="detail-row">
                    <span>Calculs</span>
                    <span id="spCalculs"></span>
                </div>

                <a href="<?= $router->generate('Calcul-ope') ?>" class="btn-primary">
                    Nouveau calcul
                </a>

            </div>

            <div class="panel-card">

                <h3>Performance financière</h3>

                <div class="detail-row">
                    <span>Gain récupéré</span>
                    <span id="spGain" class="positive">
                        
                    </span>
                </div>

                <div class="detail-row">
                    <span>Dernière révision</span>
                    <span id="spDerniere"></span>
                </div>

                
                <canvas id="performanceChart" class="chart"></canvas>

            </div>

            <div class="panel-card">

                <h3>Documents</h3>

                <div class="docs">

                    <div class="doc">
                        <span>Marché.pdf</span>
                        <small>PDF</small>
                    </div>

                    <div class="doc">
                        <span>CCAP.pdf</span>
                        <small>PDF</small>
                    </div>

                    <div class="doc">
                        <span>Révision_2026.pdf</span>
                        <small>PDF</small>
                    </div>

                </div>

            </div>

        </div>

    </div>

</main>
<!--<pre>
<?php var_dump($lotsData); ?>
</pre>
<pre>
<?php var_dump($lots); ?>
</pre>-->
<script>
   
    /*window.LOTS_DATA = <= json_encode(array_map(function($lotsData) {
        return [
            'id' => $lot->getId(),
            'nom' => $lot->getNom(),
            'chantierId' => $lot->getChantierId(),
            'tarifOrigine' => (float)$lot->getTarifOrigine(),
            'partFerme' => (float)$lot->getPartFerme(),
            'coefficient' => (float)$lot->getCoefficient(),
            'nouveauTarif' => (float)$lot->getNouveauTarif(),
            'status' => $lot->getStatus(),
            'updatedAt' => $lot->getUpdatedAt()
        ];
    }, $lots), JSON_UNESCAPED_UNICODE); ?>;*/
    window.LOTS_DATA = <?= json_encode($lotsData) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= $_SERVER['BASE_URI']?>/assets/js/lot-data.js"></script>


