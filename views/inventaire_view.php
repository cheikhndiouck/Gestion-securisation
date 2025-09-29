<?php
require_once '../php/BD connexion/connexion.php';
require_once '../php/inv_view.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaire du bon <?= htmlspecialchars($bon['num_bon']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/global.css">
</head>

<body class="bg-light">

    <?php include './components/header.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Inventaire du bon <?= htmlspecialchars($bon['num_bon']) ?></h2>

        <div class="mb-3 text-end">
            <a href="Bons.php" class="btn btn-primary">⬅ Retour aux bons</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Matériel</th>
                        <th>Quantité</th>
                        <th>Unité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($inventaires)): ?>
                        <?php foreach ($inventaires as $i => $inv): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($inv['materiel']) ?></td>
                                <td><?= htmlspecialchars($inv['quantite']) ?></td>
                                <td><?= htmlspecialchars($inv['unite']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Aucun inventaire trouvé pour ce bon.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer class="text-center py-3">
        <p class="text-muted mb-0">© 2025 Senelec - Gestion Sécurisation</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>