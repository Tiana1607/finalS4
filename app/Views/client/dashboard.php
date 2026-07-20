<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-secondary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Mon Compte</span>
            <a href="<?= base_url('/client/logout') ?>" class="btn btn-outline-light btn-sm">Déconnexion</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title">Bienvenue !</h5>
                        <p class="text-muted">Téléphone : <strong><?= esc($client['telephone']) ?></strong></p>
                        <p class="fs-4 fw-bold text-success">Solde : <?= number_format($client['solde'], 0, ',', ' ') ?> Ar</p>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <a href="<?= base_url('/client/depot') ?>" class="btn btn-outline-success">Effectuer un dépôt</a>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>
