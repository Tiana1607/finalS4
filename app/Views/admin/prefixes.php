<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Préfixes</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h3 class="text-center mb-4">Gestion des Préfixes</h3>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Ajouter un préfixe</h5>
                    <form action="/admin/prefixes/ajouter" method="post" class="d-flex gap-2">
                        <?= csrf_field() ?>
                        <input type="text" name="prefixe" class="form-control" placeholder="Ex: 034" value="<?= old('prefixe') ?>" required>
                        <button type="submit" class="btn btn-dark">Ajouter</button>
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
                                            <form action="/admin/prefixes/supprimer/<?= esc($p['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Supprimer ce préfixe ?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
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
</body>
</html>
