<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Frais</title>
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
                    <div class="page-kicker">Barèmes</div>
                    <h1 class="page-title">Gestion des Frais</h1>
                    <p class="page-subtitle">Paramétrez les tranches appliquées aux opérations Mobile Money.</p>
                </div>
                <a href="/admin/dashboard" class="btn btn-outline-secondary">Tableau de bord</a>
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
                    <h5 class="panel-title">Ajouter une tranche</h5>
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
                                <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tableau des tranches -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="panel-title">Tranches de frais — <?= esc(ucfirst($types[array_search($typeId, array_column($types, 'id'))]['libelle'] ?? '')) ?></h5>

                    <?php if (empty($tranches)): ?>
                        <p class="text-muted">Aucune tranche enregistrée pour ce type d'opération.</p>
                    <?php else: ?>
                        <div class="table-responsive">
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
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-id="<?= esc($t['id']) ?>"
                                                    data-type="<?= esc($t['type_operation_id']) ?>"
                                                    data-min="<?= esc($t['montant_min']) ?>"
                                                    data-max="<?= esc($t['montant_max']) ?>"
                                                    data-frais="<?= esc($t['frais']) ?>">
                                                Modifier
                                            </button>
                                            <form action="/admin/frais/supprimer/<?= esc($t['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        data-confirm="Supprimer cette tranche ?"
                                                        data-confirm-title="Supprimer la tranche"
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

<!-- Modale d'édition inline (remplace le popup) -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/admin/frais/modifier/" method="post" id="editModalForm">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la tranche</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type_operation_id" id="editType" value="">
                    <div class="mb-3">
                        <label class="form-label">Montant minimum (Ar)</label>
                        <input type="number" step="0.01" name="montant_min" id="editMin" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant maximum (Ar)</label>
                        <input type="number" step="0.01" name="montant_max" id="editMax" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Frais (Ar)</label>
                        <input type="number" step="0.01" name="frais" id="editFrais" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/script.js') ?>"></script>
<script>
document.getElementById('editModal').addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    const id = btn.getAttribute('data-id');
    const form = document.getElementById('editModalForm');
    form.action = '/admin/frais/modifier/' + id;
    document.getElementById('editType').value = btn.getAttribute('data-type');
    document.getElementById('editMin').value = btn.getAttribute('data-min');
    document.getElementById('editMax').value = btn.getAttribute('data-max');
    document.getElementById('editFrais').value = btn.getAttribute('data-frais');
});
</script>
</body>
</html>
