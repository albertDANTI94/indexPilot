<!-- s'il a des erreurs, on les affiche ! -->
<?php if(isset($errors)) : ?>
    <div class="alert alert-danger mt-2">
        <h4>Erreurs :</h4>
        <ul>
            <?php foreach($errors as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>