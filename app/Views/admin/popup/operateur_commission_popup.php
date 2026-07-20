<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier commission — <?= esc($commission['operateur_nom'] ?? '') ?></title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<div class="container page-shell">
    <div class="text-center mb-4">
        <div class="page-kicker">Commission externe</div>
        <h1 class="page-title">Modifier la commission</h1>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <script>setTimeout(() => window.close(), 1500);</script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card money-card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-bold">Opérateur</label>
                <input type="text" class="form-control" value="<?= esc($commission['operateur_nom'] ?? '') ?>" readonly>
            </div>

            <form action="/admin/commissions/modifier/<?= esc($commission['operateur_id']) ?>" method="post">
                <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label">Pourcentage de commission (%)</label>
            <input type="number" step="0.01" min="0" max="100" name="pourcentage" class="form-control"
                   value="<?= esc($commission['pourcentage']) ?>" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">Enregistrer</button>
            <button type="button" class="btn btn-secondary flex-fill" onclick="window.close()">Annuler</button>
        </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
