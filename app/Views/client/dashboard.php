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

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title">Bienvenue !</h5>
                        <p class="text-muted">Téléphone : <strong><?= esc(formaterTelephone($client['telephone'])) ?></strong></p>
                        <p class="solde-display"><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</p>
                        <p class="text-muted small">Solde actuel</p>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <a href="<?= base_url('/client/depot') ?>" class="btn btn-outline-success">Dépôt</a>
                    <a href="<?= base_url('/client/retrait') ?>" class="btn btn-outline-warning">Retrait</a>
                    <a href="<?= base_url('/client/transfert') ?>" class="btn btn-outline-info">Transfert</a>
                    <a href="<?= base_url('/client/historique') ?>" class="btn btn-outline-secondary">Historique</a>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>
