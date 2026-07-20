<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-sm auth-card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="<?= base_url('assets/img/online-payment.png') ?>" alt="Logo" class="auth-logo">
                    <div class="page-kicker">Mobile Money</div>
                    <h1 class="page-title">Connexion Client</h1>
                    <p class="text-muted small mb-0">Accédez à votre portefeuille par numéro de téléphone.</p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/client/login') ?>" method="post">
                    <div class="mb-3">
                        <label for="tel" class="form-label">Numéro de téléphone</label>
                        <input type="tel" class="form-control" id="tel" name="tel"
                               placeholder="032 12 123 12"
                               pattern="[\d\s]{10,15}" inputmode="numeric"
                               value="<?= old('tel') ?>" required autofocus>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary text-center">Se connecter</button>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?= base_url('/admin/login') ?>">Opérateur ?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>
