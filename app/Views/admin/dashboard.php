<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord — Opérateur</title>
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

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <h4 class="mb-4">Tableau de bord</h4>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            <?php endif; ?>

            <!-- ─── Stats ─── -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Clients</h6>
                            <h3 class="mb-0"><?= $nbClients ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Transactions</h6>
                            <h3 class="mb-0"><?= $nbTransactions ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Préfixes</h6>
                            <h3 class="mb-0"><?= $nbPrefixes ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Gains totaux</h6>
                            <h3 class="solde-display mb-0"><?= number_format($totalGains, 0, ',', '.') ?> Ar</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ─── Navigation ─── -->
            <div class="row g-4">
                <div class="col-md-4">
                    <a href="/admin/prefixes" class="text-decoration-none">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center py-4">
                                <h4 class="mb-2">Préfixes</h4>
                                <p class="text-muted mb-0">Gérer les préfixes de numéros autorisés</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/frais" class="text-decoration-none">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center py-4">
                                <h4 class="mb-2">Frais</h4>
                                <p class="text-muted mb-0">Gérer les barèmes de frais par tranche</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/gains" class="text-decoration-none">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center py-4">
                                <h4 class="mb-2">Gains</h4>
                                <p class="text-muted mb-0">Situation des gains retraits / transferts</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/clients" class="text-decoration-none">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center py-4">
                                <h4 class="mb-2">Clients</h4>
                                <p class="text-muted mb-0">Situation des comptes clients</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/commissions" class="text-decoration-none">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center py-4">
                                <h4 class="mb-2">Commissions</h4>
                                <p class="text-muted mb-0">Configurer les commissions externes (%)</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/montants" class="text-decoration-none">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center py-4">
                                <h4 class="mb-2">Montants à envoyer</h4>
                                <p class="text-muted mb-0">Montants à reverser aux opérateurs externes</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>
