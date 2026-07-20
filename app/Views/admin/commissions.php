<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commissions Externes</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Opérateur</span>
            <a href="/admin/logout" class="btn btn-outline-light btn-sm d-flex align-items-centerd-flex align-items-center">Deconnexion</a>
        </div>
    </nav>

<div class="container page-shell">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <a href="/admin/dashboard" class="back-link">&larr; Retour au tableau de bord</a>

            <div class="page-header">
                <div>
                    <div class="page-kicker">Interopérabilité</div>
                    <h1 class="page-title">Commissions Externes</h1>
                    <p class="page-subtitle">Configurez le pourcentage reversé aux opérateurs externes.</p>
                </div>
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

            <!-- ─── Formulaire d'ajout rapide ─── -->
            <div class="card shadow-sm money-card mb-4">
                <div class="card-body">
                    <h5 class="panel-title">Ajouter / Modifier une commission</h5>
                    <form action="/admin/commissions/ajouter" method="post" class="row g-3 align-items-end">
                        <?= csrf_field() ?>
                        <div class="col-md-6">
                            <label class="form-label">Opérateur externe</label>
                            <select name="operateur_id" class="form-select" required>
                                <option value="">-- Sélectionner un opérateur --</option>
                                <?php foreach ($operateurs as $op): ?>
                                    <option value="<?= esc($op['id']) ?>"><?= esc($op['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pourcentage (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="pourcentage" class="form-control" placeholder="Ex: 2.5" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ─── Liste des commissions ─── -->
            <div class="card shadow-sm section-card">
                <div class="card-body">
                    <h5 class="panel-title">Commissions configurées</h5>
                    <?php if (empty($commissions)): ?>
                        <p class="text-muted empty-state mb-0">Aucune commission externe configurée.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Opérateur</th>
                                        <th>Pourcentage</th>
                                        <th>Date création</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($commissions as $c): ?>
                                        <tr>
                                            <td><?= esc($c['id']) ?></td>
                                            <td><?= esc($c['operateur_nom']) ?></td>
                                            <td class="fw-bold"><?= number_format($c['pourcentage'], 2, ',', '.') ?> %</td>
                                            <td><?= esc($c['date_creation']) ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-warning" onclick="ouvrirPopup(<?= esc($c['operateur_id']) ?>)">
                                                    Modifier
                                                </button>
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
<script>
function ouvrirPopup(operateurId) {
    window.open('/admin/commissions/popup/' + operateurId, 'popup', 'width=500,height=450,scrollbars=yes');
}
</script>
</body>
</html>
