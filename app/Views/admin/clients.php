<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des Comptes Clients</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Opérateur</span>
            <a href="/admin/logout" class="btn btn-outline-light btn-sm">Déconnexion</a>
        </div>
    </nav>

<div class="container page-shell">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <a href="/admin/dashboard" class="back-link">&larr; Retour au tableau de bord</a>

            <div class="page-header">
                <div>
                    <div class="page-kicker">Comptes</div>
                    <h1 class="page-title">Situation des Comptes Clients</h1>
                    <p class="page-subtitle"><?= count($clients) ?> client(s) enregistré(s).</p>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <?php if (empty($clients)): ?>
                        <p class="text-muted text-center mb-0">Aucun client enregistré.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Téléphone</th>
                                        <th>Solde (Ar)</th>
                                        <th>Transactions</th>
                                        <th>Inscrit le</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clients as $c): ?>
                                        <tr>
                                            <td><?= esc($c['id']) ?></td>
                                            <td><?= esc(formaterTelephone($c['telephone'])) ?></td>
                                            <td>
                                                <?php
                                                $solde = (float) $c['solde'];
                                                $cls   = $solde > 0 ? 'text-success' : ($solde < 0 ? 'text-danger' : 'text-muted');
                                                ?>
                                                <span class="<?= $cls ?> fw-bold">
                                                    <?= number_format($solde, 0, ',', '.') ?> Ar
                                                </span>
                                            </td>
                                            <td><span class="badge bg-secondary"><?= (int) $c['nb_transactions'] ?></span></td>
                                            <td><?= esc($c['date_creation']) ?></td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#clientDetailModal"
                                                        data-id="<?= esc($c['id']) ?>"
                                                        data-telephone="<?= esc($c['telephone']) ?>"
                                                        data-solde="<?= esc($c['solde']) ?>"
                                                        data-nb="<?= esc($c['nb_transactions']) ?>"
                                                        data-date="<?= esc($c['date_creation']) ?>">
                                                    Détails
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modale détail client -->
<div class="modal fade" id="clientDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fiche client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" id="clientDetailBody">
                <div class="text-center py-4">
                    <span class="spinner-border spinner-border-sm"></span> Chargement...
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/script.js') ?>"></script>
<script>
document.getElementById('clientDetailModal').addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    const id = btn.getAttribute('data-id');
    const body = document.getElementById('clientDetailBody');

    body.innerHTML = '<div class="text-center py-4"><span class="spinner-border spinner-border-sm"></span> Chargement...</div>';

    fetch('/admin/clients/detail/' + id, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.text(); })
    .then(function(html) {
        body.innerHTML = html;
    })
    .catch(function() {
        body.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement.</div>';
    });
});
</script>
</body>
</html>
