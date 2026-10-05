console.log("calcul.js chargé");

document.addEventListener('DOMContentLoaded', async () => {
    const form = document.getElementById('calculatorForm');
    //const resultContainer = document.getElementById('resultContainer');
    //const resultText = document.getElementById('resultText');
    const errorMessage = document.getElementById('errorMessage');

    const selectLibelle = document.getElementById('selectLibelle');
    const date0Input = document.getElementById('date0');
    const dateNInput = document.getElementById('dateN');
    const indice0Input = document.getElementById('indice0');
    const indiceNInput = document.getElementById('indiceN');

    if (
        !form ||
        //!resultContainer ||
        //!resultText ||
        !errorMessage ||
        !selectLibelle ||
        !date0Input ||
        !dateNInput ||
        !indice0Input ||
        !indiceNInput
    ) {
        console.error("Éléments DOM manquants.");
        return;
    }

    let currentIdbank = null;

    //const DEFAULT_MIN = '2010-01';
    //const DEFAULT_MAX = '2025-11';

    //date0Input.min = DEFAULT_MIN;
    //date0Input.max = DEFAULT_MAX;
    //dateNInput.min = DEFAULT_MIN;
    //dateNInput.max = DEFAULT_MAX;
    
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

    // -----------------------------
    // Chargement des libellés
    // -----------------------------

    
    const currentLibelle = String(window.AppData?.currentLibelle || '');

    async function loadLibelles() {
        try {
            selectLibelle.innerHTML = '<option>Chargement...</option>';
    
            const resp = await fetch('/public/api/indices/libelles', {
                cache: "no-store"
            });
    
            if (!resp.ok) throw new Error(resp.status);
    
            const data = await resp.json();
    
            if (!data.success || !Array.isArray(data.libelles)) {
                throw new Error('Réponse API invalide');
            }
    
            selectLibelle.innerHTML = '<option value="">-- Choisir --</option>';
    
            data.libelles.forEach(item => {
                const opt = document.createElement('option');
    
                opt.value = String(item.idbank);
                opt.textContent = item.libelle;
    
                if (
                    currentLibelle &&
                    currentLibelle === String(item.idbank)
                ) {
                    opt.selected = true;
                }
    
                selectLibelle.appendChild(opt);
            });
    
        } catch (e) {
            console.error(e);
            selectLibelle.innerHTML =
                '<option>Erreur chargement</option>';
        }
    }
    
    await loadLibelles();
    
    

    // -----------------------------
    // Reset IDBANK
    // -----------------------------
    selectLibelle.addEventListener('change', () => {
        currentIdbank = selectLibelle.value || null;
        updateIndices();
    });

    // -----------------------------
    // Mise à jour automatique
    // -----------------------------
    async function updateIndices() {
        const idbank = selectLibelle.value;
        const d0 = date0Input.value;
        const dN = dateNInput.value;

        if (!idbank) return;

        try {
            // -------- indice date initiale --------
            if (d0) {
                const r0 = await fetch(
                    `/public/api/indices/valeur?idbank=${encodeURIComponent(idbank)}&date=${encodeURIComponent(d0)}`,
                    { cache: "no-store" }
                );
                
                const j0 = await r0.json();
                
                indice0Input.value =
                    j0.success && j0.indice
                        ? j0.indice.valeur
                        : '';
            }

            // -------- indice date finale --------
            if (dN) {
                const rN = await fetch(
                    `/public/api/indices/valeur?idbank=${encodeURIComponent(idbank)}&date=${encodeURIComponent(dN)}`,
                    { cache: "no-store" }
                );
                
                const jN = await rN.json();
                
                indiceNInput.value =
                    jN.success && jN.indice
                        ? jN.indice.valeur
                        : '';
            }

        } catch (err) {
            console.error('Erreur récupération indices:', err);
        }
    }

    date0Input.addEventListener('change', updateIndices);
    dateNInput.addEventListener('change', updateIndices);
    
    

    // -----------------------------
    // Soumission du formulaire
    // -----------------------------
    console.log("listener submit attaché");
    console.log(form);
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        console.log("1 - submit détecté");
        
        errorMessage.textContent = '';
        //resultContainer.style.display = 'none';

        if (!indice0Input.value || !indiceNInput.value) {
            console.log("2 - indices manquants");
            errorMessage.textContent = "Impossible de calculer : indices manquants.";
            return;
        }
        
        console.log("3 - indices OK");

        const formData = new FormData(form);
        
         console.log("===== FORM DATA =====");

        for (const pair of formData.entries()) {
            console.log(pair[0] + " = " + pair[1]);
        }
        
        console.log("=====================");

        try {
            
            console.log("5 - avant fetch");
            
            const lotId = formData.get("id");

            const url = lotId
                ? `/public/lots/update/${lotId}`
                : '/public/lots/create';
            
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });
            
            console.log("6 - après fetch");
            
            const data = await response.json();
            
            console.log("7 - JSON reçu", data);

            if (data.success) {

                const cn = parseFloat(data.Cn);
                const nouveau = parseFloat(data.nouveauTarif);
                const base = parseFloat(formData.get("tarif_origine"));
            
                const variation = ((nouveau - base) / base) * 100;
            
                // ---- MAIN RESULT ----
                document.getElementById('resultAmount').textContent =
                    nouveau.toLocaleString('fr-FR') + " €";
            
                document.getElementById('resultVariation').textContent =
                    (variation > 0 ? "+" : "") + variation.toFixed(2) + "%";
            
                // ---- STATS ----
                document.getElementById('resultCn').textContent =
                    cn.toFixed(3);
            
                document.getElementById('resultDiff').textContent =
                    (nouveau - base).toLocaleString('fr-FR') + " €";
            
                document.getElementById('resultIndice').textContent =
                    selectLibelle.options[selectLibelle.selectedIndex]?.text || "--";
            
                document.getElementById('resultStatus').textContent =
                    "Calcul OK";

            } else {

                console.error(data);
            
                errorMessage.textContent =
                    data.message || "Erreur de calcul.";
            
            }

        } catch (err) {
            console.error('Erreur serveur:', err);
            errorMessage.textContent = "Erreur serveur.";
        }
    });
    
    initDateLimits();
});