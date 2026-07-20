<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Préfixe</title>
    <link rel="icon" href="<?= base_url('assets/img/online-payment.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Opérateur</span>
            <a href="/admin/logout" class="btn btn-outline-light btn-sm d-flex align-items-center">Deconnexion</a>
        </div>
    </nav>

<div class="container page-shell">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <a href="/admin/prefixes" class="back-link">&larr; Retour aux préfixes</a>
            <div class="page-header">
                <div>
                    <div class="page-kicker">Réseau</div>
                    <h1 class="page-title">Ajouter un préfixe</h1>
                    <p class="page-subtitle">Associez un préfixe à votre opérateur ou à un opérateur externe.</p>
                </div>
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

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm money-card">
                <div class="card-body">
                    <form action="/admin/prefixes" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Préfixe</label>
                            <input type="text" name="prefixe" class="form-control"
                                   placeholder="Ex: 034" value="<?= old('prefixe') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Appartenance</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="appartenance"
                                       id="app_nous" value="nous" checked>
                                <label class="form-check-label" for="app_nous">Nous</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="appartenance"
                                       id="app_autre" value="autre">
                                <label class="form-check-label" for="app_autre">Autre opérateur</label>
                            </div>
                        </div>

                        <div class="mb-3" id="operateurSelectWrapper" style="display:none;">
                            <label class="form-label">Opérateur</label>
                            <select name="operateur_id" class="form-select">
                                <option value="">-- Choisir --</option>
                                <?php foreach ($operateurs as $op): ?>
                                    <option value="<?= esc($op['id']) ?>"><?= esc($op['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (empty($operateurs)): ?>
                                <div class="form-text text-danger">
                                    Aucun opérateur externe créé.
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">Ajouter</button>
                            <a href="/admin/prefixes" class="btn btn-outline-secondary flex-fill">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script>
document.querySelectorAll('input[name="appartenance"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('operateurSelectWrapper').style.display =
            this.value === 'autre' ? 'block' : 'none';
    });
});
</script>
</body>
</html>
