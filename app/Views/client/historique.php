<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Mon Compte</span>
            <a href="<?= base_url('/client/logout') ?>" class="btn btn-outline-light btn-sm d-flex align-items-centerd-flex align-items-center">Deconnexion</a>
        </div>
    </nav>

    <div class="container page-shell">

        <a href="<?= base_url('/client/dashboard') ?>" class="back-link">&larr; Retour</a>

        <div class="page-header">
            <div>
                <div class="page-kicker">Mouvements</div>
                <h1 class="page-title">Historique des transactions</h1>
                <p class="page-subtitle"><?= count($historique) ?> transaction(s) dans votre portefeuille.</p>
            </div>
        </div>

        <!-- Formulaire de filtres AJAX -->
        <!-- <div class="card shadow-sm mb-4 filtre-card">
            <div class="card-body">
                <form action="<?= base_url('/client/historique') ?>" method="get" id="filtreForm">
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
                                placeholder="Min" min="0" value="<?= $filtres['montant_min'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="montant_max" class="form-label">Montant max (Ar)</label>
                            <input type="number" class="form-control" id="montant_max" name="montant_max"
                                placeholder="Max" min="0" value="<?= $filtres['montant_max'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="tri_date" class="form-label">Tri par date</label>
                            <select class="form-select" id="tri_date" name="tri_date">
                                <option value="DESC" <?= ($triDate ?? 'DESC') === 'DESC' ? 'selected' : '' ?>>Plus récent
                                </option>
                                <option value="ASC" <?= ($triDate ?? '') === 'ASC' ? 'selected' : '' ?>>Plus ancien
                                </option>
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
        <div id="historiqueEmpty" class="alert alert-info <?= !empty($historique) ? 'd-none' : '' ?>">
            Aucune transaction trouvée.
        </div>

        <?php if (!empty($historique)): ?>
            <div class="card">
            <div class="card-body p-0">
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
                    <tbody id="historiqueBody">
                        <?php foreach ($historique as $tx): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($tx['date_operation'])) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match ($tx['type_libelle']) {
                                        'depot' => 'bg-success',
                                        'retrait' => 'bg-warning text-dark',
                                        'transfert' => 'bg-info',
                                        default => 'bg-secondary',
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($tx['type_libelle']) ?></span>
                                </td>
                                <td class="montant-badge"><?= number_format($tx['montant'], 0, ',', ' ') ?> Ar</td>
                                <td><?= number_format($tx['frais'], 0, ',', ' ') ?> Ar</td>
                                <td><?= $tx['destinataire_tel'] ? esc(formaterTelephone($tx['destinataire_tel'])) : '—' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            </div>
            </div>
        <?php else: ?>
            <div class="card">
            <div class="card-body p-0">
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
                    <tbody id="historiqueBody"></tbody>
                </table>
            </div>
            </div>
            </div>
        <?php endif; ?>

        <p class="text-muted small" id="historiqueCompteur"><?= count($historique) ?> transaction(s)</p>

    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
    <script src="<?= base_url('assets/js/filtre.js') ?>"></script>
</body>

</html>
