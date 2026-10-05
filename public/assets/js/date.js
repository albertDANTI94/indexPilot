let DEFAULT_MIN_MONTH = '2010-01';
let DEFAULT_MAX_MONTH = '';

async function loadDateLimits() {
    try {
        const resp = await fetch('/public/api/indices/max-date', {
            cache: "no-store"
        });

        const data = await resp.json();

        if (data.success && data.max_date) {
            DEFAULT_MAX_MONTH = data.max_date;
            console.log("MAX DATE:", DEFAULT_MAX_MONTH);
        }

    } catch (e) {
        console.error('Erreur chargement date max:', e);

        const today = new Date();
        DEFAULT_MAX_MONTH = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}`;
    }
}