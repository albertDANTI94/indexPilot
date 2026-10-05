

        <div>
            <h1>Alertes</h1>
            <p>Suivez les événements importants impactant vos marchés.</p>
        </div>

        <div class="topbar-right">

            <input type="text"
                   class="search"
                   placeholder="Rechercher une alerte...">

            <button class="btn-primary">
                Paramètres alertes
            </button>

        </div>

    </div>

    <!-- KPI -->

    <div class="kpis">

        <div class="kpi">
            <h4>Alertes actives</h4>
            <h2>18</h2>
            <p>Dont 4 prioritaires</p>
        </div>

        <div class="kpi">
            <h4>Révisions à effectuer</h4>
            <h2>7</h2>
            <p>Dans les 30 jours</p>
        </div>

        <div class="kpi">
            <h4>Lots impactés</h4>
            <h2>23</h2>
            <p>Par les derniers indices</p>
        </div>

        <div class="kpi">
            <h4>Gain potentiel</h4>
            <h2>84 K€</h2>
            <p>Estimé</p>
        </div>

    </div>

    <div class="content">

        <!-- TABLEAU -->

        <div class="table-card">

            <div class="tabs">
                <button class="active">Toutes</button>
                <button>Critiques</button>
                <button>Révisions</button>
                <button>Indices</button>
                <button>Système</button>
            </div>

            <div class="filters">

                <select>
                    <option>Tous les niveaux</option>
                </select>

                <select>
                    <option>Tous les chantiers</option>
                </select>

                <button>Filtres</button>

            </div>

            <table>

                <thead>
                <tr>
                    <th>Niveau</th>
                    <th>Alerte</th>
                    <th>Chantier</th>
                    <th>Date</th>
                    <th>Impact</th>
                    <th>Statut</th>
                </tr>
                </thead>

                <tbody>

                <tr>
                    <td>
                        <span class="badge badge-danger">
                            Critique
                        </span>
                    </td>
                    <td>
                        Révision de prix en retard
                    </td>
                    <td>
                        Centre Hospitalier
                    </td>
                    <td>
                        01/06/2026
                    </td>
                    <td class="negative">
                        -12 400 €
                    </td>
                    <td>
                        Non traitée
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="badge badge-warning">
                            Important
                        </span>
                    </td>
                    <td>
                        Forte hausse du BT01
                    </td>
                    <td>
                        Groupe Scolaire Lyon
                    </td>
                    <td>
                        03/06/2026
                    </td>
                    <td class="positive">
                        +18 900 €
                    </td>
                    <td>
                        À analyser
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="badge badge-info">
                            Information
                        </span>
                    </td>
                    <td>
                        Nouveaux indices INSEE publiés
                    </td>
                    <td>
                        Tous les marchés
                    </td>
                    <td>
                        02/06/2026
                    </td>
                    <td>
                        -
                    </td>
                    <td>
                        Disponible
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="badge badge-success">
                            Résolue
                        </span>
                    </td>
                    <td>
                        Révision générée
                    </td>
                    <td>
                        Résidence Horizon
                    </td>
                    <td>
                        28/05/2026
                    </td>
                    <td class="positive">
                        +6 850 €
                    </td>
                    <td>
                        Traitée
                    </td>
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

        <!-- SIDEPANEL -->

        <div class="sidepanel">

            <div class="panel-card">

                <h3>Alerte sélectionnée</h3>

                <div class="detail-row">
                    <span>Type</span>
                    <span>Révision en retard</span>
                </div>

                <div class="detail-row">
                    <span>Marché</span>
                    <span>Centre Hospitalier</span>
                </div>

                <div class="detail-row">
                    <span>Échéance</span>
                    <span>01/06/2026</span>
                </div>

                <div class="detail-row">
                    <span>Impact estimé</span>
                    <span class="negative">
                        -12 400 €
                    </span>
                </div>

                <button class="btn-primary">
                    Traiter l'alerte
                </button>

            </div>

            <div class="panel-card">

                <h3>Révisions prioritaires</h3>

                <div class="documents">

                    <div class="document">
                        Lot VRD - Centre Hospitalier
                    </div>

                    <div class="document">
                        Lot GO - Groupe Scolaire
                    </div>

                    <div class="document">
                        Lot Couverture - Médiathèque
                    </div>

                </div>

            </div>

            <div class="panel-card">

                <h3>Statistiques</h3>

                <div class="detail-row">
                    <span>Alertes ce mois</span>
                    <span>42</span>
                </div>

                <div class="detail-row">
                    <span>Traitées</span>
                    <span>36</span>
                </div>

                <div class="detail-row">
                    <span>Taux traitement</span>
                    <span>86%</span>
                </div>

                <div class="detail-row">
                    <span>Gain sécurisé</span>
                    <span class="positive">
                        +184 K€
                    </span>
                </div>

            </div>

        </div>

    </div>

</main>
