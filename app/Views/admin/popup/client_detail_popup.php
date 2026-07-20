<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails — <?= esc($client['telephone']) ?></title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h5 class="text-center mb-1">Fiche client</h5>
    <p class="text-center text-muted mb-4"><?= esc($client['telephone']) ?></p>

    <!-- Résumé -->
    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-2">
                    <small class="text-muted d-block">Solde</small>
                    <?php
                    $solde = (float) $client['solde'];
                    $cls   = $solde > 0 ? 'text-success' : ($solde < 0 ? 'text-danger' : 'text-muted');
                    ?>
                    <strong class="<?= $cls ?> fs-5"><?= number_format($solde, 0, ',', '.') ?> Ar</strong>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-2">
                    <small class="text-muted d-block">Transactions</small>
                    <strong class="fs-5"><?= count($transactions) ?></strong>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-2">
                    <small class="text-muted d-block">Inscrit le</small>
                    <strong class="fs-6"><?= esc($client['date_creation']) ?></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique -->
    <h6 class="mb-3">Historique des opérations</h6>

    <?php if (empty($transactions)): ?>
        <p class="text-muted text-center">Aucune transaction pour ce client.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Frais</th>
                        <th>Destinataire</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td><?= esc($t['id']) ?></td>
                            <td>
                                <?php
                                $libelle = ucfirst($t['type_libelle'] ?? '');
                                $badge   = match($t['type_operation_id']) {
                                    1      => 'primary',
                                    2      => 'warning',
                                    3      => 'success',
                                    default => 'secondary',
                                };
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= $libelle ?></span>
                            </td>
                            <td><?= number_format($t['montant'], 0, ',', '.') ?> Ar</td>
                            <td class="text-danger fw-bold"><?= number_format($t['frais'], 0, ',', '.') ?> Ar</td>
                            <td><?= esc($t['destinataire_tel'] ?? '—') ?></td>
                            <td><?= esc($t['date_operation']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="text-center mt-3">
        <button type="button" class="btn btn-secondary btn-sm" onclick="window.close()">Fermer</button>
    </div>
</div>
</body>
</html>
