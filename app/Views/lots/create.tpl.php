<!-- /views/chantiers/create.php -->

    <div>
        <h1>Création de nouveau lot</h1>
        <p>Automatisez et sécurisez vos révisions tarifaires.</p>
      </div>

      <div class="topbar-actions">
        <a href="<?= $router->generate('Lot-index') ?>" class="btn btn-primary">Voir les lots</a>
      </div>
    </div>
<div class="grid">
    <!-- FORM -->
        <div class="card">
            <h2>Nouveau lot</h2>

            <form id="calculatorForm" class="calculator">

            <div class="form-grid">
                
                <div class="input-group full">
                  <label>Nom</label>
                  <input type="text" name="nom" placeholder="Nom du lot">
                </div>
                
                <div class="input-group full">
                    <label>Chantier</label>
                    <select id="chantier_id" name="chantier_id" required>
                        <?php foreach($chantiers as $chantier): ?>
                            <option value="<?= $chantier->getId() ?>">
                                <?= htmlspecialchars($chantier->getNom()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tarif origine -->
                <div class="input-group">
                  <label>Tarif d'origine (€)</label>
                  <input name="tarif_origine" type="number" step="0.01" required>
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
                  <input id="indice0" name="indice0" type="number" step="0.01" readonly required>
                </div>
            
                <!-- Indice N -->
                <div class="input-group">
                  <label>Indice Mois N</label>
                  <input id="indiceN" name="indiceN" type="number" step="0.01" readonly required>
                </div>
                
                <div class="input-group">
                    <label for="description">Statut</label>
                    <select name="status" id="status">
                        <option value="draft">Brouillon</option>
                        <option value="calculated">Validé</option>
                        <option value="validated">Terminé</option>
                    </select>
                </div>
                
                <div class="input-group full">
                    <label for="subscribeIndice">S’abonner à cet indice</label>
                    <input type="checkbox" id="subscribeIndice" name="subscribeIndice">
                </div>

                <!-- Commentaires (fusion SaaS dashboard) -->
                <div class="input-group full">
                  <label>Commentaires</label>
                  <input type="text" name="commentaires" placeholder="Observations complémentaires">
                </div>

            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:1.5rem;">
                Créer le lot
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

<script src="<?= $_SERVER['BASE_URI']?>/assets/js/date.js"></script>
<script src="<?= $_SERVER['BASE_URI']?>/assets/js/lot.js"></script>