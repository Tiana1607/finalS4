<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails — <?= esc(formaterTelephone($client['telephone'])) ?></title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<div class="container py-3">
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
                                    1      => 'bg-success',
                                    2      => 'bg-warning text-dark',
                                    3      => 'bg-info',
                                    default => 'bg-secondary',
                                };
                                ?>
                                <span class="badge <?= $badge ?>"><?= $libelle ?></span>
                            </td>
                            <td class="montant-badge"><?= number_format($t['montant'], 0, ',', '.') ?> Ar</td>
                            <td class="fw-bold"><?= number_format($t['frais'], 0, ',', '.') ?> Ar</td>
                            <td><?= esc($t['destinataire_tel'] ? formaterTelephone($t['destinataire_tel']) : '—') ?></td>
                            <td><?= esc($t['date_operation']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
