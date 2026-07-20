<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Frais</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <h3 class="text-center mb-4">Gestion des Frais</h3>

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

            <!-- Onglets par type d'opération -->
            <ul class="nav nav-tabs mb-4">
                <?php foreach ($types as $type): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $typeId == $type['id'] ? 'active' : '' ?>"
                           href="<?= base_url('/admin/frais?type=' . $type['id']) ?>">
                            <?= esc(ucfirst($type['libelle'])) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Formulaire d'ajout -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Ajouter une tranche</h5>
                    <form action="/admin/frais/ajouter" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="type_operation_id" value="<?= esc($typeId) ?>">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Montant min (Ar)</label>
                                <input type="number" step="0.01" name="montant_min" class="form-control"
                                       value="<?= old('montant_min') ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Montant max (Ar)</label>
                                <input type="number" step="0.01" name="montant_max" class="form-control"
                                       value="<?= old('montant_max') ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Frais (Ar)</label>
                                <input type="number" step="0.01" name="frais" class="form-control"
                                       value="<?= old('frais') ?>" required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark w-100">Ajouter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tableau des tranches -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Tranches de frais — <?= esc(ucfirst($types[array_search($typeId, array_column($types, 'id'))]['libelle'] ?? '')) ?></h5>

                    <?php if (empty($tranches)): ?>
                        <p class="text-muted">Aucune tranche enregistrée pour ce type d'opération.</p>
                    <?php else: ?>
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Montant min (Ar)</th>
                                    <th>Montant max (Ar)</th>
                                    <th>Frais (Ar)</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tranches as $t): ?>
                                    <tr>
                                        <td><?= esc($t['id']) ?></td>
                                        <td><?= number_format($t['montant_min'], 0, ',', '.') ?></td>
                                        <td><?= number_format($t['montant_max'], 0, ',', '.') ?></td>
                                        <td><?= number_format($t['frais'], 0, ',', '.') ?></td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-warning btn-sm"
                                                    onclick="openEditPopup(<?= esc($t['id']) ?>)">
                                                Modifier
                                            </button>
                                            <form action="/admin/frais/supprimer/<?= esc($t['id']) ?>" method="post" class="d-inline"
                                                  onsubmit="return confirm('Supprimer cette tranche ?')">
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

<script>
function openEditPopup(id) {
    window.open(
        '/admin/frais/modifier/' + id,
        'Modifier la tranche',
        'width=600,height=500,scrollbars=yes,resizable=yes'
    );
}
</script>
</body>
</html>
