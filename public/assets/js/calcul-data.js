let state = {

    calculs: window.CALCULS_DATA || [],
    filtered: [],

    currentPage: 1,
    perPage: 10,

    selectedCalcul: null,

    filters: {
        search: '',
        status: 'all',
        libelle: 'all',
        period: 'all'
    }
   
};

/*
|--------------------------------------------------------------------------
| KPI
|--------------------------------------------------------------------------
*/

function computeKPIs() {

    const calculs = state.calculs;

    const now = new Date();
    

    const calculsMonth = calculs.filter(c => {

        const d = new Date(c.date);

        return (
            d.getMonth() === now.getMonth()
            &&
            d.getFullYear() === now.getFullYear()
        );

    }).length;

    const montantRevise = calculs.reduce((sum, c) => {

        return sum + parseFloat(c.nouveauTarif || 0);

    }, 0);

    const impactTotal = calculs.reduce((sum, c) => {

        return sum + parseFloat(c.impact || 0);
    
    }, 0);

    return {
        totalCalculs: calculs.length,
        montantRevise,
        impactTotal,
        calculsMonth
    };
}

function renderKPIs() {

    const kpis = computeKPIs();

    document.getElementById('kpiEconomies').textContent =
    kpis.impactTotal.toLocaleString('fr-FR') + ' €';

    document.getElementById('kpiMontantRevise').textContent =
        kpis.montantRevise.toLocaleString('fr-FR')
        + ' €';

    document.getElementById('kpiImpact').textContent =
        kpis.impactTotal.toLocaleString('fr-FR')
        + ' €';

    document.getElementById('kpiCalculsMonth').textContent =
        kpis.calculsMonth;
}

/*
|--------------------------------------------------------------------------
| TABLE VIEW
|--------------------------------------------------------------------------
*/

function computeView() {

    let data = [...state.calculs];

    // SEARCH
    if (state.filters.search) {

        const s = state.filters.search.toLowerCase();

        data = data.filter(c =>
            c.reference?.toLowerCase().includes(s) ||
            c.libelle?.toLowerCase().includes(s)
        );
    }

    if (state.filters.status === 'positive') {

        data = data.filter(c =>
            parseFloat(c.impact || 0) > 0
        );
    }
    
    if (state.filters.status === 'negative') {
    
        data = data.filter(c =>
            parseFloat(c.impact || 0) < 0
        );
    }
    
    if (state.filters.status === 'month') {
    
        const now = new Date();

        data = data.filter(c => {
            const d = new Date(c.date.replace(' ', 'T'));
            return d.getMonth() === now.getMonth() &&
                   d.getFullYear() === now.getFullYear();
        });
    }
    
    if (state.filters.status === 'year') {
    
        const year = new Date().getFullYear();
    
        data = data.filter(c => {
    
            const d = new Date(c.date);
    
            return d.getFullYear() === year;
        });
    }
    
    if (state.filters.libelle !== 'all') {

        data = data.filter(c => {
    
            const lib = c.libelleTexte || c.libelle_texte || c.libelle;
    
            return String(lib) === String(state.filters.libelle);
        });
    }
    
    if (state.filters.period !== 'all') {

        const now = new Date();
    
        data = data.filter(c => {
    
            //const d = new Date(c.date);
            const d = new Date(c.date.replace(' ', 'T'));
    
            if (state.filters.period === 'month') {
    
                return (
                    d.getMonth() === now.getMonth()
                    &&
                    d.getFullYear() === now.getFullYear()
                );
            }
            
            
    
            if (state.filters.period === 'year') {
    
                return (
                    d.getFullYear() === now.getFullYear()
                );
            }
    
            return true;
        });
    }
    
    if (state.filters.period === 'month') {

        const now = new Date();
    
        data = data.filter(c => {
            const d = new Date(c.date);
            return d.getMonth() === now.getMonth()
                && d.getFullYear() === now.getFullYear();
        });
    }
    
    if (state.filters.libelle !== 'all') {

        data = data.filter(c =>
            (c.libelleTexte || c.libelle_texte || c.libelle)
            === state.filters.libelle
        );
    }

    console.log("Calculs reçus :", data);

    state.filtered = data;

    console.log("Filtered :", state.filtered);

    renderTable();
    renderPagination();

    state.filtered = data;

}


function populateLibelleFilter() {

    const select = document.getElementById('filterLibelle');
    if (!select) return;

    const libelles = [
        ...new Set(
            state.calculs.map(c =>
                c.libelleTexte || c.libelle || ''
            )
        )
    ].filter(Boolean);

    libelles.forEach(libelle => {

        const option = document.createElement('option');

        option.value = libelle;
        option.textContent = libelle;

        select.appendChild(option);
    });
}

/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/

function renderTable() {

    const tbody = document.querySelector('tbody');
    if (!tbody) return;

    tbody.innerHTML = '';

    const start = (state.currentPage - 1) * state.perPage;
    const pageItems = state.filtered.slice(start, start + state.perPage);

    pageItems.forEach(calcul => {

        const variation =
            ((calcul.indiceNn - calcul.indiceN0) / calcul.indiceN0) * 100;

        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${calcul.reference}</td>
            <td>${calcul.tarifOrigin}</td>
            <td>${calcul.partFerme}%</td>
            <td>${calcul.libelleTexte || calcul.libelle}</td>
            <td>${calcul.indiceN0}</td>
            <td>${calcul.indiceNn}</td>
            <td class="${variation >= 0 ? 'positive' : 'negative'}">
                ${variation.toFixed(2)}%
            </td>
            <td>${calcul.nouveauTarif}</td>
            <td>${calcul.date}</td>
            <td>
                <a class="bt btn-secondary-mini" href="/calcul/delete/${calcul.id}">
                    Supprimer
                </a>
            </td>
        `;

        /*
        |--------------------------------------------------------------------------
        | CLICK ROW → SIDEPANEL
        |--------------------------------------------------------------------------
        */

        tr.addEventListener('click', () => {

            document.querySelectorAll('tbody tr')
                .forEach(r => r.classList.remove('tr-selected'));

            tr.classList.add('tr-selected');

            state.selectedCalcul = calcul;

            renderSidePanel();
        });
        

        tbody.appendChild(tr);
    });
}

/*
|--------------------------------------------------------------------------
| SIDEPANEL
|--------------------------------------------------------------------------
*/

function renderSidePanel() {

    const calcul = state.selectedCalcul;
    if (!calcul) return;

    document.getElementById('spReference').textContent =
        calcul.reference;

    document.getElementById('spLibelle').textContent =
        calcul.libelleTexte || calcul.libelle;

    document.getElementById('spPeriode').textContent =
        `${calcul.date0 || '-'} → ${calcul.dateN || '-'}`;

    document.getElementById('spImpact').textContent =
        (calcul.impact || 0).toLocaleString('fr-FR') + ' €';

    document.getElementById('spIndice0').textContent =
        calcul.indiceN0;

    document.getElementById('spIndiceN').textContent =
        calcul.indiceNn;

    document.getElementById('spVariation').textContent =
        (calcul.variation || 0).toFixed(2) + '%';

    renderChart(calcul);
}

/*
|--------------------------------------------------------------------------
| CHART
|--------------------------------------------------------------------------
*/

let chartInstance = null;

function renderChart(c) {

    const ctx = document.getElementById('calculChart');
    if (!ctx) return;

    if (chartInstance) chartInstance.destroy();

    const base = parseFloat(c.tarif || 0);
    const final = parseFloat(c.nouveauTarif || 0);

    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Initial', 'Révisé', 'Gain'],
            datasets: [{
                data: [base, final, final - base]
            }]
        },
        options: {
            responsive: true
        }
    });
}

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

function renderPagination() {

    const container = document.querySelector('.pagination');
    if (!container) return;

    const pages = Math.ceil(state.filtered.length / state.perPage);

    container.innerHTML = '';

    for (let i = 1; i <= pages; i++) {

        const btn = document.createElement('button');
        btn.textContent = i;

        if (i === state.currentPage) btn.classList.add('active');

        btn.addEventListener('click', () => {
            state.currentPage = i;
            renderTable();
            renderPagination();
        });

        container.appendChild(btn);
    }
}

/*
|--------------------------------------------------------------------------
| INIT
|--------------------------------------------------------------------------
*/
document
.getElementById('filterLibelle')
?.addEventListener('change', e => {

    state.filters.libelle =
        e.target.value;

    state.currentPage = 1;

    computeView();
});

document
.getElementById('filterPeriod')
?.addEventListener('change', e => {

    state.filters.period = e.target.value;

    state.currentPage = 1;

    computeView();
});

document
.querySelectorAll('.tabs button')
.forEach(btn => {

    btn.addEventListener('click', () => {

        document
            .querySelectorAll('.tabs button')
            .forEach(b =>
                b.classList.remove('active')
            );

        btn.classList.add('active');

        state.filters.status =
            btn.dataset.status;

        state.currentPage = 1;

        computeView();
    });
});

document.addEventListener('DOMContentLoaded', () => {

    console.log(window.CALCULS_DATA);

    renderKPIs();
    populateLibelleFilter(); // 👈 MANQUANT
    computeView();
});