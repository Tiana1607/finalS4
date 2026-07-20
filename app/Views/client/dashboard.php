<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
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

    <div class="container page-shell">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="hero-panel mb-4">
                    <div class="row align-items-end g-3">
                        <div class="col-md-8">
                            <div class="hero-label">Portefeuille Mobile Money</div>
                            <div class="hero-balance"><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</div>
                            <p class="mb-0 opacity-75">Compte <?= esc(formaterTelephone($client['telephone'])) ?></p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge rounded-pill bg-light text-dark px-3 py-2">Solde disponible</span>
                        </div>
                    </div>
                </div>

                <div class="page-header">
                    <div>
                        <div class="page-kicker">Actions rapides</div>
                        <h1 class="page-title">Que voulez-vous faire ?</h1>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="<?= base_url('/client/depot') ?>" class="action-card text-decoration-none">
                            <div class="card h-100">
                                <div class="card-body">
                                    <span class="action-icon" style="background: var(--color-depot)">+</span>
                                    <div>
                                        <h5 class="card-title mb-1">Dépôt</h5>
                                        <p class="text-muted mb-0">Alimenter le portefeuille</p>
                                    </div>
                                    <span class="action-arrow">→</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= base_url('/client/retrait') ?>" class="action-card text-decoration-none">
                            <div class="card h-100">
                                <div class="card-body">
                                    <span class="action-icon" style="background: var(--color-retrait); color: var(--color-text)">−</span>
                                    <div>
                                        <h5 class="card-title mb-1">Retrait</h5>
                                        <p class="text-muted mb-0">Retirer de l'argent</p>
                                    </div>
                                    <span class="action-arrow">→</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= base_url('/client/transfert') ?>" class="action-card text-decoration-none">
                            <div class="card h-100">
                                <div class="card-body">
                                    <span class="action-icon" style="background: var(--color-transfert)">↗</span>
                                    <div>
                                        <h5 class="card-title mb-1">Transfert</h5>
                                        <p class="text-muted mb-0">Envoyer vers un ou plusieurs numéros</p>
                                    </div>
                                    <span class="action-arrow">→</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= base_url('/client/historique') ?>" class="action-card text-decoration-none">
                            <div class="card h-100">
                                <div class="card-body">
                                    <span class="action-icon" style="background: var(--color-historique)">≡</span>
                                    <div>
                                        <h5 class="card-title mb-1">Historique</h5>
                                        <p class="text-muted mb-0">Consulter les opérations</p>
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
