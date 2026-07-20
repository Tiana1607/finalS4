<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrait</title>
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
                <div class="mb-3">
                    <a href="<?= base_url('/client/dashboard') ?>" class="text-decoration-none">&larr; Retour</a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title text-center mb-3">Retrait</h5>
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

                        <form action="<?= base_url('/client/retrait') ?>" method="post">
                            <div class="mb-3">
                                <label for="montant" class="form-label">Montant à retirer (Ar)</label>
                                <input type="number" class="form-control" id="montant" name="montant"
                                       placeholder="Ex: 5000" min="1" step="any"
                                       value="<?= old('montant') ?>" required autofocus>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning">Retirer</button>
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
