<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Préfixes</title>
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
        <div class="col-md-8">

            <h4 class="mb-4">Gestion des Préfixes</h4>

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

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Ajouter un préfixe</h5>
                    <form action="/admin/prefixes/ajouter" method="post" class="d-flex gap-2">
                        <?= csrf_field() ?>
                        <input type="text" name="prefixe" class="form-control" placeholder="Ex: 034" value="<?= old('prefixe') ?>" required>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Préfixes enregistrés</h5>

                    <?php if (empty($prefixes)): ?>
                        <p class="text-muted">Aucun préfixe enregistré.</p>
                    <?php else: ?>
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Préfixe</th>
                                    <th>Date de création</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($prefixes as $p): ?>
                                    <tr>
                                        <td><?= esc($p['id']) ?></td>
                                        <td><?= esc($p['prefixe']) ?></td>
                                        <td><?= esc($p['date_creation']) ?></td>
                                        <td class="text-end">
                                            <form action="/admin/prefixes/supprimer/<?= esc($p['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        data-confirm="Supprimer ce préfixe ?"
                                                        data-confirm-title="Supprimer le préfixe"
                                                        data-confirm-btn="Supprimer">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="/admin/dashboard" class="btn btn-outline-secondary">Retour au tableau de bord</a>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>
