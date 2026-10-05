<!-- /views/chantiers/create.php -->

    <div>
        <h1>Création de nouveau chantier</h1>
        <p>Automatisez et sécurisez vos révisions tarifaires.</p>
      </div>

      <div class="topbar-actions">
        <a href="<?= $router->generate('Chantier-index') ?>" class="btn btn-primary">Voir les chantiers</a>
      </div>
    </div>
<div class="grid">
    <div class="card">
        <h2>Créer un chantier</h2>
        <form method="POST" action="" id="calculatorForm" class="calculator">
            <?php
            // On inclut la sous-vue/partial form_errors.tpl.php
            include __DIR__ . '/../partials/form_errors.tpl.php';
            ?>
            <div class="input-group">
                <label for="nom">Nom du chantier</label>
                <input type="text" name="nom" id="nom" required>
            </div>
            
            <div class="input-group">
                <label for="client">Nom du client</label>
                <input type="text" name="client" id="client" required>
            </div>
          
            <div class="input-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4"></textarea>
            </div>
            
            <div class="input-group">
                <label for="description">Statut</label>
                <select name="status" id="status">
                    <option value="active">En cours</option>
                    <option value="archived">Terminé</option>
                </select>
            </div>
          
        
          <button type="submit" class="btn btn-primary">Créer le chantier</button>
        
        </form>
    </div>
</div>