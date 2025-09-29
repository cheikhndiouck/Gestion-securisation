<?php
require_once '../php/BD connexion/connexion.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Récupérer tous les bons
$bons = $pdo->query("SELECT * FROM bon_sortie ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Suppression d’un bon et de ses inventaires
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $pdo->prepare("DELETE FROM inventaire_materiel WHERE bon_id = ?")->execute([$delete_id]);
    $pdo->prepare("DELETE FROM bon_sortie WHERE id = ?")->execute([$delete_id]);
    header("Location: Bons.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bons de sortie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/global.css?v=1.2?v=1.2">
    <link rel="stylesheet" href="../css/bons.css">
</head>

<body class="bg-light">
    <?php include './components/header.php'; ?>
    <main class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-center mb-4">Bons de sortie</h1>
            <div>
                <button class="btn btn-primary bg-senelec-blue" data-bs-toggle="modal" data-bs-target="#formModal">➕ Nouveau bon</button>
                <a href="../php/export_bons.php" class="btn btn-outline-success">📥 Exporter en Excel</a>
            </div>
        </div>

        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Numéro Bon</th>
                    <th>Date de sortie</th>
                    <th>GIE</th>
                    <th>Zone</th>
                    <th>Origine</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bons)): ?>
                    <?php foreach ($bons as $bon): ?>
                        <tr>
                            <td><?= htmlspecialchars($bon['num_bon']) ?></td>
                            <td><?= htmlspecialchars($bon['date_bon']) ?></td>
                            <td><?= htmlspecialchars($bon['gie']) ?></td>
                            <td><?= htmlspecialchars($bon['zone']) ?></td>
                            <td><?= htmlspecialchars($bon['origine']) ?></td>
                            <td>
                                <a href="inventaire_view.php?id=<?= $bon['id'] ?>" class="btn btn-sm btn-primary mb-1">Inventaire</a>
                                <a href="Bons.php?delete_id=<?= $bon['id'] ?>" class="btn btn-sm btn-danger mb-1"
                                    onclick="return confirm('Voulez-vous vraiment supprimer ce bon et ses inventaires ?');">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Aucun bon trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>

        <!-- Modal pour créer un bon -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="p-3 mb-2 text-light bg-senelec-blue d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">Créer un bon de sortie</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form method="post" action="../php/save_bon.php">
                            <div class="mb-3">
                                <label>Numéro du bon :</label>
                                <?php
                                // Générer un numéro unique format YYYY-MM-000001
                                $prefix = date('Y-m');
                                $stmt = $pdo->prepare("SELECT num_bon FROM bon_sortie WHERE num_bon LIKE ? ORDER BY id DESC LIMIT 1");
                                $stmt->execute([$prefix . '-%']);
                                $last = $stmt->fetchColumn();
                                $newCount = $last ? str_pad((int)substr($last, -6) + 1, 6, '0', STR_PAD_LEFT) : '000001';
                                $numeroBon = $prefix . '-' . $newCount;
                                ?>
                                <input type="text" name="numBon" class="form-control" value="<?= $numeroBon ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label>Date :</label>
                                <input type="date" name="dateBon" class="form-control" value="<?= date('Y-m-d') ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label>Nom du GIE :</label>
                                <input type="text" name="gie" class="form-control" placeholder="Nom du GIE..." required>
                            </div>
                            <div class="mb-3">
                                <label>Zone :</label>
                                <select name="zone" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="zone 1">zone 1</option>
                                    <option value="zone 2">zone 2</option>
                                    <option value="zone 3">zone 3</option>
                                    <option value="zone 4">zone 4</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Origine :</label>
                                <select name="origine" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="DD">DD</option>
                                    <option value="PNT">PNT</option>
                                    <option value="Projet">Projet</option>
                                    <option value="Ciblage">Ciblage</option>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">Enregistrer</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <footer></footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </main>
    <footer class="text-center py-3">
        <p class="text-muted mb-0">© 2025 Senelec - Gestion Sécurisation</p>
    </footer>
</body>

</html>