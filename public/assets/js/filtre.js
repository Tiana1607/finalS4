/* ──────────────────────────────────────────────
   filtre.js — Filtres AJAX pour l'historique
   ────────────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('filtreForm');
    const tbody = document.getElementById('historiqueBody');
    const compteur = document.getElementById('historiqueCompteur');
    const emptyState = document.getElementById('historiqueEmpty');

    if (!form || !tbody) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        chargerHistorique();
    });

    form.querySelectorAll('select, input[type="date"], input[type="number"]').forEach(function (el) {
        el.addEventListener('change', function () {
            chargerHistorique();
        });
    });

    function chargerHistorique() {
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                    <span class="spinner-border spinner-border-sm me-2"></span>Chargement...
                </td>
            </tr>
        `;

        fetch('/client/historique/filtrer?' + params, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (response) {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.json();
        })
        .then(function (data) {
            afficherResultats(data);
        })
        .catch(function (err) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4 text-danger">
                        Erreur lors du chargement. Veuillez réessayer.
                    </td>
                </tr>
            `;
        });
    }

    function afficherResultats(data) {
        if (!data.historique || data.historique.length === 0) {
            tbody.innerHTML = '';
            if (emptyState) emptyState.classList.remove('d-none');
            if (compteur) compteur.textContent = '0 transaction(s)';
            return;
        }

        if (emptyState) emptyState.classList.add('d-none');
        if (compteur) compteur.textContent = data.historique.length + ' transaction(s)';

        let html = '';
        data.historique.forEach(function (tx) {
            const date = new Date(tx.date_operation);
            const dateFormatee = date.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            const libelleClass = {
                'depot': 'bg-success',
                'retrait': 'bg-warning text-dark',
                'transfert': 'bg-info'
            };

            const badgeClass = libelleClass[tx.type_libelle] || 'bg-secondary';

            html += `
                <tr>
                    <td>${dateFormatee}</td>
                    <td><span class="badge ${badgeClass}">${capitalize(tx.type_libelle)}</span></td>
                    <td class="montant-badge">${numberFormat(tx.montant)} Ar</td>
                    <td>${numberFormat(tx.frais)} Ar</td>
                    <td>${tx.destinataire_tel ? escapeHtml(tx.destinataire_tel) : '—'}</td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    function numberFormat(n) {
        return Number(n).toLocaleString('fr-FR');
    }

    function capitalize(s) {
        return s ? s.charAt(0).toUpperCase() + s.slice(1) : '';
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

});
