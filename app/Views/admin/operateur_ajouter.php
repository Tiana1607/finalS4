<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Opérateur</title>
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
        <div class="col-md-6">

            <a href="/admin/prefixes" class="text-decoration-none mb-3 d-inline-block">&larr; Retour à la liste des préfixes</a>

            <h4 class="mb-4">Créer un opérateur</h4>

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

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="/admin/operateurs/ajouter" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Nom de l'opérateur</label>
                            <input type="text" name="nom" class="form-control"
                                   placeholder="Ex: Airtel, Orange, Telma..." value="<?= old('nom') ?>" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">Créer</button>
                            <a href="/admin/prefixes" class="btn btn-outline-secondary flex-fill">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
