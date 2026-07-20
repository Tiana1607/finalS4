<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-secondary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Mon Compte</span>
            <a href="<?= base_url('/client/logout') ?>" class="btn btn-outline-light btn-sm">Déconnexion</a>
        </div>
    </nav>

    <div class="container mt-4">

        <div class="mb-3">
            <a href="<?= base_url('/client/dashboard') ?>" class="text-decoration-none">&larr; Retour au tableau de bord</a>
        </div>

        <h5 class="mb-3">Historique des transactions</h5>

        <!-- Formulaire de filtres -->
        <!-- <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="<?= base_url('/client/historique') ?>" method="post">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="type_operation" class="form-label">Type d'opération</label>
                            <select class="form-select" id="type_operation" name="type_operation">
                                <option value="">Tous</option>
                                <?php foreach ($typesOperation as $type): ?>
                                    <option value="<?= $type['id'] ?>" <?= ($filtres['type_operation'] ?? '') == $type['id'] ? 'selected' : '' ?>>
                                        <?= ucfirst($type['libelle']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="date_debut" class="form-label">Date début</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut"
                                   value="<?= $filtres['date_debut'] ?? '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="date_fin" class="form-label">Date fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin"
                                   value="<?= $filtres['date_fin'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="montant_min" class="form-label">Montant min (Ar)</label>
                            <input type="number" class="form-control" id="montant_min" name="montant_min"
                                   placeholder="Min" min="0"
                                   value="<?= $filtres['montant_min'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="montant_max" class="form-label">Montant max (Ar)</label>
                            <input type="number" class="form-control" id="montant_max" name="montant_max"
                                   placeholder="Max" min="0"
                                   value="<?= $filtres['montant_max'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="tri_date" class="form-label">Tri par date</label>
                            <select class="form-select" id="tri_date" name="tri_date">
                                <option value="DESC" <?= ($triDate ?? 'DESC') === 'DESC' ? 'selected' : '' ?>>Plus récent d'abord</option>
                                <option value="ASC" <?= ($triDate ?? '') === 'ASC' ? 'selected' : '' ?>>Plus ancien d'abord</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> -->

        <!-- Liste des transactions -->
        <?php if (empty($historique)): ?>
            <div class="alert alert-info">Aucune transaction trouvée.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Frais</th>
                            <th>Destinataire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historique as $tx): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($tx['date_operation'])) ?></td>
                                <td><span class="badge bg-secondary"><?= ucfirst($tx['type_libelle']) ?></span></td>
                                <td><?= number_format($tx['montant'], 0, ',', ' ') ?> Ar</td>
                                <td><?= number_format($tx['frais'], 0, ',', ' ') ?> Ar</td>
                                <td><?= $tx['destinataire_tel'] ? esc($tx['destinataire_tel']) : '—' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p class="text-muted small">Total : <?= count($historique) ?> transaction(s)</p>
        <?php endif; ?>

    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>
