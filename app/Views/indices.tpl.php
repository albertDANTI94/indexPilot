

        <div>
            <h1>Indices INSEE</h1>
            <p>Suivez les évolutions des indices utilisés dans vos marchés.</p>
        </div>

        <div class="topbar-right">

            <input type="text" id="indicesSearch" class="search" placeholder="Rechercher un indice...">
            <button class="btn-primary">
                Synchroniser
            </button>

        </div>

    </div>

    <!-- KPI -->

    <div class="kpis">

        <div class="kpi">
            <h4>Total indices</h4>
            <h2 id="kpiTotal">0</h2>
            <p>Base active</p>
        </div>
    
        <div class="kpi">
            <h4>Variations fortes</h4>
            <h2 id="kpiStrong">0</h2>
            <p>> 3%</p>
        </div>
    
        <div class="kpi">
            <h4>Lots impactés</h4>
            <h2 id="kpiLots">0</h2>
            <p>Total cumulé</p>
        </div>
    
        <div class="kpi">
            <h4>Dernière MAJ</h4>
            <h2 id="kpiLast">—</h2>
            <p>INSEE</p>
        </div>
    
    </div>

    <div class="content">

        <!-- TABLEAU -->

        <div class="table-card">

            <div class="tabs">
                <button data-tab="all" class="active">Tous</button>
                <button data-tab="BT">BT</button>
                <button data-tab="TP">TP</button>
                <button data-tab="ICC">ICC</button>
            </div>

            <div class="filters">

                <select id="filterLibelle">
                    <option value="all">Tous les libellés</option>
                </select>

                <select id="filterVariation">
                    <option value="all">Toutes les variations</option>
                    <option value="positive">Positives</option>
                    <option value="negative">Négatives</option>
                </select>

                <button>Filtres</button>

            </div>

            <table>

                <thead>

                <tr>
                    <th>Indice</th>
                    <th>Libellé</th>
                    <th>Valeur</th>
                    <th>Ancienne valeur</th>
                    <th>Variation</th>
                    <th>Date</th>
                    <th>Lots impactés</th>
                </tr>

                </thead>

                <tbody id="indicesTbody">

                <tr>
                    <td>TP01</td>
                    <td>Travaux publics généraux</td>
                    <td>132.45</td>
                    <td>128.80</td>
                    <td class="positive">+2.83%</td>
                    <td>05/2026</td>
                    <td>8</td>
                </tr>

                <tr>
                    <td>BT01</td>
                    <td>Tous corps d'état</td>
                    <td>125.14</td>
                    <td>122.90</td>
                    <td class="positive">+1.82%</td>
                    <td>05/2026</td>
                    <td>12</td>
                </tr>

                <tr>
                    <td>BT30</td>
                    <td>Couverture</td>
                    <td>118.20</td>
                    <td>119.05</td>
                    <td class="negative">-0.71%</td>
                    <td>05/2026</td>
                    <td>3</td>
                </tr>

                <tr>
                    <td>BT45</td>
                    <td>Menuiseries</td>
                    <td>141.60</td>
                    <td>135.50</td>
                    <td class="positive">+4.50%</td>
                    <td>05/2026</td>
                    <td>6</td>
                </tr>

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
        
        <div class="sidepanel" id="indicesSidepanel">

    <div class="panel-card">

        <h3>Indice sélectionné</h3>

        <div class="detail-row">
            <span>Code</span>
            <span id="spCode">-</span>
        </div>

        <div class="detail-row">
            <span>Libellé</span>
            <span id="spLibelle">-</span>
        </div>

        <div class="detail-row">
            <span>Valeur</span>
            <span id="spValue">-</span>
        </div>

        <div class="detail-row">
            <span>Ancienne valeur</span>
            <span id="spOldValue">-</span>
        </div>

        <div class="detail-row">
            <span>Variation</span>
            <span id="spVariation">-</span>
        </div>

        <div class="detail-row">
            <span>Date</span>
            <span id="spDate">-</span>
        </div>

    </div>

    <div class="panel-card">

        <h3>Impact estimé</h3>

        <div class="detail-row">
            <span>Lots impactés</span>
            <span id="spLots">0</span>
        </div>

        <div class="detail-row">
            <span>Montant concerné</span>
            <span id="spMontant">0 €</span>
        </div>

        <div class="detail-row">
            <span>Révision potentielle</span>
            <span id="spRevision">0 €</span>
        </div>

        <canvas id="performanceChart"></canvas>

    </div>

    <div class="panel-card">

        <h3>Synchronisation</h3>

        <div class="detail-row">
            <span>Dernière MAJ</span>
            <span id="spLastUpdate">-</span>
        </div>

        <div class="detail-row">
            <span>Source</span>
            <span>INSEE</span>
        </div>

        <div class="detail-row">
            <span>État</span>
            <span class="positive">
                Synchronisé
            </span>
        </div>

        <a href="/functions/sync_insee.php" class="btn-primary">
            Actualiser
        </a>

    </div>

</div>

    </div>

</main>

<script>
    window.INDICES_DATA = <?= json_encode($indices ?? [], JSON_HEX_TAG | JSON_HEX_APOS) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= $_SERVER['BASE_URI']?>/assets/js/indices-data.js"></script>
