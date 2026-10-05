/*document.addEventListener('DOMContentLoaded', () => {
    const chantiersContainer = document.getElementById('chantiersContainer');

    // --- MODALES ---
    const createChantierModal = document.getElementById('createChantierModal');
    const openCreateChantierBtn = document.getElementById('openCreateChantierModal');
    const closeCreateChantierBtn = document.getElementById('closeCreateChantierModal');
    const createChantierForm = document.getElementById('createChantierForm');

    const lotModal = document.getElementById('lotModal');
    const lotForm = document.getElementById('lotForm');
    const closeLotModalBtn = document.getElementById('closeLotModal');
    const lotChantierIdInput = document.getElementById('lotChantierId');

    // éléments de la modale lot (peuvent être null si DOM différent)
    const selectLibelle = document.getElementById('selectLibelle');
    const date0Input = document.getElementById('date0');
    const dateNInput = document.getElementById('dateN');
    const indice0Input = document.getElementById('indice0');
    const indiceNInput = document.getElementById('indiceN');

    // Défauts si présents
    const DEFAULT_MIN_MONTH = '2010-01';
    const DEFAULT_MAX_MONTH = '2025-11';

    // Set min/max pour les inputs month si existants
    if (date0Input) date0Input.setAttribute('min', DEFAULT_MIN_MONTH), date0Input.setAttribute('max', DEFAULT_MAX_MONTH);
    if (dateNInput) dateNInput.setAttribute('min', DEFAULT_MIN_MONTH), dateNInput.setAttribute('max', DEFAULT_MAX_MONTH);

    // --- OUVRIR / FERMER MODALE CHANTIER ---
    if (openCreateChantierBtn && createChantierModal) openCreateChantierBtn.addEventListener('click', () => createChantierModal.style.display = 'flex');
    if (closeCreateChantierBtn && createChantierModal) closeCreateChantierBtn.addEventListener('click', () => createChantierModal.style.display = 'none');

    // --- OUVRIR / FERMER MODALE LOT ---
    if (closeLotModalBtn && lotModal) closeLotModalBtn.addEventListener('click', () => lotModal.style.display = 'none');

    // --- CREATION CHANTIER ---
    if (createChantierForm) {
        createChantierForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(createChantierForm);
            try {
                const resp = await fetch('./functions/create_chantier.php', { method: 'POST', body: formData });
                const data = await resp.json();
                if (data.success) {
                    alert('✅ Chantier créé !');
                    createChantierForm.reset();
                    if (createChantierModal) createChantierModal.style.display = 'none';
                    await loadChantiers();
                } else {
                    alert('❌ ' + (data.message || 'Erreur création chantier'));
                }
            } catch (err) {
                console.error(err);
                alert('Erreur serveur lors de la création du chantier');
            }
        });
    }

    // --- SUPPRESSION CHANTIER ---
    if (chantiersContainer) {
        chantiersContainer.addEventListener('click', async (e) => {
            if (e.target.classList.contains('delete-chantier')) {
                const chantierId = e.target.dataset.id;
                if (!confirm('Voulez-vous vraiment supprimer ce chantier ?')) return;
                try {
                    const resp = await fetch('./functions/delete_chantier.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: chantierId })
                    });
                    const data = await resp.json();
                    if (data.success) {
                        alert('✅ Chantier supprimé !');
                        await loadChantiers();
                    } else {
                        alert('❌ ' + (data.message || 'Erreur suppression chantier'));
                    }
                } catch (err) {
                    console.error(err);
                    alert('Erreur serveur lors de la suppression du chantier');
                }
            }
        });
    }

    // --- OUVRIR MODALE LOT : on charge les libellés ici ---
    if (chantiersContainer) {
        chantiersContainer.addEventListener('click', async (e) => {
            if (e.target.classList.contains('add-lot-btn')) {
                const chantierId = e.target.dataset.id;
                if (!lotChantierIdInput) return console.error('lotChantierIdInput introuvable');
                lotChantierIdInput.value = chantierId;

                // Clear modale fields
                if (lotForm) lotForm.reset();
                if (indice0Input) indice0Input.value = '';
                if (indiceNInput) indiceNInput.value = '';

                // Load libelles into select
                if (selectLibelle) {
                    selectLibelle.innerHTML = '<option value="">Chargement...</option>';
                    try {
                        const resp = await fetch('./functions/get_libelles.php', {cache: "no-store"});
                        if (!resp.ok) throw new Error('HTTP ' + resp.status);
                        const json = await resp.json();
                        // json can be array of strings or array of objects
                        selectLibelle.innerHTML = '<option value="">-- Choisir --</option>';
                        if (Array.isArray(json)) {
                            json.forEach(item => {
                                const val = (typeof item === 'string') ? item : (item.libelle ?? item);
                                const opt = document.createElement('option');
                                opt.value = val;
                                opt.textContent = val;
                                selectLibelle.appendChild(opt);
                            });
                        } else {
                            console.warn('get_libelles.php returned not-array', json);
                            selectLibelle.innerHTML = '<option value="">Erreur chargement</option>';
                        }
                    } catch (err) {
                        console.error('Erreur chargement libellés:', err);
                        selectLibelle.innerHTML = '<option value="">Erreur chargement</option>';
                    }
                }

                if (lotModal) lotModal.style.display = 'block';
            }
        });
    }

    // --- updateIndices : récupère indice0 et indiceN via functions/get_indice.php ---
    async function updateIndices() {
        if (!selectLibelle || !date0Input || !dateNInput || !indice0Input || !indiceNInput) return;
        const libelle = selectLibelle.value;
        const d0 = date0Input.value;
        const dN = dateNInput.value;

        // validate
        if (!libelle || !d0 || !dN) return;

        try {
            // fetch indice0
            const r0 = await fetch(`./functions/get_indice.php?libelle=${encodeURIComponent(libelle)}&date=${encodeURIComponent(d0)}`, {cache: "no-store"});
            const j0 = await r0.json();
            if (j0.success) {
                indice0Input.value = j0.indice;
            } else {
                indice0Input.value = '';
                console.warn('indice0 non trouvé:', j0.message || j0);
            }
            
            
            
            // fetch indiceN
            const rN = await fetch(`./functions/get_indice.php?libelle=${encodeURIComponent(libelle)}&date=${encodeURIComponent(dN)}`, {cache: "no-store"});
            const jN = await rN.json();
            if (jN.success) {
                indiceNInput.value = jN.indice;
            } else {
                indiceNInput.value = '';
                console.warn('indiceN non trouvé:', jN.message || jN);
            }
            
            
        } catch (err) {
            console.error('Erreur récupération indices:', err);
        }
    }

    // Attacher listeners (si éléments existants)
    if (selectLibelle) selectLibelle.addEventListener('change', updateIndices);
    // --- ABONNEMENT INDICE ---
    const subscribeCheckbox = document.getElementById('subscribeIndice');
    
    async function handleSubscription(idbank) {
    
        const formData = new FormData();
        formData.append('idbank', idbank);
    
        const resp = await fetch('./functions/toggle_subscription.php', {
            method: 'POST',
            body: formData
        });
    
        const data = await resp.json();
    
        if (!data.success) {
            alert('❌ Erreur abonnement');
            return;
        }
    
        if (data.subscribed) {
            console.log('🔔 Abonné');
        } else {
            console.log('🔕 Désabonné');
        }
    }

    // Listener checkbox
    if (subscribeCheckbox) {
        subscribeCheckbox.addEventListener('change', async () => {
    
            if (!selectLibelle || !selectLibelle.value) {
                alert("Veuillez sélectionner un libellé");
                subscribeCheckbox.checked = false;
                return;
            }
    
            const idbank = selectLibelle.value;
    
            try {
                const resp = await fetch('./functions/toggle_subscription.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        idbank: idbank
                    })
                });
                
                console.log("IDBANK ENVOYÉ:", selectLibelle.value);
    
                const text = await resp.text();
    
                if (!text) {
                    throw new Error("Réponse vide");
                }
    
                const data = JSON.parse(text);
    
                if (!data.success) {
                    alert(data.message || "Erreur abonnement");
                    subscribeCheckbox.checked = false;
                    return;
                }
    
                console.log("Abonnement OK:", data);
    
            } catch (err) {
                console.error("Erreur abonnement:", err);
                alert("Erreur serveur");
                subscribeCheckbox.checked = false;
            }
        });
    }
    if (date0Input) date0Input.addEventListener('change', updateIndices);
    if (dateNInput) dateNInput.addEventListener('change', updateIndices);

    // --- CREATION LOT (submit) ---
    if (lotForm) {
        lotForm.addEventListener('submit', async (e) => {
            e.preventDefault();
    
            if (!lotChantierIdInput || !lotChantierIdInput.value) {
                alert('❌ Le chantier est introuvable');
                return;
            }
    
            // vérification indices
            if (indice0Input && indice0Input.value === '') {
                alert('Veuillez sélectionner un libellé et des dates valides (indice 0 manquant).');
                return;
            }
    
            if (indiceNInput && indiceNInput.value === '') {
                alert('Veuillez sélectionner un libellé et des dates valides (indice N manquant).');
                return;
            }
    
            const formData = new FormData(lotForm);
    
            if (!formData.has('chantier_id')) {
                formData.append('chantier_id', lotChantierIdInput.value);
            }
    
            try {
                // 🔹 1. création du lot
                const resp = await fetch('./functions/create_lot.php', {
                    method: 'POST',
                    body: formData
                });
    
                const data = await resp.json();
    
                if (!data.success) {
                    alert('❌ ' + (data.message || 'Erreur ajout lot'));
                    return;
                }
    
                // 🔹 2. abonnement si checkbox cochée
                const subscribeCheckbox = document.getElementById('subscribeIndice');
    
                if (subscribeCheckbox && subscribeCheckbox.checked) {
    
                    try {
                        const respSub = await fetch('./functions/toggle_subscription.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                            idbank: selectLibelle.value
                        })
                        });
                        
                        const raw = await respSub.text();
                        console.log("RAW RESPONSE:", raw);
    
                        let subData = null;

                        try {
                            const text = await respSub.text();
                        
                            if (text) {
                                subData = JSON.parse(text);
                            } else {
                                throw new Error('Réponse vide');
                            }
                        
                        } catch (e) {
                            console.error('Réponse invalide abonnement:', e);
                            console.error('Contenu brut:', await respSub.text());
                        }
    
                        if (!subData.success) {
                            console.warn('Erreur abonnement:', subData.message);
                        }
    
                    } catch (e) {
                        console.error('Erreur abonnement:', e);
                    }
                }
    
                // 🔹 3. succès
                alert(
                    '✅ Lot ajouté !\n' +
                    'Coefficient : ' + data.coefficient + '\n' +
                    'Nouveau tarif : ' + data.nouveau_tarif + ' €'
                );
    
                lotForm.reset();
    
                if (lotModal) {
                    lotModal.style.display = 'none';
                }
    
                await loadChantiers();
    
            } catch (err) {
                console.error('Erreur création lot:', err);
                alert('Erreur serveur lors de la création du lot');
            }
        });
    }

    // --- SUPPRESSION LOT handled earlier in code: left unchanged ---
    chantiersContainer.addEventListener('click', async (e) => {
        if (e.target.classList.contains('delete-lot-btn')) {
            const lotId = e.target.dataset.id;
            if (!confirm('Voulez-vous vraiment supprimer ce lot ?')) return;

            try {
                const resp = await fetch('./functions/delete_lot.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: lotId })
                });
                const data = await resp.json();

                if (data.success) {
                    alert('✅ Lot supprimé !');
                    await loadChantiers();
                } else {
                    alert('❌ ' + (data.message || 'Erreur suppression lot'));
                }
            } catch (err) {
                console.error(err);
                alert('Erreur serveur lors de la suppression du lot');
            }
        }
    });
    
    // --- CHARGEMENT DES CHANTIERS + LOTS ---
    async function loadChantiers() {
        try {
            const resp = await fetch('./functions/get_chantiers.php', {cache: "no-store"});
            const chantiers = await resp.json();
            chantiersContainer.innerHTML = '';
            if (!Array.isArray(chantiers) || chantiers.length === 0) {
                chantiersContainer.innerHTML = '<p>Aucun chantier pour le moment.</p>';
                return;
            }
            for (const chantier of chantiers) {
                const card = document.createElement('div');
                card.className = 'chantier-card';
                card.dataset.id = chantier.id;
                let lotsHTML = '';
                try {
                    const lotsResp = await fetch(`./functions/get_lots.php?chantier_id=${chantier.id}`, {cache: "no-store"});
                    const lots = await lotsResp.json();
                    if (Array.isArray(lots) && lots.length > 0) {
                        lots.forEach(lot => {
                            lotsHTML += `
                                <div class="lot">
                                    <strong>${escapeHtml(lot.nom)}</strong><br>
                                    Ancien tarif : ${escapeHtml(lot.tarif_origine)} €<br>
                                    Indice 0 : ${escapeHtml(lot.indice0)}<br>
                                    Indice N : ${escapeHtml(lot.indiceN)}<br>
                                    <strong>Coefficient : ${parseFloat(lot.coefficient).toFixed(3)}</strong><br>
                                    <strong>Nouveau tarif : ${parseFloat(lot.nouveau_tarif).toFixed(2)} €</strong>
                                    <button class="delete-lot-btn" data-id="${lot.id}">🗑</button>
                                </div>
                            `;
                        });
                    } else {
                        lotsHTML = '<p>Aucun lot pour ce chantier.</p>';
                    }
                } catch (err) {
                    lotsHTML = '<p>Impossible de charger les lots.</p>';
                    console.error(err);
                }
                card.innerHTML = `
                    <h3>${escapeHtml(chantier.nom)}</h3>
                    <button class="delete-chantier" data-id="${chantier.id}">Supprimer</button>
                    <div class="lots">${lotsHTML}</div>
                    <button class="add-lot-btn" data-id="${chantier.id}">+ Ajouter un lot</button>
                `;
                chantiersContainer.appendChild(card);
            }
        } catch (err) {
            console.error('Erreur chargement chantiers:', err);
            chantiersContainer.innerHTML = '<p>Impossible de charger les chantiers.</p>';
        }
    }

    // util
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // INIT
    loadChantiers();
});*/
window.addEventListener('error', e => {
    console.error('GLOBAL ERROR:', e.message);
});

document.addEventListener('DOMContentLoaded', () => {

    const chantiersContainer = document.getElementById('chantiersContainer');

    // --- MODALES ---
    const createChantierModal = document.getElementById('createChantierModal');
    const openCreateChantierBtn = document.getElementById('openCreateChantierModal');
    const closeCreateChantierBtn = document.getElementById('closeCreateChantierModal');
    const createChantierForm = document.getElementById('createChantierForm');

    const lotModal = document.getElementById('lotModal');
    const lotForm = document.getElementById('lotForm');
    const closeLotModalBtn = document.getElementById('closeLotModal');
    const lotChantierIdInput = document.getElementById('lotChantierId');

    const selectLibelle = document.getElementById('selectLibelle');
    const date0Input = document.getElementById('date0');
    const dateNInput = document.getElementById('dateN');
    const indice0Input = document.getElementById('indice0');
    const indiceNInput = document.getElementById('indiceN');

    const subscribeCheckbox = document.getElementById('subscribeIndice');

    // ✅ IDBANK GLOBAL
    let currentIdbank = null;

    //const DEFAULT_MIN_MONTH = '2010-01';
    //const DEFAULT_MAX_MONTH = '2025-11';
    
    //await loadDateLimits();

    //if (date0Input) date0Input.setAttribute('min', DEFAULT_MIN_MONTH), date0Input.setAttribute('max', DEFAULT_MAX_MONTH);
    //if (dateNInput) dateNInput.setAttribute('min', DEFAULT_MIN_MONTH), dateNInput.setAttribute('max', DEFAULT_MAX_MONTH);
    
    //let DEFAULT_MIN_MONTH = '2010-01';
    //let DEFAULT_MAX_MONTH = '2025-11';

    async function initDateLimits() {
        await loadDateLimits();
        
        console.log("DATE MAX APPLIQUÉE:", DEFAULT_MAX_MONTH);
    
        if (date0Input) {
            date0Input.min = DEFAULT_MIN_MONTH;
            date0Input.max = DEFAULT_MAX_MONTH;
        }
    
        if (dateNInput) {
            dateNInput.min = DEFAULT_MIN_MONTH;
            dateNInput.max = DEFAULT_MAX_MONTH;
        }
    }

    // --- MODALES ---
    if (openCreateChantierBtn) openCreateChantierBtn.onclick = () => createChantierModal.style.display = 'flex';
    if (closeCreateChantierBtn) closeCreateChantierBtn.onclick = () => createChantierModal.style.display = 'none';
    if (closeLotModalBtn) closeLotModalBtn.onclick = () => lotModal.style.display = 'none';

    // --- CREATION CHANTIER ---
    if (createChantierForm) {
        createChantierForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            try {
                const resp = await fetch('./functions/create_chantier.php', {
                    method: 'POST',
                    body: new FormData(createChantierForm)
                });

                const data = await resp.json();

                if (!data.success) {
                    alert(data.message || 'Erreur création chantier');
                    return;
                }

                alert('✅ Chantier créé');
                createChantierForm.reset();
                createChantierModal.style.display = 'none';
                await loadChantiers();

            } catch (err) {
                console.error(err);
                alert('Erreur serveur');
            }
        });
    }

    // --- OUVERTURE MODALE LOT ---
    chantiersContainer.addEventListener('click', async (e) => {
        if (!e.target.classList.contains('add-lot-btn')) return;

        lotChantierIdInput.value = e.target.dataset.id;
        lotForm.reset();

        currentIdbank = null;
        if (subscribeCheckbox) subscribeCheckbox.checked = false;

        // charger libellés
        selectLibelle.innerHTML = '<option>Chargement...</option>';

        try {
            const resp = await fetch('./functions/get_libelles.php');
            const data = await resp.json();

            selectLibelle.innerHTML = '<option value="">-- Choisir --</option>';

            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.idbank;       // ✅ idbank direct
                opt.textContent = item.libelle;
                selectLibelle.appendChild(opt);
            });

        } catch (err) {
            console.error(err);
            selectLibelle.innerHTML = '<option>Erreur</option>';
        }
        
        //console.log("DATA LIBELLES:", data);

        lotModal.style.display = 'block';
    });

    // --- RESET IDBANK ---
    if (selectLibelle) {
        selectLibelle.addEventListener('change', () => {
            currentIdbank = null;
        });
    }

    // --- UPDATE INDICES ---
    /*async function updateIndices() {

        if (!selectLibelle.value || !date0Input.value || !dateNInput.value) return;

        try {
            const r0 = await fetch(`./functions/get_indice.php?libelle=${encodeURIComponent(selectLibelle.value)}&date=${date0Input.value}`);
            const j0 = await r0.json();

            indice0Input.value = j0.success ? j0.indice : '';

            const rN = await fetch(`./functions/get_indice.php?libelle=${encodeURIComponent(selectLibelle.value)}&date=${dateNInput.value}`);
            const jN = await rN.json();

            indiceNInput.value = jN.success ? jN.indice : '';

        } catch (err) {
            console.error(err);
        }
    }*/
    
    // --- updateIndices : récupère indice0 et indiceN via functions/get_indice.php ---
    async function updateIndices() {

        if (!selectLibelle || !date0Input || !dateNInput || !indice0Input || !indiceNInput) return;
    
        const idbank = selectLibelle.value;
        const d0 = date0Input.value;
        const dN = dateNInput.value;
    
        if (!idbank || !d0 || !dN) return;
    
        try {
            // 🔹 indice 0
            const r0 = await fetch(`./functions/get_indice.php?idbank=${encodeURIComponent(idbank)}&date=${encodeURIComponent(d0)}`, { cache: "no-store" });
            const j0 = await r0.json();
    
            if (j0.success) {
                indice0Input.value = j0.valeur;
            } else {
                indice0Input.value = '';
                console.warn('indice0 non trouvé:', j0.message);
            }
    
            // 🔹 indice N
            const rN = await fetch(`./functions/get_indice.php?idbank=${encodeURIComponent(idbank)}&date=${encodeURIComponent(dN)}`, { cache: "no-store" });
            const jN = await rN.json();
    
            if (jN.success) {
                indiceNInput.value = jN.valeur;
            } else {
                indiceNInput.value = '';
                console.warn('indiceN non trouvé:', jN.message);
            }
    
        } catch (err) {
            console.error('Erreur récupération indices:', err);
        }
    }

    if (selectLibelle) selectLibelle.addEventListener('change', updateIndices);
    if (date0Input) date0Input.addEventListener('change', updateIndices);
    if (dateNInput) dateNInput.addEventListener('change', updateIndices);
    
    //console.log("IDBANK envoyé:", idbank);

    // --- ABONNEMENT ---
    if (subscribeCheckbox) {
        subscribeCheckbox.addEventListener('change', async () => {
    
            const idbank = selectLibelle.value;
    
            if (!idbank) {
                alert("Veuillez sélectionner un indice");
                subscribeCheckbox.checked = false;
                return;
            }
    
            try {
                const resp = await fetch('./functions/toggle_subscription.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ idbank })
                });
    
                const data = await resp.json();
    
                if (!data.success) {
                    alert(data.message || "Erreur abonnement");
                    subscribeCheckbox.checked = false;
                    return;
                }
    
                console.log("Abonnement OK:", data);
    
            } catch (err) {
                console.error(err);
                alert("Erreur serveur");
                subscribeCheckbox.checked = false;
            }
        });
    }

    // --- CREATION LOT ---
    if (lotForm) {
        lotForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!indice0Input.value || !indiceNInput.value) {
                alert('Indices manquants');
                return;
            }

            const formData = new FormData(lotForm);
            formData.append('chantier_id', lotChantierIdInput.value);

            try {
                const resp = await fetch('./functions/create_lot.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await resp.json();

                if (!data.success) {
                    alert(data.message);
                    return;
                }

                // abonnement après création
                if (subscribeCheckbox.checked) {
                        
                        await fetch('./functions/toggle_subscription.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            idbank: selectLibelle.value // ✅ direct
                        })
                    });
                }

                alert(`✅ Lot ajouté\nCoef: ${data.coefficient}`);
                lotModal.style.display = 'none';
                await loadChantiers();

            } catch (err) {
                console.error(err);
                alert('Erreur serveur');
            }
        });
    }
    
    // --- SUPPRESSION LOT handled earlier in code: left unchanged ---
    chantiersContainer.addEventListener('click', async (e) => {
        if (e.target.classList.contains('delete-lot-btn')) {
            const lotId = e.target.dataset.id;
            if (!confirm('Voulez-vous vraiment supprimer ce lot ?')) return;

            try {
                const resp = await fetch('./functions/delete_lot.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: lotId })
                });
                const data = await resp.json();

                if (data.success) {
                    alert('✅ Lot supprimé !');
                    await loadChantiers();
                } else {
                    alert('❌ ' + (data.message || 'Erreur suppression lot'));
                }
            } catch (err) {
                console.error(err);
                alert('Erreur serveur lors de la suppression du lot');
            }
        }
    });

    // --- LOAD CHANTIERS ---
    // --- CHARGEMENT DES CHANTIERS + LOTS ---
    async function loadChantiers() {
        try {
            const resp = await fetch('./functions/get_chantiers.php', {cache: "no-store"});
            const chantiers = await resp.json();
            chantiersContainer.innerHTML = '';
            if (!Array.isArray(chantiers) || chantiers.length === 0) {
                chantiersContainer.innerHTML = '<p>Aucun chantier pour le moment.</p>';
                return;
            }
            for (const chantier of chantiers) {
                const card = document.createElement('div');
                card.className = 'chantier-card';
                card.dataset.id = chantier.id;
                let lotsHTML = '';
                try {
                    const lotsResp = await fetch(`./functions/get_lots.php?chantier_id=${chantier.id}`, {cache: "no-store"});
                    const lots = await lotsResp.json();
                    if (Array.isArray(lots) && lots.length > 0) {
                        lots.forEach(lot => {
                            lotsHTML += `
                                <div class="lot">
                                    <strong>${escapeHtml(lot.nom)}</strong><br>
                                    Montant acompte mensuel (M0) : ${escapeHtml(lot.tarif_origine)} €<br>
                                    Indice 0 : ${escapeHtml(lot.indice0)}<br>
                                    Indice N : ${escapeHtml(lot.indiceN)}<br>
                                    <strong>Coefficient : ${parseFloat(lot.coefficient).toFixed(3)}</strong><br>
                                    <strong>Nouveau tarif : ${parseFloat(lot.nouveau_tarif).toFixed(2)} €</strong>
                                    <button class="delete-lot-btn" data-id="${lot.id}">🗑</button>
                                </div>
                            `;
                        });
                    } else {
                        lotsHTML = '<p>Aucun lot pour ce chantier.</p>';
                    }
                } catch (err) {
                    lotsHTML = '<p>Impossible de charger les lots.</p>';
                    console.error(err);
                }
                card.innerHTML = `
                    <h3>${escapeHtml(chantier.nom)}</h3>
                    <button class="delete-chantier" data-id="${chantier.id}">Supprimer</button>
                    <div class="lots">${lotsHTML}</div>
                    <button class="add-lot-btn" data-id="${chantier.id}">+ Ajouter un lot</button>
                `;
                chantiersContainer.appendChild(card);
            }
        } catch (err) {
            console.error('Erreur chargement chantiers:', err);
            chantiersContainer.innerHTML = '<p>Impossible de charger les chantiers.</p>';
        }
    }

    // util
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // INIT
    initDateLimits();
    loadChantiers();
    
});






