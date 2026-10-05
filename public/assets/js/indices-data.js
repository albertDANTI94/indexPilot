const state = {
    indices: window.INDICES_DATA || [],
    filtered: [],
    selected: null,

    filters: {
        search: '',
        tab: 'all',
        variation: 'all',
        libelle: 'all'
    },

    currentPage: 1,
    perPage: 10
};


function computeView() {

    let data = [...state.indices];

    const s = state.filters.search.toLowerCase();

    // SEARCH
    if (s) {
        data = data.filter(i =>
            i.code?.toLowerCase().includes(s) ||
            i.libelle?.toLowerCase().includes(s)
        );
    }

    // TAB (BT / TP / ICC)
    /*if (state.filters.tab !== 'all') {

        data = data.filter(i => {
    
            const lib = (i.libelle || '').toUpperCase();
    
            switch(state.filters.tab) {
    
                case 'TP':
                    return lib.includes('TP');
    
                case 'BT':
                    return lib.includes('BT');
    
                case 'ICC':
                    return lib.includes('ICC');
    
                default:
                    return true;
            }
        });
    }*/
    
    if (state.filters.tab !== 'all') {

            data = data.filter(i => {
        
                const libelle = i.libelle || '';
        
                if (state.filters.tab === 'BT') {
                    return /BT\d+/i.test(libelle);
                }
        
                if (state.filters.tab === 'TP') {
                    return /TP\d+/i.test(libelle);
                }
        
                if (state.filters.tab === 'ICC') {
                    return /ICC/i.test(libelle);
                }
        
                return true;
            });
    }
    
    
    console.log(state.indices);
    
    // FAMILY (si tu as un champ famille)
        if (state.filters.libelle !== 'all') {
    
        data = data.filter(i =>
            i.libelle === state.filters.libelle
        );
    }
    
    if (state.filters.tab !== 'all') {
        data = data.filter(i =>
            i.famille === state.filters.tab
        );
    }

    // VARIATION FILTER
    if (state.filters.variation === 'positive') {
        data = data.filter(i => i.variation > 0);
    }

    if (state.filters.variation === 'negative') {
        data = data.filter(i => i.variation < 0);
    }
    
    console.log(
        "TAB =",
        state.filters.tab,
        "RESULTATS =",
        data.length
    );

    state.filtered = data;

    renderTable();
    renderKPIs();
}


function computeKPIs() {

    const indices = state.filtered.length ? state.filtered : state.indices;

    const variationStrong = indices.filter(i =>
        Math.abs(i.variation) > 3
    ).length;

    const totalLots = indices.reduce((sum, i) =>
        sum + (parseInt(i.lots || 0)), 0
    );

    const last = indices.reduce((max, i) => {
        return (!max || new Date(i.date) > new Date(max)) ? i.date : max;
    }, null);

    return {
        total: indices.length,
        variationStrong,
        totalLots,
        lastUpdate: last
            ? new Date(last).toLocaleDateString('fr-FR', { month: '2-digit', year: 'numeric' })
            : '-'
    };
}


function renderKPIs() {

    const kpis = computeKPIs();

    document.getElementById('kpiTotal')
        .textContent = kpis.total;

    document.getElementById('kpiLast')
        .textContent = kpis.lastUpdate
        ? kpis.lastUpdate.slice(0, 7)
        : '-';

    document.getElementById('kpiStrong')
        .textContent = kpis.variationStrong;

    document.getElementById('kpiLots')
        .textContent = kpis.totalLots;
}

function populateFamilyFilter() {

    const select = document.getElementById('filterFamily');

    if (!select) return;

    const families = [
        ...new Set(
            state.indices.map(i => i.family).filter(Boolean)
        )
    ];

    families.forEach(f => {

        const option = document.createElement('option');

        option.value = f;
        option.textContent = f;

        select.appendChild(option);
    });
}

function renderTable() {

    const tbody = document.getElementById('indicesTbody');
    if (!tbody) return;

    tbody.innerHTML = '';

    state.filtered.forEach(i => {

        const tr = document.createElement('tr');

        const variationClass = i.variation >= 0 ? 'positive' : 'negative';

        tr.innerHTML = `
            <td>${i.code}</td>
            <td>${i.libelle}</td>
            <td>${i.valeur}</td>
            <td>${i.ancienne_valeur}</td>
            <td class="${variationClass}">
                ${i.variation.toFixed(2)}%
            </td>
            <td>${i.date?.slice(0,7)}</td>
            <td>${i.lots}</td>
        `;
    

        // CLICK ROW → SIDE PANEL
            tr.addEventListener('click', () => {

            // reset UI sélection
            document.querySelectorAll('#indicesTbody tr')
                .forEach(row => row.classList.remove('tr-selected'));
        
            tr.classList.add('tr-selected');
        
            state.selected = i;
        
            renderSidePanel();
        });

        tbody.appendChild(tr);
    });
}

function renderSidePanel() {

    const i = state.selected;

    if (!i) return;

    document.getElementById('spCode').textContent =
        i.code || '-';

    document.getElementById('spLibelle').textContent =
        i.libelle || '-';

    document.getElementById('spValue').textContent =
        Number(i.valeur || 0).toFixed(3);

    document.getElementById('spOldValue').textContent =
        Number(i.ancienne_valeur || 0).toFixed(3);

    const variationElement =
        document.getElementById('spVariation');

    variationElement.textContent =
        Number(i.variation || 0).toFixed(2) + '%';

    variationElement.className =
        i.variation >= 0
            ? 'positive'
            : 'negative';

    document.getElementById('spDate').textContent =
        i.date
            ? new Date(i.date).toLocaleDateString('fr-FR')
            : '-';

    document.getElementById('spLots').textContent =
        i.lots || 0;

    document.getElementById('spMontant').textContent =
        ((i.lots || 0) * 100000)
            .toLocaleString('fr-FR')
        + ' €';

    document.getElementById('spRevision').textContent =
        (
            ((i.lots || 0) * 100000)
            *
            (i.variation / 100)
        ).toLocaleString('fr-FR')
        + ' €';

    document.getElementById('spLastUpdate').textContent =
        i.date
            ? new Date(i.date).toLocaleDateString('fr-FR')
            : '-';

    renderChart(i);
}

let chartInstance = null;

function renderChart(indice)
{
    const ctx =
        document.getElementById('performanceChart');

    if (!ctx) return;

    if (chartInstance) {
        chartInstance.destroy();
    }

    const labels =
        indice.history.map(h =>
            h.date.substring(0, 7)
        );

    const values =
        indice.history.map(h =>
            parseFloat(h.valeur)
        );

    chartInstance = new Chart(ctx, {
        type: 'line',

        data: {
            labels,
            datasets: [{
                label: indice.code,
                data: values,
                tension: 0.3
            }]
        },

        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

function populateLibelleFilter() {

    const select = document.getElementById('filterLibelle');

    if (!select) return;

    const libelles = [
        ...new Set(state.indices.map(i => i.libelle))
    ];

    libelles.sort();

    libelles.forEach(libelle => {

        const option = document.createElement('option');

        option.value = libelle;
        option.textContent = libelle;

        select.appendChild(option);
    });
}

document.addEventListener('DOMContentLoaded', () => {

    document.getElementById('indicesSearch')
    ?.addEventListener('input', e => {
    
        state.filters.search = e.target.value;
    
        computeView();
    });
    
    document.getElementById('filterVariation')
    ?.addEventListener('change', e => {
    
        state.filters.variation = e.target.value;
    
        computeView();
    });
    
    document.getElementById('filterLibelle')
    ?.addEventListener('change', e => {
    
        state.filters.libelle =
            e.target.value;
    
        computeView();
    });

    document.querySelectorAll('.tabs button')
    .forEach(btn => {
    
        btn.addEventListener('click', () => {
            
            console.log(btn.dataset.tab);
    
            document.querySelectorAll('.tabs button')
                .forEach(b => b.classList.remove('active'));
    
            btn.classList.add('active');
    
            state.filters.tab = btn.dataset.tab || 'all';
    
            computeView();
        });
    });
    
    
    computeView();
    
    if (state.filtered.length > 0) {

        state.selected = state.filtered[0];
    
        renderSidePanel();
    
        const firstRow =
            document.querySelector('#indicesTbody tr');
    
        firstRow?.classList.add('tr-selected');
    }
    
    populateLibelleFilter();
    renderTable();
    renderKPIs();
    console.table(state.indices);
});