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
    
            const resp = await fetch('/functions/get_libelles.php', {
                cache: "no-store"
            });
    
            if (!resp.ok) throw new Error(resp.status);
    
            const data = await resp.json();
    
            selectLibelle.innerHTML = '<option value="">-- Choisir --</option>';
    
            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = String(item.idbank);
                opt.textContent = item.libelle;
    
                if (currentLibelle && currentLibelle === String(item.idbank)) {
                    opt.selected = true;
                }
    
                selectLibelle.appendChild(opt);
            });
    
        } catch (e) {
            console.error(e);
            selectLibelle.innerHTML = '<option>Erreur chargement</option>';
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
                    `/functions/get_indice.php?idbank=${encodeURIComponent(idbank)}&date=${encodeURIComponent(d0)}`,
                    { cache: "no-store" }
                );

                const j0 = await r0.json();

                indice0Input.value = j0.success ? j0.valeur : '';
            }

            // -------- indice date finale --------
            if (dN) {
                const rN = await fetch(
                    `/functions/get_indice.php?idbank=${encodeURIComponent(idbank)}&date=${encodeURIComponent(dN)}`,
                    { cache: "no-store" }
                );

                const jN = await rN.json();

                indiceNInput.value = jN.success ? jN.valeur : '';
            }

        } catch (err) {
            console.error('Erreur récupération indices:', err);
        }
    }

    date0Input.addEventListener('change', updateIndices);
    dateNInput.addEventListener('change', updateIndices);
    
    initDateLimits();
});