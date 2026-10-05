    let state = {
        lots: window.LOTS_DATA || [],
        filtered: [],
        currentPage: 1,
        perPage: 10,
        sortKey: 'nom',
        sortDir: 'asc',
        filters: {
            chantier: 'all',
            indice: 'all',
            status: 'all',
            search: '',
            tab: 'all'
        },
        selectedLot: null
    };
        
    function computeKPIs(lots) {

        const activeLots = lots.filter(l => l.status !== 'archived');
    
        const totalAmount = lots.reduce((sum, l) => {
            return sum + (parseFloat(l.nouveauTarif) || 0);
        }, 0);
    
        const calculs = lots.length * 2; // ⚠️ placeholder (à remplacer si tu as une vraie table calculs)
    
        const now = new Date();
        const in30Days = new Date();
        in30Days.setDate(now.getDate() + 30);
    
        const upcoming = lots.filter(l => {
            if (!l.updatedAt) return false;
            const d = new Date(l.updatedAt);
            return d <= in30Days && d >= now;
        }).length;
    
        return {
            activeLots: activeLots.length,
            totalAmount,
            calculs,
            upcoming
        };
    }

    function renderKPIs(kpis) {
    
        const el1 = document.getElementById('kpiActiveLots');
        const el2 = document.getElementById('kpiTotalAmount');
        const el3 = document.getElementById('kpiCalculs');
        const el4 = document.getElementById('kpiUpcoming');
    
        if (el1) el1.textContent = kpis.activeLots;
    
        if (el2) {
            el2.textContent =
                kpis.totalAmount.toLocaleString('fr-FR') + " €";
        }
    
        if (el3) el3.textContent = kpis.calculs;
    
        if (el4) el4.textContent = kpis.upcoming;
    }
    
    
    
    function computeView() {

        let data = [...state.lots];
    
        // SEARCH
        if (state.filters.search) {
            const s = state.filters.search.toLowerCase();
            data = data.filter(l =>
                l.nom.toLowerCase().includes(s)
            );
        }
    
        // STATUS
        if (state.filters.status !== 'all') {
            data = data.filter(l => l.status === state.filters.status);
        }
    
        // CHANTIER
        if (state.filters.chantier !== 'all') {
            data = data.filter(l => String(l.chantierId) === state.filters.chantier);
        }
        
        // LIBELLE
        if (state.filters.indice !== 'all') {
            data = data.filter(l =>
                String(l.libelle) === state.filters.indice
            );
        }
        
    
        // SORT
        data.sort((a, b) => {
            let valA = a[state.sortKey];
            let valB = b[state.sortKey];
    
            if (typeof valA === 'string') valA = valA.toLowerCase();
            if (typeof valB === 'string') valB = valB.toLowerCase();
    
            if (valA < valB) return state.sortDir === 'asc' ? -1 : 1;
            if (valA > valB) return state.sortDir === 'asc' ? 1 : -1;
            return 0;
        });
    
        state.filtered = data;
        
        console.log(state.filters.status);
        console.log(state.lots);
        
        if (state.filters.tab !== 'all') {
            data = data.filter(l => l.status === state.filters.tab);
        }
        
        if (state.filters.status !== 'all') {

            data = data.filter(l => {
                console.log(l.status, state.filters.status);
                return l.status === state.filters.status;
            });
        }
    
        renderTable();
        renderPagination();
        
        console.log("Filtre status :", state.filters.status);
        console.log("Lots avant filtre :", data.length);
    }
    
    function renderTable() {

        const tbody = document.querySelector('tbody');
        if (!tbody) return;
    
        const start = (state.currentPage - 1) * state.perPage;
        const end = start + state.perPage;
    
        const pageItems = state.filtered.slice(start, end);
    
        tbody.innerHTML = '';
    
        pageItems.forEach(lot => {
    
            const tr = document.createElement('tr');
    
            tr.innerHTML = `
                <td>${lot.nom}</td>
                <td>${lot.chantierNom}</td>
                <td>${lot.tarifOrigine}</td>
                <td>${lot.partFerme}</td>
                <td>${lot.coefficient}</td>
                <td>${lot.nouveauTarif}</td>
                <td>${lot.updatedAt || '-'}</td>
                <td><span class="badge">${lot.status}</span></td>
                <td><a class="btn btn-primary-mini" href="/public/lots/update/${lot.id}">Modifier</a></td>
                <td><a class="btn btn-secondary-mini" href="/public/lots/delete/${lot.id}">Supprimer</a></td>
            `;
    
            // CLICK ROW → SIDE PANEL
            tr.addEventListener('click', () => {

                // Retire la sélection précédente
                document.querySelectorAll('tbody tr').forEach(row => {
                    row.classList.remove('tr-selected');
                });
            
                // Ajoute la sélection sur la ligne courante
                tr.classList.add('tr-selected');
            
                state.selectedLot = lot;
            
                renderSidePanel();
            });
    
            tbody.appendChild(tr);
        });
    }
    
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
    
    document.querySelector('.search')?.addEventListener('input', e => {
    state.filters.search = e.target.value;
    state.currentPage = 1;
    computeView();
    });
    
    document.querySelectorAll('select')[0]?.addEventListener('change', e => {
        state.filters.chantier = e.target.value;
        computeView();
    });
    
    document.querySelectorAll('select')[2]?.addEventListener('change', e => {
        console.log("Status sélectionné :", e.target.value);
        state.filters.status = e.target.value;
        computeView();
    });
    
    document.getElementById('filterChantier')?.addEventListener('change', e => {
    state.filters.chantier = e.target.value;

    state.currentPage = 1;

    computeView();
    });
    
    document.getElementById('filterStatus')?.addEventListener('change', e => {
        state.filters.status = e.target.value;
        state.currentPage = 1;
        computeView();
    });
    
    
    document.getElementById('filterIndice')?.addEventListener('change', e => {
    state.filters.indice = e.target.value;
    computeView();
    });
    
    document.querySelectorAll('.tabs button').forEach(btn => {

        btn.addEventListener('click', () => {
    
            document
                .querySelectorAll('.tabs button')
                .forEach(b => b.classList.remove('active'));
    
            btn.classList.add('active');
    
            state.filters.status = btn.dataset.status;
    
            state.currentPage = 1;
    
            computeView();
        });
    });
    
    function renderSidePanel() {

        const lot = state.selectedLot;
    
        if (!lot) return;
    
        document.getElementById('spNom').textContent = lot.nom;
        document.getElementById('spIndice').textContent = lot.libelleTexte || '-';
        document.getElementById('spCoef').textContent = lot.coefficient;
    
        const montant = parseFloat(lot.nouveauTarif || 0);
    
        document.getElementById('spMontant').textContent =
            montant.toLocaleString('fr-FR') + " €";
    
        // faux calcul (à remplacer par vraie table calculs plus tard)
        const calculs = Math.floor(Math.random() * 30);
    
        document.getElementById('spCalculs').textContent = calculs;
    
        // performance
        const gain = montant * 0.03;
    
        document.getElementById('spGain').textContent =
            "+ " + gain.toLocaleString('fr-FR') + " €";
    
        document.getElementById('spDerniere').textContent =
            lot.updatedAt || "-";
            
        renderPerformanceChart(state.selectedLot);
    }
    
    function updatePerformancePanel() {

        const lot = state.selectedLot;
        if (!lot) return;
    
        const montant = parseFloat(lot.nouveauTarif || 0);
    
        const revisions = Math.floor(Math.random() * 20); // placeholder SaaS
    
        const gain = montant * 0.04;
    
        document.querySelector('.panel-card .positive').textContent =
            "+ " + gain.toLocaleString('fr-FR') + " €";
    
        const lastDate = lot.updatedAt || "-";
    
        const lastEl = document.querySelectorAll('.detail-row span')[5];
        if (lastEl) lastEl.textContent = lastDate;
    }
    
    let performanceChart = null;
    
    function renderPerformanceChart(lot) {

        const ctx = document.getElementById('performanceChart');
    
        if (!ctx || !lot) return;
    
        const base = parseFloat(lot.tarifOrigine || 0);
        const final = parseFloat(lot.nouveauTarif || 0);
        const gain = final - base;
    
        const data = {
            labels: ["Tarif initial", "Tarif révisé", "Gain"],
            datasets: [{
                label: "€",
                data: [base, final, gain],
                borderWidth: 2,
                backgroundColor: [
                    'rgba(100, 116, 139, 0.4)',
                    'rgba(59, 130, 246, 0.4)',
                    'rgba(34, 197, 94, 0.4)'
                ],
                borderColor: [
                    'rgba(100, 116, 139, 1)',
                    'rgba(59, 130, 246, 1)',
                    'rgba(34, 197, 94, 1)'
                ],
                fill: true
            }]
        };
    
        const config = {
            type: 'bar',
            data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                return context.raw.toLocaleString('fr-FR') + " €";
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) =>
                                value.toLocaleString('fr-FR') + " €"
                        }
                    }
                }
            }
        };
    
        // destroy ancien chart
        if (performanceChart) {
            performanceChart.destroy();
        }
    
        performanceChart = new Chart(ctx, config);
        
        if (!lot) {
            if (performanceChart) performanceChart.destroy();
            return;
        }
    }
    
    document.querySelectorAll('.tabs button').forEach(btn => {
        btn.addEventListener('click', () => {
    
            document.querySelectorAll('.tabs button')
                .forEach(b => b.classList.remove('active'));
    
            btn.classList.add('active');
    
            state.filters.tab = btn.dataset.tab;
            state.currentPage = 1;
    
            computeView();
        });
    });
    
    function populateChantierFilter() {

        const select =
            document.getElementById('filterChantier');
    
        if (!select) return;
    
        const chantiers = [
            ...new Set(
                state.lots.map(l => [
                    l.chantierId,
                    l.chantierNom
                ])
            ).values()
        ];
    
        select.innerHTML =
            '<option value="all">Tous les chantiers</option>';
    
        state.lots.forEach(lot => {
    
            if (
                !select.querySelector(
                    `option[value="${lot.chantierId}"]`
                )
            ) {
    
                const option =
                    document.createElement('option');
    
                option.value = lot.chantierId;
                option.textContent = lot.chantierNom;
    
                select.appendChild(option);
            }
        });
    }
    
        function populateIndiceFilter() {
    
        const select =
            document.getElementById('filterIndice');
    
        if (!select) return;
    
        select.innerHTML =
            '<option value="all">Tous les indices</option>';
    
        const uniques = {};
    
        state.lots.forEach(lot => {
    
            if (!lot.libelle) return;
    
            if (uniques[lot.libelle]) return;
    
            uniques[lot.libelle] = true;
    
            const option =
                document.createElement('option');
    
            option.value = lot.libelle;
            option.textContent =
                lot.libelleTexte || lot.libelle;
    
            select.appendChild(option);
        });
    }
    
    
    document.addEventListener('DOMContentLoaded', () => {

        const lots = window.LOTS_DATA || [];
    
        const kpis = computeKPIs(lots);
    
        renderKPIs(kpis);
    
        populateChantierFilter();
        populateIndiceFilter();
    
        renderSidePanel();
        updatePerformancePanel();
    
        computeView();
    });
