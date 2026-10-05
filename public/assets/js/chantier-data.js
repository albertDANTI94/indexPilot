document.addEventListener('DOMContentLoaded', () => {

    console.log('CHANTIERS_DATA :');
    console.log(window.CHANTIERS_DATA);

});


/*
|--------------------------------------------------------------------------
| STATE GLOBAL
|--------------------------------------------------------------------------
| Contient toutes les données de la page
*/

let state = {

    chantiers: window.CHANTIERS_DATA || [],
    filtered: [],

    currentPage: 1,
    perPage: 10,

    selectedChantier: null,

    filters: {
        search: '',
        status: 'all',
        client: 'all'
    }
};

/*
|--------------------------------------------------------------------------
| KPI
|--------------------------------------------------------------------------
*/

function computeKPIs() {

    const chantiers = state.chantiers;

    const actifs =
        chantiers.filter(
            c => c.status === 'active'
        ).length;

    const archives =
        chantiers.filter(
            c => c.status === 'archived'
        ).length;

    const termines =
        chantiers.filter(
            c => c.status === 'finished'
        ).length;

    const suspendus =
        chantiers.filter(
            c => c.status === 'suspended'
        ).length;

    return {
        actifs,
        archives,
        termines,
        suspendus
    };
}

function renderKPIs() {

    const kpis = computeKPIs();

    document.getElementById('kpiActifs').textContent =
        kpis.actifs;

    document.getElementById('kpiArchives').textContent =
        kpis.archives;

    document.getElementById('kpiTermines').textContent =
        kpis.termines;

    document.getElementById('kpiSuspendus').textContent =
        kpis.suspendus;
}

/*
|--------------------------------------------------------------------------
| FILTRES
|--------------------------------------------------------------------------
*/

function computeView() {

    let data = [...state.chantiers];

    /*
    |--------------------------------------------------------------------------
    | Recherche
    |--------------------------------------------------------------------------
    */

    if (state.filters.search) {

        const s =
            state.filters.search.toLowerCase();

        data = data.filter(c =>

            c.nom.toLowerCase().includes(s)

            ||

            c.client.toLowerCase().includes(s)

            ||

            c.description.toLowerCase().includes(s)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Filtre client
    |--------------------------------------------------------------------------
    */

    if (state.filters.client !== 'all') {

        data = data.filter(
            c => c.client === state.filters.client
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Filtre statut
    |--------------------------------------------------------------------------
    */

    if (state.filters.status !== 'all') {

        data = data.filter(
            c => c.status === state.filters.status
        );
    }

    state.filtered = data;

    renderTable();
    renderPagination();
}

/*
|--------------------------------------------------------------------------
| TABLEAU
|--------------------------------------------------------------------------
*/

function renderTable() {

    const tbody =
        document.querySelector('tbody');

    if (!tbody) return;

    tbody.innerHTML = '';

    const start =
        (state.currentPage - 1)
        * state.perPage;

    const pageItems =
        state.filtered.slice(
            start,
            start + state.perPage
        );

    pageItems.forEach(chantier => {

        const tr =
            document.createElement('tr');

        tr.innerHTML = `
            <td>${chantier.nom}</td>
            <td>${chantier.client}</td>
            <td>${chantier.description}</td>
            <td>${chantier.createdAt}</td>
            <td>
                <span class="badge">
                    ${chantier.status}
                </span>
            </td>
            <td>
                <a class="btn btn-primary-mini" href="/public/chantiers/update/${chantier.id}">
                    Modifier
                </a>
            </td>
            <td>
                <a class="btn btn-secondary-mini" href="/public/chantiers/delete/${chantier.id}">
                    Supprimer
                </a>
            </td>
        `;

        /*
        |--------------------------------------------------------------------------
        | Sélection ligne
        |--------------------------------------------------------------------------
        */

        tr.addEventListener('click', () => {

            document
                .querySelectorAll('tbody tr')
                .forEach(row =>
                    row.classList.remove('tr-selected')
                );

            tr.classList.add('tr-selected');

            state.selectedChantier =
                chantier;

            renderSidePanel();
        });

        tbody.appendChild(tr);
    });
}

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

function renderPagination() {

    const container =
        document.querySelector('.pagination');

    if (!container) return;

    const pages =
        Math.ceil(
            state.filtered.length
            /
            state.perPage
        );

    container.innerHTML = '';

    for (let i = 1; i <= pages; i++) {

        const btn =
            document.createElement('button');

        btn.textContent = i;

        if (i === state.currentPage) {
            btn.classList.add('active');
        }

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
| SIDEPANEL
|--------------------------------------------------------------------------
*/

function renderSidePanel() {

    const chantier = state.selectedChantier;

    if (!chantier) return;

    document.getElementById('spClient').textContent =
        chantier.client;

    document.getElementById('spDescription').textContent =
        chantier.description;

    document.getElementById('spStatus').textContent =
        chantier.status;

    document.getElementById('spDate').textContent =
        chantier.createdAt;

    // LOTS
    document.getElementById('spLotsCount').textContent =
        chantier.lotsCount;

    document.getElementById('spGain').textContent =
        '+ ' + Number(chantier.gain).toLocaleString('fr-FR') + ' €';

    document.getElementById('spTotal').textContent =
        Number(chantier.totalNouveau).toLocaleString('fr-FR') + ' €';
}

/*
|--------------------------------------------------------------------------
| FILTRE CLIENT
|--------------------------------------------------------------------------
*/

function populateClientFilter() {

    const select =
        document.getElementById('filterClient');

    if (!select) return;

    select.innerHTML =
        '<option value="all">Tous les clients</option>';

    const clients =
        [...new Set(
            state.chantiers.map(
                c => c.client
            )
        )];

    clients.forEach(client => {

        const option =
            document.createElement('option');

        option.value = client;
        option.textContent = client;

        select.appendChild(option);
    });
}

/*
|--------------------------------------------------------------------------
| EVENTS
|--------------------------------------------------------------------------
*/

document.querySelector('.search')
?.addEventListener('input', e => {

    state.filters.search =
        e.target.value;

    state.currentPage = 1;

    computeView();
});

document.getElementById('filterClient')
?.addEventListener('change', e => {

    state.filters.client =
        e.target.value;

    state.currentPage = 1;

    computeView();
});

document.getElementById('filterStatus')
?.addEventListener('change', e => {

    state.filters.status =
        e.target.value;

    state.currentPage = 1;

    computeView();
});

/*
|--------------------------------------------------------------------------
| ONGLETS
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| INITIALISATION
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    () => {

        renderKPIs();

        populateClientFilter();

        computeView();
    }
);