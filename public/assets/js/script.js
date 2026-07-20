/* ──────────────────────────────────────────────
   script.js — Modales Bootstrap & utilitaires
   ────────────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', function () {

    /* ── Modale de confirmation réutilisable ── */
    window.confirmModal = function (options) {
        const {
            titre   = 'Confirmer',
            message = 'Êtes-vous sûr ?',
            btnText = 'Confirmer',
            btnClass = 'btn-danger',
            onConfirm = null,
            form = null
        } = options;

        // Supprimer ancienne modale si elle existe
        const old = document.getElementById('confirmModal');
        if (old) old.remove();

        const modalHtml = `
            <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${titre}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0">${message}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn ${btnClass}" id="confirmModalBtn">${btnText}</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);

        const modalEl = document.getElementById('confirmModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        document.getElementById('confirmModalBtn').addEventListener('click', function () {
            modal.hide();
            if (onConfirm) {
                onConfirm();
            } else if (form) {
                form.submit();
            }
        });

        modalEl.addEventListener('hidden.bs.modal', function () {
            modalEl.remove();
        });

        return modal;
    };

    /* ── Remplacer tous les confirm() natifs ── */
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            const form = el.closest('form');

            confirmModal({
                titre: el.getAttribute('data-confirm-title') || 'Confirmer',
                message: el.getAttribute('data-confirm'),
                btnText: el.getAttribute('data-confirm-btn') || 'Confirmer',
                btnClass: el.getAttribute('data-confirm-class') || 'btn-danger',
                form: form
            });
        });
    });

    /* ── Auto-dismiss alerts après 5s ── */
    document.querySelectorAll('.alert-dismissible').forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000);
    });

    /* ── Form submission loader ── */
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Chargement...';
                btn.dataset.originalText = originalText;
            }
        });
    });

    /* ── Formatage automatique des numéros de téléphone (0XX XX XXX XX) ── */
    document.querySelectorAll('input[type="tel"]').forEach(function (input) {
        input.addEventListener('input', function () {
            const chiffres = this.value.replace(/\D/g, '').slice(0, 10);
            let formate = '';
            if (chiffres.length > 0) formate += chiffres.slice(0, 3);
            if (chiffres.length > 3) formate += ' ' + chiffres.slice(3, 5);
            if (chiffres.length > 5) formate += ' ' + chiffres.slice(5, 8);
            if (chiffres.length > 8) formate += ' ' + chiffres.slice(8, 10);

            this.value = formate;
            this.setSelectionRange(formate.length, formate.length);
        });

        input.addEventListener('paste', function () {
            const self = this;
            setTimeout(function () {
                self.dispatchEvent(new Event('input'));
            }, 0);
        });
    });

    /* ── Tooltips Bootstrap (si utilisés) ── */
    const tooltipTriggers = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggers.forEach(function (el) {
        new bootstrap.Tooltip(el);
    });

});
