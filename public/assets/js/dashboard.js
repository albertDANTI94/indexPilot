document.addEventListener('DOMContentLoaded', () => {
    const addChantierBtn = document.getElementById('addChantierBtn');
    const chantierFormContainer = document.getElementById('chantierFormContainer');
    const chantierForm = document.getElementById('chantierForm');
    const lotFormContainer = document.getElementById('lotFormContainer');
    const lotForm = document.getElementById('lotForm');
    const chantierContainer = document.getElementById('chantierContainer');

    // Afficher / masquer le formulaire de chantier
    addChantierBtn.addEventListener('click', () => {
        chantierFormContainer.style.display = chantierFormContainer.style.display === 'none' ? 'block' : 'none';
    });

    // Création d'un chantier
    chantierForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(chantierForm);

        const response = await fetch('./functions/create_chantier.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            chantierForm.reset();
            chantierFormContainer.style.display = 'none';
            loadChantiers(); // recharge la liste
        } else {
            alert('Erreur lors de la création du chantier');
        }
    });

    // Création d'un lot
    lotForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(lotForm);

        const response = await fetch('./functions/create_lot.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            lotForm.reset();
            lotFormContainer.style.display = 'none';
            loadChantiers();
        } else {
            alert('Erreur lors de l\'ajout du lot');
        }
    });

    // Charger les chantiers existants
    async function loadChantiers() {
        const response = await fetch('./functions/get_chantiers.php');
        const data = await response.json();

        chantierContainer.innerHTML = '';

        data.forEach(chantier => {
            const chantierCard = document.createElement('div');
            chantierCard.classList.add('chantierCard');
            chantierCard.innerHTML = `
                <h3>${chantier.nom}</h3>
                <button class="addLotBtn" data-id="${chantier.id}">Ajouter un lot</button>
                ${chantier.lots.map(lot => `
                    <div class="LotContainer">
                        <h4>${lot.nom}</h4>
                        <p>Prix initial : ${lot.tarif_origine} €</p>
                        <p>Coefficient : ${lot.coefficient}</p>
                        <p>Nouveau prix : ${lot.nouveau_tarif} €</p>
                    </div>
                `).join('')}
            `;
            chantierContainer.appendChild(chantierCard);
        });

        // Boutons "Ajouter un lot"
        document.querySelectorAll('.addLotBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('lotChantierId').value = btn.dataset.id;
                lotFormContainer.style.display = 'block';
            });
        });
    }

    loadChantiers();
});
