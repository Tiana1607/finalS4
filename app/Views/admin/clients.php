<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Situation des Comptes Clients</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <h3 class="text-center mb-4">Situation des Comptes Clients</h3>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (empty($clients)): ?>
                        <p class="text-muted text-center mb-0">Aucun client enregistré.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Téléphone</th>
                                        <th>Solde (Ar)</th>
                                        <th>Transactions</th>
                                        <th>Inscrit le</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clients as $c): ?>
                                        <tr>
                                            <td><?= esc($c['id']) ?></td>
                                            <td><?= esc($c['telephone']) ?></td>
                                            <td>
                                                <?php
                                                $solde = (float) $c['solde'];
                                                $cls   = $solde > 0 ? 'text-success' : ($solde < 0 ? 'text-danger' : 'text-muted');
                                                ?>
                                                <span class="<?= $cls ?> fw-bold">
                                                    <?= number_format($solde, 0, ',', '.') ?> Ar
                                                </span>
                                            </td>
                                            <td><span class="badge bg-secondary"><?= (int) $c['nb_transactions'] ?></span></td>
                                            <td><?= esc($c['date_creation']) ?></td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-info btn-sm"
                                                        onclick="openDetailPopup(<?= esc($c['id']) ?>)">
                                                    Détails
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

            <div class="text-center mt-3">
                <a href="/admin/dashboard" class="btn btn-outline-secondary">Retour au tableau de bord</a>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script>
function openDetailPopup(id) {
    window.open(
        '/admin/clients/detail/' + id,
        'Détails du client',
        'width=750,height=600,scrollbars=yes,resizable=yes'
    );
}
</script>
</body>
</html>
