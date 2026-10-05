<!-- /views/chantiers/create.php -->

    <div>
        <h1>Mise à jour du chantier : <?= $chantier->getNom() ?></h1>
        <p>Automatisez et sécurisez vos révisions tarifaires.</p>
      </div>

      <div class="topbar-actions">
        <button href="<?= $router->generate('Chantier-index') ?>" class="btn btn-primary">Voir les chantiers</button>
      </div>
    </div>
<div class="grid">
    <div class="card">
        <h2>Modifier le chantier <?= $chantier->getNom() ?></h2>
        <form method="POST" action="" id="calculatorForm" class="calculator">
            <?php
            // On inclut la sous-vue/partial form_errors.tpl.php
            include __DIR__ . '/../partials/form_errors.tpl.php';
            ?>
            <div class="input-group">
                <label for="nom">Nom du chantier</label>
                <input type="text" name="nom" id="nom" value="<?= $chantier->getNom() ?>" required>
            </div>
            
            <div class="input-group">
                <label for="client">Nom du client</label>
                <input type="text" name="client" id="client" value="<?= $chantier->getClient() ?>" required>
            </div>
          
            <div class="input-group">
                <label for="description">Description</label>
                <textarea name="description" id="description"  rows="4"><?= $chantier->getDescription() ?></textarea>
            </div>
            
            <div class="input-group">
                <label for="description">Statut</label>
                <select name="status" id="status">
                    <option value="active" <?= ($chantier->getStatus() == "active") ? "selected" : "" ?>>En cours</option>
                    <option value="archived" <?= ($chantier->getStatus() == "archived") ? "selected" : "" ?>>Terminé</option>
                </select>
            </div>
          
        
          <button type="submit" class="btn btn-primary">Modifier le chantier</button>
        
        </form>
    </div>
</div>