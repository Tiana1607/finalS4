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
            <a href="/admin/logout" class="btn btn-outline-light btn-sm d-flex align-items-centerd-flex align-items-center">Deconnexion</a>
        </div>
    </nav>

<div class="container page-shell">
    <div class="row justify-content-center">
        <div class="col-xl-10">

            <div class="page-header">
                <div>
                    <div class="page-kicker">Pilotage opérateur</div>
                    <h1 class="page-title">Tableau de bord</h1>
                    <p class="page-subtitle">Vue rapide des comptes, opérations et revenus Mobile Money.</p>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            <?php endif; ?>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card kpi-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="kpi-icon">CL</span>
                                <span class="badge bg-success">Actif</span>
                            </div>
                            <h6 class="text-muted mb-1">Clients</h6>
                            <div class="kpi-value"><?= $nbClients ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card kpi-card kpi-info h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="kpi-icon">↔</span>
                                <span class="badge bg-info">Flux</span>
                            </div>
                            <h6 class="text-muted mb-1">Transactions</h6>
                            <div class="kpi-value"><?= $nbTransactions ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card kpi-card kpi-muted h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="kpi-icon">#</span>
                                <span class="badge bg-secondary">Réseau</span>
                            </div>
                            <h6 class="text-muted mb-1">Préfixes</h6>
                            <div class="kpi-value"><?= $nbPrefixes ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card kpi-card kpi-warning h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="kpi-icon">Ar</span>
                                <span class="badge bg-warning text-dark">Revenus</span>
                            </div>
                            <h6 class="text-muted mb-1">Gains totaux</h6>
                            <div class="kpi-value"><?= number_format($totalGains, 0, ',', '.') ?> Ar</div>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="panel-title">Espaces de gestion</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <a href="/admin/prefixes" class="action-card text-decoration-none">
                        <div class="card h-100">
                            <div class="card-body">
                                <span class="action-icon">#</span>
                                <div>
                                <h4 class="mb-2">Préfixes</h4>
                                <p class="text-muted mb-0">Gérer les préfixes de numéros autorisés</p>
                                </div>
                                <span class="action-arrow">→</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/frais" class="action-card text-decoration-none">
                        <div class="card h-100">
                            <div class="card-body">
                                <span class="action-icon" style="background: var(--color-retrait); color: var(--color-text)">%</span>
                                <div>
                                <h4 class="mb-2">Frais</h4>
                                <p class="text-muted mb-0">Gérer les barèmes de frais par tranche</p>
                                </div>
                                <span class="action-arrow">→</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/gains" class="action-card text-decoration-none">
                        <div class="card h-100">
                            <div class="card-body">
                                <span class="action-icon" style="background: var(--color-transfert)">Ar</span>
                                <div>
                                <h4 class="mb-2">Gains</h4>
                                <p class="text-muted mb-0">Situation des gains retraits / transferts</p>
                                </div>
                                <span class="action-arrow">→</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/clients" class="action-card text-decoration-none">
                        <div class="card h-100">
                            <div class="card-body">
                                <span class="action-icon" style="background: var(--color-historique)">CL</span>
                                <div>
                                <h4 class="mb-2">Clients</h4>
                                <p class="text-muted mb-0">Situation des comptes clients</p>
                                </div>
                                <span class="action-arrow">→</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/commissions" class="action-card text-decoration-none">
                        <div class="card h-100">
                            <div class="card-body">
                                <span class="action-icon" style="background: var(--color-danger)">%</span>
                                <div>
                                <h4 class="mb-2">Commissions</h4>
                                <p class="text-muted mb-0">Configurer les commissions externes (%)</p>
                                </div>
                                <span class="action-arrow">→</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/montants" class="action-card text-decoration-none">
                        <div class="card h-100">
                            <div class="card-body">
                                <span class="action-icon" style="background: var(--color-warning); color: var(--color-text)">Ar</span>
                                <div>
                                <h4 class="mb-2">Montants à envoyer</h4>
                                <p class="text-muted mb-0">Montants à reverser aux opérateurs externes</p>
                                </div>
                                <span class="action-arrow">→</span>
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
