<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Situation des Gains</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <h3 class="text-center mb-4">Situation des Gains</h3>

            <!-- ─── Cartes résumé ─── -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-1">Gains — Retraits</h6>
                            <h3 class="text-primary mb-1"><?= number_format($totalRetrait, 0, ',', '.') ?> Ar</h3>
                            <small class="text-muted"><?= $nbRetrait ?> transaction<?= $nbRetrait > 1 ? 's' : '' ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-1">Gains — Transferts</h6>
                            <h3 class="text-success mb-1"><?= number_format($totalTransfert, 0, ',', '.') ?> Ar</h3>
                            <small class="text-muted"><?= $nbTransfert ?> transaction<?= $nbTransfert > 1 ? 's' : '' ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-1">Gains Totaux</h6>
                            <h3 class="text-dark mb-1"><?= number_format($totalGeneral, 0, ',', '.') ?> Ar</h3>
                            <small class="text-muted"><?= $nbRetrait + $nbTransfert ?> transaction<?= ($nbRetrait + $nbTransfert) > 1 ? 's' : '' ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ─── Graphiques ─── -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title text-center">Gains par type (barres)</h6>
                            <canvas id="barChart" height="220"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title text-center">Répartition des gains (camembert)</h6>
                            <canvas id="pieChart" height="220"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ─── Liste des retraits ─── -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Retraits effectués</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($retraits)): ?>
                        <p class="text-muted mb-0">Aucun retrait enregistré.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Téléphone client</th>
                                        <th>Montant (Ar)</th>
                                        <th>Frais (Ar)</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($retraits as $r): ?>
                                        <tr>
                                            <td><?= esc($r['id']) ?></td>
                                            <td><?= esc($r['client_tel']) ?></td>
                                            <td><?= number_format($r['montant'], 0, ',', '.') ?></td>
                                            <td class="text-primary fw-bold"><?= number_format($r['frais'], 0, ',', '.') ?></td>
                                            <td><?= esc($r['date_operation']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ─── Liste des transferts ─── -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Transferts effectués</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($transferts)): ?>
                        <p class="text-muted mb-0">Aucun transfert enregistré.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Émetteur</th>
                                        <th>Destinataire</th>
                                        <th>Montant (Ar)</th>
                                        <th>Frais (Ar)</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transferts as $t): ?>
                                        <tr>
                                            <td><?= esc($t['id']) ?></td>
                                            <td><?= esc($t['client_tel']) ?></td>
                                            <td><?= esc($t['destinataire_tel']) ?></td>
                                            <td><?= number_format($t['montant'], 0, ',', '.') ?></td>
                                            <td class="text-success fw-bold"><?= number_format($t['frais'], 0, ',', '.') ?></td>
                                            <td><?= esc($t['date_operation']) ?></td>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
const labels = ['Retraits', 'Transferts'];
const data   = [<?= json_encode($totalRetrait) ?>, <?= json_encode($totalTransfert) ?>];
const colors = ['#0d6efd', '#198754'];

// Bar chart
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Gains (Ar)',
            data: data,
            backgroundColor: colors
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Pie chart
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: colors
        }]
    },
    options: {
        responsive: true
    }
});
</script>
</body>
</html>
