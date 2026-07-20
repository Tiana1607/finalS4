<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dépôt</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Mon Compte</span>
            <a href="<?= base_url('/client/logout') ?>" class="btn btn-outline-light btn-sm d-flex align-items-centerd-flex align-items-center">Deconnexion</a>
        </div>
    </nav>

    <div class="container page-shell">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <a href="<?= base_url('/client/dashboard') ?>" class="back-link">&larr; Retour</a>

                <div class="card shadow-sm money-card">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="page-kicker">Alimentation</div>
                            <h1 class="page-title">Dépôt</h1>
                            <p class="text-muted mb-1">Solde actuel</p>
                            <p class="solde-display mb-0"><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</p>
                        </div>

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

                        <form action="<?= base_url('/client/depot') ?>" method="post">
                            <div class="mb-3">
                                <label for="montant" class="form-label">Montant à déposer</label>
                                <input type="number" class="form-control" id="montant" name="montant"
                                       placeholder="Ex: 5000" min="1" step="any"
                                       value="<?= old('montant') ?>" required autofocus>
                                <div class="form-text">Saisissez le montant en Ariary.</div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">Déposer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>
