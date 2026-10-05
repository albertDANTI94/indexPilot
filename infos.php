<?php require './includes/header.php'; ?>

<main>
    <div class="infoContainer">
        <h3>MES infos</h3>
        <p>Nom</p>
        <p>email</p>
        <p>entreprise</p>
        <p>index INSEE</p>
        <button>MODIFIER</button>
    </div>

    <form action="" method="post">
        <div class="formFlexer">
            <label>Nom</label>
            <input name="name" type="text" step="0.01" required>
        </div>
        <div class="formFlexer">
            <label>email</label>
            <input name="email" type="mail" step="0.01" required>
        </div>
        <div class="formFlexer">
            <label>Entreprise</label>
            <input name="name" type="text" step="0.01" required>
        </div>
        <div class="formFlexer">
            <label>index INSEE</label>
            <input name="index" type="text" step="0.01" required>
        </div>
        <button type="submit">ENREGISTRER</button>
    </form>

</main>