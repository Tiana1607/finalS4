<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montants à envoyer</title>
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

            <div class="mb-3">
                <a href="/admin/dashboard" class="text-decoration-none">&larr; Retour au tableau de bord</a>
            </div>

            <h4 class="mb-4">Montants à envoyer aux opérateurs</h4>

            <!-- ─── Carte résumé ─── -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-1">Total à reverser</h6>
                            <h3 class="text-danger mb-0"><?= number_format($totalGeneral, 0, ',', '.') ?> Ar</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-1">Opérateurs concernés</h6>
                            <h3 class="mb-0"><?= count($montants) ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ─── Tableau des montants ─── -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">Détail par opérateur</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($montants)): ?>
                        <p class="text-muted mb-0">Aucun montant à envoyer pour le moment.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Opérateur</th>
                                        <th>Nombre de transferts</th>
                                        <th>Montant total (Ar)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($montants as $i => $m): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td class="fw-bold"><?= esc($m['operateur_nom']) ?></td>
                                            <td><?= (int) $m['nb_transferts'] ?></td>
                                            <td class="text-danger fw-bold"><?= number_format($m['montant_total'], 0, ',', '.') ?> Ar</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Total général :</th>
                                        <th class="text-danger"><?= number_format($totalGeneral, 0, ',', '.') ?> Ar</th>
                                    </tr>
                                </tfoot>
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
