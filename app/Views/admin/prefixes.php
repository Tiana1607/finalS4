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
        <div class="col-md-10">

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

            <!-- Formulaire d'ajout -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Ajouter un préfixe</h5>
                    <form action="/admin/prefixes/ajouter" method="post" class="row g-2 align-items-end">
                        <?= csrf_field() ?>

                        <div class="col-md-4">
                            <label class="form-label">Préfixe</label>
                            <input type="text" name="prefixe" class="form-control" placeholder="Ex: 034" value="<?= old('prefixe') ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label d-block">Appartenance</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="appartenance" id="app_nous" value="nous" checked>
                                <label class="form-check-label" for="app_nous">Nous</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="appartenance" id="app_autre" value="autre">
                                <label class="form-check-label" for="app_autre">Autre opérateur</label>
                            </div>
                        </div>

                        <div class="col-md-3" id="operateurSelectWrapper" style="display:none;">
                            <label class="form-label">Opérateur</label>
                            <select name="operateur_id" class="form-select">
                                <option value="">-- Choisir --</option>
                                <?php foreach ($operateurs as $op): ?>
                                    <option value="<?= esc($op['id']) ?>"><?= esc($op['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (empty($operateurs)): ?>
                                <div class="form-text text-danger">
                                    Aucun opérateur externe créé.
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                        </div>
                    </form>
                </div>
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

            <!-- Préfixes des autres opérateurs -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Préfixes des autres opérateurs</h5>

                    <?php if (empty($autresPrefixes)): ?>
                        <p class="text-muted">Aucun préfixe externe enregistré.</p>
                    <?php else: ?>
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