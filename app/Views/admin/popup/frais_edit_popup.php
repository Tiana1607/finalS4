<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la tranche #<?= esc($tranche['id']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h5 class="text-center mb-4">Modifier la tranche #<?= esc($tranche['id']) ?></h5>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <script>
            if (window.opener) {
                window.opener.location.reload();
                window.close();
            }
        </script>
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

    <form action="/admin/frais/modifier/<?= esc($tranche['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label">Type d'opération</label>
            <select name="type_operation_id" class="form-select" required>
                <?php foreach ($types as $type): ?>
                    <option value="<?= esc($type['id']) ?>"
                        <?= $type['id'] == $tranche['type_operation_id'] ? 'selected' : '' ?>>
                        <?= esc(ucfirst($type['libelle'])) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Montant minimum (Ar)</label>
            <input type="number" step="0.01" name="montant_min" class="form-control"
                   value="<?= esc($tranche['montant_min']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Montant maximum (Ar)</label>
            <input type="number" step="0.01" name="montant_max" class="form-control"
                   value="<?= esc($tranche['montant_max']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Frais (Ar)</label>
            <input type="number" step="0.01" name="frais" class="form-control"
                   value="<?= esc($tranche['frais']) ?>" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-dark flex-fill">Enregistrer</button>
            <button type="button" class="btn btn-secondary flex-fill" onclick="window.close()">Annuler</button>
        </div>
    </form>
</div>
</body>
</html>
