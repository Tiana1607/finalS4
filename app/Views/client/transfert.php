<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfert</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Mon Compte</span>
            <a href="<?= base_url('/client/logout') ?>" class="btn btn-outline-light btn-sm">Déconnexion</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-3">
                    <a href="<?= base_url('/client/dashboard') ?>" class="text-decoration-none">&larr; Retour</a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title text-center mb-3">Transfert</h5>
                        <p class="text-muted text-center solde-display mb-3"><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</p>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('/client/transfert') ?>" method="post" id="transfertForm">
                            <!-- Destinataires -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Destinataires</label>
                                <div id="destinatairesList">
                                    <div class="destinataire-row mb-2">
                                        <div class="input-group">
                                            <input type="tel" class="form-control destinataire-input"
                                                   name="destinataires[]"
                                                   placeholder="034 12 123 12"
                                                   pattern="[\d\s]{10,15}" inputmode="numeric"
                                                   required>
                                            <span class="input-group-text destinataire-badge d-none"></span>
                                            <button type="button" class="btn btn-outline-danger btn-remove-destinataire d-none" title="Retirer">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnAjouterDestinataire">
                                    + Ajouter un destinataire
                                </button>
                                <small class="text-muted d-block mt-1" id="destinataireInfo"></small>
                            </div>

                            <!-- Montant -->
                            <div class="mb-3">
                                <label for="montant" class="form-label">Montant total (Ar)</label>
                                <input type="number" class="form-control" id="montant" name="montant"
                                       placeholder="Ex: 10000" min="1" step="any"
                                       value="<?= old('montant') ?>" required>
                                <small class="text-muted" id="montantParDest"></small>
                            </div>

                            <!-- Frais de retrait (visible uniquement même opérateur) -->
                            <div class="mb-3" id="fraisRetraitBlock" style="display: none;">
                                <label class="form-label">Frais de retrait</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="frais_retrait" id="sansFrais" value="0" checked>
                                    <label class="form-check-label" for="sansFrais">Sans frais de retrait (le destinataire paie)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="frais_retrait" id="avecFrais" value="1">
                                    <label class="form-check-label" for="avecFrais">Avec frais de retrait (vous payez)</label>
                                </div>
                            </div>

                            <!-- Récapitulatif -->
                            <div class="alert alert-light border mb-3" id="recapBlock" style="display: none;">
                                <small>
                                    <strong>Récapitulatif :</strong><br>
                                    <span id="recapMontant"></span><br>
                                    <span id="recapFrais"></span>
                                </small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-info text-white">Transférer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const liste = document.getElementById('destinatairesList');
        const btnAjouter = document.getElementById('btnAjouterDestinataire');
        const infoDest = document.getElementById('destinataireInfo');
        const fraisBlock = document.getElementById('fraisRetraitBlock');
        const montantInput = document.getElementById('montant');
        const montantParDest = document.getElementById('montantParDest');
        const recapBlock = document.getElementById('recapBlock');
        const recapMontant = document.getElementById('recapMontant');
        const recapFrais = document.getElementById('recapFrais');

        const emetteurOperateurId = <?= json_encode($emetteurOperateurId) ?>;
        let tousMemeOperateur = true;
        let detectTimers = {};

        btnAjouter.addEventListener('click', function () {
            const row = liste.querySelector('.destinataire-row').cloneNode(true);
            row.querySelector('input').value = '';
            const badge = row.querySelector('.destinataire-badge');
            badge.classList.add('d-none');
            badge.textContent = '';
            row.querySelector('.btn-remove-destinataire').classList.remove('d-none');
            liste.appendChild(row);
            updateUI();
        });

        liste.addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-remove-destinataire')) {
                e.target.closest('.destinataire-row').remove();
                updateUI();
            }
        });

        liste.addEventListener('input', function (e) {
            if (e.target.classList.contains('destinataire-input')) {
                clearTimeout(detectTimers[e.target.name]);
                detectTimers[e.target.name] = setTimeout(function () {
                    detecterOperateur(e.target);
                }, 400);
            }
        });

        montantInput.addEventListener('input', updateUI);

        document.querySelectorAll('input[name="frais_retrait"]').forEach(function (r) {
            r.addEventListener('change', updateUI);
        });

        function detecterOperateur(input) {
            const tel = input.value.replace(/\s/g, '');
            const row = input.closest('.destinataire-row');
            const badge = row.querySelector('.destinataire-badge');

            if (tel.length < 3) {
                badge.classList.add('d-none');
                badge.textContent = '';
                input.dataset.operateurId = '';
                input.dataset.estNous = '';
                checkTousMemeOperateur();
                updateUI();
                return;
            }

            fetch('/client/transfert/detecter-operateur?tel=' + encodeURIComponent(tel), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                input.dataset.operateurId = data.operateur_id || '';
                input.dataset.estNous = data.est_nous ? '1' : '0';

                if (data.nom) {
                    badge.textContent = data.nom;
                    badge.classList.remove('d-none');
                    badge.classList.remove('bg-success', 'bg-warning', 'bg-secondary');
                    if (data.est_nous) {
                        badge.classList.add('bg-success');
                        badge.style.color = '#fff';
                    } else {
                        badge.classList.add('bg-warning');
                        badge.style.color = '#000';
                    }
                } else {
                    badge.textContent = 'Inconnu';
                    badge.classList.remove('d-none', 'bg-success', 'bg-warning');
                    badge.classList.add('bg-secondary');
                    badge.style.color = '#fff';
                }

                checkTousMemeOperateur();
                updateUI();
            });
        }

        function checkTousMemeOperateur() {
            const inputs = liste.querySelectorAll('.destinataire-input');
            tousMemeOperateur = true;
            inputs.forEach(function (inp) {
                if (inp.dataset.estNous === '0') {
                    tousMemeOperateur = false;
                }
            });
        }

        function updateUI() {
            const nb = liste.querySelectorAll('.destinataire-row').length;
            const montant = parseFloat(montantInput.value) || 0;

            checkTousMemeOperateur();

            // Info nombre destinataires
            if (nb > 1) {
                const parDest = montant / nb;
                montantParDest.textContent = '→ ' + numberFormat(parDest) + ' Ar par destinataire';
                if (tousMemeOperateur) {
                    infoDest.textContent = nb + ' destinataires (même opérateur)';
                    infoDest.classList.remove('text-danger');
                    infoDest.classList.add('text-muted');
                } else {
                    infoDest.textContent = 'L\'envoi multiple ne fonctionne qu\'avec un même opérateur.';
                    infoDest.classList.remove('text-muted');
                    infoDest.classList.add('text-danger');
                }
            } else {
                montantParDest.textContent = '';
                infoDest.textContent = '';
                infoDest.classList.remove('text-danger');
                infoDest.classList.add('text-muted');
            }

            // Frais de retrait visible uniquement même opérateur
            if (tousMemeOperateur && nb > 0) {
                fraisBlock.style.display = '';
            } else {
                fraisBlock.style.display = 'none';
                document.getElementById('sansFrais').checked = true;
            }

            // Récapitulatif
            if (montant > 0 && nb > 0) {
                recapBlock.style.display = '';
                const parDest = nb > 1 ? montant / nb : montant;
                recapMontant.textContent = 'Montant : ' + numberFormat(montant) + ' Ar' + (nb > 1 ? ' (' + numberFormat(parDest) + ' Ar × ' + nb + ')' : '');
                recapFrais.textContent = 'Frais de transfert calculés automatiquement selon le barème.';
            } else {
                recapBlock.style.display = 'none';
            }
        }

        function numberFormat(n) {
            return Number(n).toLocaleString('fr-FR');
        }
    });
    </script>
</body>
</html>
