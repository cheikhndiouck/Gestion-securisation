<?php
session_start();
require_once '../php/BD connexion/connexion.php';

// Vérifier si un id de facture est passé via GET
$facture_id = $_GET['id'] ?? null;

if (!$facture_id) {
    $error_message = "⚠️ Aucune facture sélectionnée.";
} else {
    try {
        // Récupérer la facture
        $stmt = $pdo->prepare("SELECT * FROM facture WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $facture_id]);
        $facture = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$facture) {
            $error_message = "⚠️ Facture introuvable.";
        } else {
            // Récupérer les détails matériels
            $stmt2 = $pdo->prepare("
                SELECT f.*, m.nom, m.unite 
                FROM facture_details f
                JOIN materiel m ON f.materiel_id = m.id
                WHERE f.facture_id = :id
            ");
            $stmt2->execute([':id' => $facture_id]);
            $details = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            // Récupérer les fiches associées
            $stmt3 = $pdo->prepare("
                SELECT fr.* 
                FROM fiche_reception fr
                JOIN facture_reception frf ON fr.id = frf.fiche_id
                WHERE frf.facture_id = :facture_id
            ");
            $stmt3->execute([':facture_id' => $facture_id]);
            $fiches = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (Exception $e) {
        $error_message = "⚠️ Erreur lors de la récupération : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Facture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container mt-5">

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message) ?>
        </div>
        <a href="liste_factures.php" class="btn btn-primary mt-3">
            <i class="bi bi-arrow-left"></i> Retour à la liste des factures
        </a>
    <?php else: ?>
    
        <!-- Titre + Boutons d’action -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">Facture <?= htmlspecialchars($facture['numero_facture']) ?></h2>
            <div class="btn-group">
                <button onclick="window.print()" class="btn btn-info">
                    <i class="bi bi-printer"></i> Imprimer
                </button>
                <a href="../php/export_facturation.php?id=<?= $facture_id ?>" class="btn btn-secondary">
                    <i class="bi bi-file-pdf"></i> PDF
                </a>
                <a href="modifier_facture.php?id=<?= $facture_id ?>" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                <a href="../php/supprimer_facture.php?id=<?= $facture_id ?>" class="btn btn-danger" 
                   onclick="return confirm('Voulez-vous vraiment supprimer cette facture ?');">
                    <i class="bi bi-trash"></i> Supprimer
                </a>
                <a href="liste_factures.php" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <!-- Informations Facture -->
        <div class="card mb-4 shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Informations</h5>
                <!-- Statut de facture -->
                <span class="badge bg-<?= ($facture['statut'] ?? 'En attente') === 'Validée' ? 'success' : 'warning' ?>">
                    <?= htmlspecialchars($facture['statut'] ?? 'En attente') ?>
                </span>
            </div>
            <div class="card-body">
                <p><strong>Zone :</strong> <?= htmlspecialchars($facture['zone']) ?></p>
                <p><strong>Date facturation :</strong> <?= htmlspecialchars($facture['date_facturation']) ?></p>
                <p><strong>Matricule agent :</strong> <?= htmlspecialchars($facture['matricule_agent']) ?></p>
                <p><strong>Montant total :</strong> <?= number_format($facture['montant_total'], 2) ?> FCFA</p>
                <?php if (!empty($facture['fichier_scan']) && file_exists($facture['fichier_scan'])): ?>
                    <p><strong>Scan facture :</strong> 
                        <a href="<?= htmlspecialchars($facture['fichier_scan']) ?>" target="_blank">Voir</a>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Détails Matériels -->
        <h5>Détails des matériels</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Matériel</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($details)): ?>
                        <?php $i = 1; foreach ($details as $d): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($d['nom']) ?> (<?= htmlspecialchars($d['unite']) ?>)</td>
                                <td><?= $d['quantite'] ?></td>
                                <td><?= number_format($d['prix_unitaire'], 2) ?> FCFA</td>
                                <td><?= number_format($d['montant'], 2) ?> FCFA</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">Aucun matériel</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Fiches de réception associées -->
        <h5 class="mt-4">Fiches de réception associées</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>#</th>
                        <th>Numéro Fiche</th>
                        <th>GIE</th>
                        <th>Zone</th>
                        <th>Date Réception</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($fiches)): ?>
                        <?php $i = 1; foreach ($fiches as $fiche): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($fiche['numero_fiche']) ?></td>
                                <td><?= htmlspecialchars($fiche['gie']) ?></td>
                                <td><?= htmlspecialchars($fiche['zone']) ?></td>
                                <td><?= date('d/m/Y', strtotime($fiche['date_reception'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">Aucune fiche associée</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Signatures -->
        <div class="card mt-4 shadow">
            <div class="card-header">
                <h5 class="mb-0">Signatures & Validations</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <p><strong>Agent Responsable</strong></p>
                        <div class="border p-5">Signature</div>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Responsable</strong></p>
                        <div class="border p-5">Signature</div>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Contrôleur</strong></p>
                        <div class="border p-5">Signature</div>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>

</div>

</body>
</html>
