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
            <div class="col-md-5">
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

                        <form action="<?= base_url('/client/transfert') ?>" method="post">
                            <div class="mb-3">
                                <label for="destinataire" class="form-label">Numéro du destinataire</label>
                                <input type="tel" class="form-control" id="destinataire" name="destinataire"
                                       placeholder="034 12 123 12"
                                       pattern="[\d\s]{10,15}" inputmode="numeric"
                                       value="<?= old('destinataire') ?>" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="montant" class="form-label">Montant à transférer (Ar)</label>
                                <input type="number" class="form-control" id="montant" name="montant"
                                       placeholder="Ex: 5000" min="1" step="any"
                                       value="<?= old('montant') ?>" required>
                            </div>
                            <div class="mb-3">
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
</body>
</html>
