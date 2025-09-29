<?php
require_once '../php/BD connexion/connexion.php';

// Récupérer l'ID du bon depuis l'URL
$bon_id = $_GET['id'] ?? null;
if (!$bon_id) {
    die("⚠️ Bon non trouvé");
}

// Récupérer les informations du bon
$stmt = $pdo->prepare("SELECT * FROM bon_sortie WHERE id = :id");
$stmt->execute([':id' => $bon_id]);
$bon = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$bon) {
    die("⚠️ Bon non trouvé");
}

// Récupérer la liste des matériels pour le select
$materiels = $pdo->query("SELECT id, nom FROM materiel ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les inventaires déjà enregistrés pour ce bon
$stmt = $pdo->prepare("
    SELECT im.id, m.nom AS materiel, im.quantite, im.unite
    FROM inventaire_materiel im
    JOIN materiel m ON m.id = im.materiel
    WHERE im.bon_id = :bon_id
");
$stmt->execute([':bon_id' => $bon_id]);
$inventaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaire du bon <?= htmlspecialchars($bon['num_bon']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/global.css?v=1.2">
</head>

<body class="bg-light">

    <?php include './components/header.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Inventaire du bon <?= htmlspecialchars($bon['num_bon']) ?></h2>

        <div class="mb-3 text-end">
            <a href="Bons.php" class="btn btn-primary">⬅ Retour aux bons</a>
        </div>

        <form method="post" action="../php/save_inventaire.php">
            <input type="hidden" name="bon_id" value="<?= $bon['id'] ?>">

            <table class="table table-bordered text-center align-middle" id="inventaireTable">
                <thead class="table-dark">
                    <tr>
                        <th>Matériel</th>
                        <th>quantite</th>
                        <th>unite</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="materiel[]" class="form-select materiel-select" required>
                                <option value="">-- Sélectionner --</option>
                                <?php foreach ($materiels as $m): ?>
                                    <option value="<?= $m['id'] ?>" data-unite="<?= htmlspecialchars($m['unite'] ?? '') ?>">
                                        <?= htmlspecialchars($m['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="quantite[]" class="form-control" min="1" required></td>
                        <td>
                            <select name="unite[]" class="form-select" required>
                                <option value="">Sélectionner...</option>
                                <option value="pièce">Pièce</option>
                                <option value="boîte">Boîte</option>
                                <option value="kg">Kg</option>
                                <option value="m">Mètre</option>
                                <option value="litre">Litre</option>
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="supprimerLigne(this)">Supprimer</button>
                        </td>
                    </tr>

                </tbody>
            </table>

            <div class="text-center mb-3">
                <button type="button" class="btn btn-secondary" onclick="ajouterLigne()">+ Ajouter une ligne</button>
                <button type="submit" class="btn btn-success px-4">Enregistrer</button>
            </div>
        </form>
    </div>
    <footer class="text-center py-3">
        <p class="text-muted mb-0">© 2025 Senelec - Gestion Sécurisation</p>
    </footer>

    <script src="../js/inventaire.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>