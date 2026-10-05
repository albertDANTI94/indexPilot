<?php require_once __DIR__ . '/../../includes/config.php'; ?>


 
      <div>
        <h1>Calculs de révision</h1>
        <p>Automatisez et sécurisez vos révisions tarifaires.</p>
      </div>

      <div class="topbar-actions">
        <button class="btn btn-secondary">Export Excel</button>
        <button class="btn btn-primary">Générer PDF</button>
      </div>
    </div>

    <div class="grid">

      <!-- FORM -->
      <div class="card">
        <h2>Nouveau calcul</h2>

        <form id="calculatorForm" class="calculator">

  <div class="form-grid">

    <!-- Tarif origine -->
    <div class="input-group">
      <label>Tarif d'origine (€)</label>
      <input name="Tarif" type="number" step="0.01" required>
    </div>

    <!-- Part ferme -->
    <div class="input-group">
      <label>Part ferme (%)</label>
      <input type="number" step="0.01" id="part_ferme" name="part_ferme" required>
      <small style="color:#64748b;">
        Exemple : 15 = 15% / 0 = révision intégrale
      </small>
    </div>

    <!-- Libellé INSEE -->
    <div class="input-group">
      <label>Libellé INSEE</label>
      <select id="selectLibelle" name="libelle" required>
        <option value="">Chargement...</option>
      </select>
    </div>

    <!-- Date 0 -->
    <div class="input-group">
      <label>Mois 0</label>
      <input id="date0" name="date0" type="month" required>
    </div>

    <!-- Date N -->
    <div class="input-group">
      <label>Mois N</label>
      <input id="dateN" name="dateN" type="month" required>
    </div>

    <!-- Indice 0 -->
    <div class="input-group">
      <label>Indice Mois 0</label>
      <input id="indice0" name="ICHT-N0" type="number" step="0.01" readonly required>
    </div>

    <!-- Indice N -->
    <div class="input-group">
      <label>Indice Mois N</label>
      <input id="indiceN" name="ICHT-Nn" type="number" step="0.01" readonly required>
    </div>

    <!-- Commentaires (fusion SaaS dashboard) -->
    <div class="input-group full">
      <label>Commentaires</label>
      <input type="text" name="commentaires" placeholder="Observations complémentaires">
    </div>

  </div>

  <button type="submit" class="btn btn-primary" style="margin-top:1.5rem;">
    Lancer le calcul
  </button>

</form>
      </div>

      <!-- RESULT -->
        <div class="result-card">

            <div class="result-main">
                <p>Montant révisé</p>
                <h2 id="resultAmount">-- €</h2>
                <div class="variation" id="resultVariation">--%</div>
            </div>
        
            <div class="stats">
            
                <div class="stat">
                    <p>Coefficient de révision</p>
                    <h3 id="resultCn">--</h3>
                </div>
                
                <div class="stat">
                    <p>Variation financière</p>
                    <h3 id="resultDiff">-- €</h3>
                </div>
                
                <div class="stat">
                    <p>Indice utilisé</p>
                    <h3 id="resultIndice">--</h3>
                </div>
                
                <div class="stat">
                    <p>Statut</p>
                    <h3 id="resultStatus">--</h3>
                </div>
                
                <div class="error" id="errorMessage"></div>
        
            </div>
    
        </div>

    </div>

    <!-- HISTORY--> 
    <div class="history">

      <div class="history-header">
        <!--<h2>Historique des calculs</h2>-->
        <a href="<?= $router->generate('Calcul-index') ?>" class="btn btn-secondary">Voir tous les calculs</button>
      </div>

      <!--<table>
        <thead>
          <tr>
            <th>Chantier</th>
            <th>Lot</th>
            <th>Indice</th>
            <th>Variation</th>
            <th>Montant</th>
            <th>Date</th>
            <th>Statut</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>Résidence Horizon</td>
            <td>Gros œuvre</td>
            <td>BT01</td>
            <td>+7.55%</td>
            <td>91 420€</td>
            <td>12/05/2026</td>
            <td><span class="badge badge-success">Validé</span></td>
          </tr>

          <tr>
            <td>Centre Hospitalier</td>
            <td>Terrassement</td>
            <td>TP01</td>
            <td>+3.20%</td>
            <td>128 900€</td>
            <td>08/05/2026</td>
            <td><span class="badge badge-warning">En attente</span></td>
          </tr>
        </tbody>

      </table>-->

    </div>

  </main>
<script src="<?= $_SERVER['BASE_URI']?>/assets/js/date.js"></script>
<script src="<?= $_SERVER['BASE_URI']?>/assets/js/calcul.js"></script>
