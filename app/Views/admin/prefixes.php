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

<div class="container page-shell">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="page-header">
                <div>
                    <div class="page-kicker">Réseau</div>
                    <h1 class="page-title">Gestion des Préfixes</h1>
                    <p class="page-subtitle">Contrôlez les numéros autorisés et les opérateurs externes.</p>
                </div>
                <a href="/admin/prefixes/ajouter" class="btn btn-primary">+ Ajouter un préfixe</a>
            </div>

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

            <!-- Boutons -->
            <div class="mb-4 d-flex gap-2">
                <a href="/admin/prefixes/ajouter" class="btn btn-primary">
                    + Ajouter un préfixe
                </a>
                <a href="/admin/operateurs/ajouter" class="btn btn-outline-primary">
                    + Créer un opérateur
                </a>
            </div>

            <!-- Nos préfixes -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        Nos préfixes <span class="text-muted fw-normal">(<?= esc($operateurNous['nom'] ?? 'Nous') ?>)</span>
                    </h5>

                    <?php if (empty($nosPrefixes)): ?>
                        <p class="text-muted">Aucun préfixe enregistré.</p>
                    <?php else: ?>
                        <div class="table-responsive">
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
                                <?php foreach ($nosPrefixes as $p): ?>
                                    <tr>
                                        <td><?= esc($p['id']) ?></td>
                                        <td><?= esc($p['prefixe']) ?></td>
                                        <td><?= esc($p['date_creation']) ?></td>
                                        <td class="text-end">
                                            <form action="/admin/prefixes/delete/<?= esc($p['id']) ?>" method="post" class="d-inline">
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
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Préfixes des autres opérateurs -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Préfixes des autres opérateurs</h5>

                    <?php if (empty($autresPrefixes)): ?>
                        <p class="text-muted">Aucun préfixe externe enregistré.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-secondary">
                                <tr>
                                    <th>ID</th>
                                    <th>Opérateur</th>
                                    <th>Préfixe</th>
                                    <th>Date de création</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($autresPrefixes as $p): ?>
                                    <tr>
                                        <td><?= esc($p['id']) ?></td>
                                        <td><?= esc($p['operateur_nom']) ?></td>
                                        <td><?= esc($p['prefixe']) ?></td>
                                        <td><?= esc($p['date_creation']) ?></td>
                                        <td class="text-end">
                                            <form action="/admin/prefixes/delete/<?= esc($p['id']) ?>" method="post" class="d-inline">
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
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>
