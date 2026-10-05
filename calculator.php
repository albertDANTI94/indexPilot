<?php require './includes/header.php'; ?> 
<main> 
    <div class="connect"> 
        <p>Vous êtes connecté en tant que <?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?></p> 
    </div> 
    <div class="contentBox"> 
        <h3>VOTRE CALCULATEUR</h3> 
        <p> Renseignez les champs ci-dessous pour calculer la réévaluation de vos tarifs. 
        Pour trouver vos indices : 
            <a href="https://www.insee.fr/fr/statistiques/series/103173847" target="_blank">Indexes INSEE</a> 
        </p> 
        
        <form id="calculatorForm" class="calculator"> 
            <div class="formFlexer"> 
                <label>Tarif d'origine (€)</label> 
                <input name="Tarif" type="number" step="0.01" required> 
            </div> 
            <div class="formFlexer"> 
                <label for="part_ferme">La formule de révision contient-elle une part ferme ?</label> 
                <div class="verticalFlexer"> 
                    <input type="number" step="0.01" id="part_ferme" name="part_ferme" required> 
                    <p>aide : <span>si la formule est Cn = 0.15 + 0.85 (In/Io) saisir 15%, si il y a révision intégrale saisir 0</span></p> 
                </div> 
            </div> 
            <div class="formFlexer"> 
                <label>Libellé INSEE</label> 
                <select id="selectLibelle" name="libelle" required> 
                    <option value="">Chargement...</option> 
                </select> 
            </div> 
            <div class="formFlexer"> 
                <label>Mois 0</label> 
                <input id="date0" name="date0" type="month" required> 
            </div> 
            <div class="formFlexer"> 
                <label>Mois N</label> 
                <input id="dateN" name="dateN" type="month" required> 
            </div> 
            <div class="formFlexer"> 
                <label>Indice Mois 0</label> 
                <input id="indice0" name="ICHT-N0" type="number" step="0.01" readonly required> 
            </div> 
            <div class="formFlexer"> 
                <label>Indice Mois N</label> 
                <input id="indiceN" name="ICHT-Nn" type="number" step="0.01" readonly required> 
            </div> 
            
            <button type="submit">Calculer</button> 
        </form> 
        
        <div id="resultContainer" class="resultContainer" style="display:none;"> 
            <h3>Résultat</h3> 
            <p id="resultText"></p> 
        </div> 
        <div class="error" id="errorMessage"></div> 
    </div> 
</main> 

<script src="./assets/js/date.js"></script> <script src="./assets/js/calcul.js"></script> 
    </body> 
</html>