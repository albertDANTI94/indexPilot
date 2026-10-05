


        <div>
            <h1>Tableau de bord</h1>
            <p style="color:#64748b;">Pilotez votre activité tarifaire en temps réel.</p>
        </div>
        <input class="search" type="text" placeholder="Rechercher un chantier, lot, indice...">
        <div class="top-actions">
            <a class="btn-primary" href="<?= $router->generate('Chantier-create') ?>">+ Nouveau chantier</a>
            <!--<button class="btn-primary" onclick="openModal()">+ Nouveau chantier</button>-->
        </div>
    </div>
    

    <section class="kpi-grid">
        <div class="kpi-card">
            <p>Chantiers actifs</p>
            <h3><?= $kpis['chantiers_actifs'] ?? 0 ?></h3>
            <span style="color:green;">+12%</span>
        </div>
        <div class="kpi-card">
            <p>Calculs ce mois-ci</p>
            <h3><?= $kpis['calculs_mois'] ?? 0 ?></h3>
            <span style="color:green;">+233%</span>
        </div>
        <div class="kpi-card">
            <p>Économies estimées</p>
            <h3><?= number_format($kpis['economies'] ?? 0, 0, ',', ' ') ?> €</h3>
            <span style="color:green;">+18%</span>
        </div>
        <div class="kpi-card">
            <p>Alertes indices</p>
            <h3><?= $kpis['alertes'] ?? 0 ?></h3>
            <span style="color:#f59e0b;">À surveiller</span>
        </div>
        



    </section>
  
    <section class="content-grid">
        <div class="panel">
            <div class="panel-header">
              <h2>Évolution des indices</h2>
            </div>
            <div>
              <canvas id="revisionChart"></canvas>
            </div>
        </div>
      
        <div>
            <div class="panel">
                <div class="panel-header">
                  <h2>Derniéres mises à jour d'indices</h2>
                </div>
                <div class="scroller">
                    <div class="alert-list indice-list">
                        <?php foreach ($indices as $indice): ?>
                            <div class="maj-item">
                                <div class="item1">
                                    <strong><?= $indice['libelle'] ?? '---' ?></strong>
                                </div>
                                <div class="item2">
                                    <p style="color:green;"><?= $indice['valeur'] ?? '---' ?></p>
                                </div>
                                <div class="item3">
                                    <p><?= $indice['date'] ?? '---' ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
      
        <div>
            <div class="panel">
                <div class="panel-header">
                  <h2>Alertes récentes</h2>
                </div>
                <div class="alert-list">
                    <?php foreach ($alerts as $alert): ?>
                        <div class="alert-item">
                            <strong style="color:red;"><?= $alert['title'] ?></strong>
                            <p><?= $alert['desc'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="content-grid-b">
    
        <div class="panel">
            <div class="panel-header">
              <h2>Derniers chantiers</h2>
              <a class="btn-primary" href="<?= $_SERVER['BASE_URI']. '/chantiers' ?>">Voir tout</a>
            </div>
            <div class="scroller">
                <table class="indice-list">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Client</th>
                            <th>Statut</th>
                            <th>Date de création</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($chantiers)): ?>
                            <tr>
                                <td colspan="4">Aucun chantier disponible</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($chantiers as $c): ?>
                                <tr>
                                  <td><?= $c->getNom() ?></td>
                                  <td><?= $c->getClient() ?></td>
                                  <td><?= $c->getStatus() ?></td>
                                  <td><?= $c->getCreatedAt() ?></td>
                                  <!--<td style="color:green;"><= number_format($c['revision'], 0, ',', ' ') ?> €</td>-->
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </tbody>
                </table>
            </div>
      </div>
      
      <div class="panel">
        <div class="panel-header">
            <h2>Derniers calculs</h2>
            <a class="btn-primary" href="<?= $_SERVER['BASE_URI']. '/calculs' ?>">Voir tout</a>
        </div>
        <div class="scroller">
            <table class="indice-list">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Coefficient</th>
                        <th>Nouveau tarif</th>
                        <th>Date</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($calculs as $calc): ?>
                    <tr>
                        
    
    
    
                        <td><?= $calc['reference'] ?? '' ?></td>
                
                        <td style="color:green;">
                            <?= number_format($calc['coefficient'], 3, ',', ' ') ?>
                        </td>
                
                        <td>
                            <?= number_format($calc['nouveau_tarif'], 0, ',', ' ') ?>  €
                        </td>
                
                        <td>
                            <?= $calc['created_at'] ?? '' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>



    
    <div>
      <div class="panel yellow-panel">
        <div class="panel-header">
            <h2>Génération de documents</h2>
        </div>
        <div class="docs-list" style="margin-bottom:2vh;">
            <p class="doc-item">Générez vos attestations et documents en quelques clicks</p>
            <a class="btn-primary" href="<?= $_SERVER['BASE_URI']. '/docs' ?>">Créer un document</a>
        </div>
        
      </div>
      <div class="panel" style="margin-top:2rem;">
          <div class="panel-header">
            <h2>Besoin d'aide ?</h2>
        </div>
        <div class="docs-list" style="margin-bottom:2vh;">
            <p class="doc-item">Consultez nos guides ou contactez notre support</p>
        </div>
      </div>
    </div>
  </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revisionChart');

const data = {
  labels: <?= json_encode($chart['labels']) ?>,
  datasets: [{
    label: 'Tarif révisé (€)',
    data: <?= json_encode($chart['values']) ?>,
    borderColor: '#FACC15',
    backgroundColor: '#FACC15',
    tension: 0.4,
    fill: false,
    pointRadius: 6,
    pointHoverRadius: 10,
    pointBorderWidth: 2,
    pointStyle: ['circle'],
    pointBackgroundColor: ['#FACC15'],
    pointBorderColor: '#111827',
  }]
};

const config = {
    type: 'line',

    data: data,

    options: {
        
        responsive: true,

        plugins: {
            legend: {
                labels: {
                    usePointStyle: true
                }
            },

            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y + ' €';
                    }
                }
            }
        },

        scales: {
            y: {
                beginAtZero: false
            }
        }
    }
};

new Chart(ctx, config);
</script>

<!--<script>
  document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
      this.classList.add('active');
    });
  });
</script>-->

<script src="<?= $_SERVER['BASE_URI']?>/assets/js/date.js"></script>
<script src="<?= $_SERVER['BASE_URI']?>/assets/js/modal.js"></script>


