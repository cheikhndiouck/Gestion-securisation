<?php
require_once '../php/BD connexion/connexion.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facturation Prestataire</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../css/global.css?v=1.2">
  <link rel="stylesheet" href="../css/facturation.css">
</head>

<body class="bg-light">

  <?php include './components/header.php'; ?>

  <main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-dark">Facturation Prestataire</h2>
      <div>
        <a href="../php/export_facturation.php" class="btn btn-outline-success">📥 Exporter en Excel</a>
      </div>
    </div>

    <!-- Formulaire de création de facture -->
    <div class="card shadow-lg rounded-4 mb-4">
      <div class="card-header bg-primary bg-senelec-blue text-white py-3">
        <h5 class="card-title mb-0">Créer une Facture</h5>
      </div>

      <div class="card-body p-4">
        <!-- Zone d'affichage des messages -->
        <div id="factureMsg"></div>
        <form id="factureForm" method="POST" action="../php/save_facture.php" enctype="multipart/form-data" autocomplete="off">
          <!-- Infos facture -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">N° Facture</label>
              <input type="text" class="form-control" name="numero_facture" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Zone</label>
              <select name="zone" class="form-select" required>
                <option value="">Sélectionner...</option>
                <option value="zone 1">zone 1</option>
                <option value="zone 2">zone 2</option>
                <option value="zone 3">zone 3</option>
                <option value="zone 4">zone 4</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Date de facturation</label>
              <input type="date" name="date_facturation" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Matricule Agent</label>
              <input type="text" class="form-control" name="matricule_agent" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Scan Facture (PDF ou Image)</label>
              <input type="file" class="form-control" name="fichier_scan" accept=".pdf,.jpg,.jpeg,.png">
            </div>
          </div>

          <!-- Sélection des fiches de réception -->
          <div class="card mb-3">
            <div class="card-header bg-light">
              <h5 class="mb-0">Sélectionner une ou plusieurs fiches de réception</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label for="fiche_reception_select" class="form-label">Fiches de réception disponibles</label>
                <select name="fiche_reception_ids[]" id="fiche_reception_select" class="form-select" multiple required onchange="afficherDetailsFiches()">
                  <option value="">-- Sélectionner --</option>
                  <?php
                  try {
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $pdo->query("
          SELECT fr.id, fr.numero_fiche, fr.gie, fr.zone, fr.date_reception 
          FROM fiche_reception fr
          WHERE fr.id NOT IN (SELECT fiche_id FROM facture_reception)
          ORDER BY fr.date_reception DESC
      ");
                    $fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($fiches as $fiche) {
                      echo '<option value="' . $fiche['id'] . '">'
                        . htmlspecialchars($fiche['numero_fiche']) . ' | '
                        . htmlspecialchars($fiche['gie']) . ' | '
                        . htmlspecialchars($fiche['zone']) . ' | '
                        . htmlspecialchars($fiche['date_reception'])
                        . '</option>';
                    }
                  } catch (PDOException $e) {
                    echo "<option value=''>Erreur : " . $e->getMessage() . "</option>";
                  }
                  ?>
                </select>

                <div id="detailsFichesReception"></div>
                <span id="montantTotal">0 FCFA</span>
                <input type="hidden" id="montantTotalInput" name="montant_total" value="0">

                <small class="text-muted d-block mt-2">
                  <i class="bi bi-info-circle"></i>
                  Maintenez Ctrl pour sélectionner plusieurs fiches (si même zone et prestataire).
                </small>
              </div>

              <!-- Tableau dynamique des fiches sélectionnées -->
              <div id="detailsFichesReception" class="mb-3"></div>

              <!-- Montant total -->
              <div class="alert alert-info mb-0">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold">Montant Total :</span>
                  <span id="montantTotal" class="fs-5 text-success">0 FCFA</span>
                  <input type="hidden" name="montant_total" id="montantTotalInput">
                </div>
              </div>
            </div>
          </div>

          <!-- Boutons de soumission -->
          <div class="d-flex justify-content-end gap-2">
            <button type="reset" class="btn btn-outline-secondary text-light bg-danger" id="resetBtn">
              <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
            </button>
            <button type="submit" class="btn btn-primary" id="submitBtn">
              <i class="bi bi-save"></i> Enregistrer la facture
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Table des factures -->
    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead class="table-light">
          <tr>
            <th>N° Facture</th>
            <th>Zone</th>
            <th>Date</th>
            <th>Matricule Agent</th>
            <th>Montant Total</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $factures = $pdo->query("SELECT * FROM facture ORDER BY date_facturation DESC")->fetchAll(PDO::FETCH_ASSOC);
          foreach ($factures as $facture) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($facture['numero_facture']) . "</td>";
            echo "<td>" . htmlspecialchars($facture['zone']) . "</td>";
            echo "<td>" . htmlspecialchars($facture['date_facturation']) . "</td>";
            echo "<td>" . htmlspecialchars($facture['matricule_agent']) . "</td>";
            echo "<td>" . number_format($facture['montant_total'], 0, ',', ' ') . " FCFA</td>";
            echo "<td>";
            if ($facture['fichier_scan']) {
              $scanPath = "../uploads/factures/" . htmlspecialchars($facture['fichier_scan']);
              echo "<a href='" . $scanPath . "' target='_blank' class='btn btn-sm btn-info me-2' title='Voir le scan'><i class='bi bi-eye'></i></a>";
            }
            echo "</td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
  <footer class="text-center py-3">
    <p class="text-muted mb-0">© 2025 Senelec - Gestion Sécurisation</p>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Préchargement des fiches réception -->
  <script>
    let fichesReceptionDetails = {};
    <?php
    if (isset($_SESSION['user'])) {
      try {
        $sql = "SELECT f.id as fiche_id, f.numero_fiche, f.gie, f.zone, f.date_reception, 
                       m.nom as materiel_nom, m.unite, m.prix_unitaire, mr.qte_constatee
                FROM fiche_reception f
                LEFT JOIN materiels_reception mr ON f.id = mr.fiche_id
                LEFT JOIN materiel m ON mr.materiel_id = m.id
                LEFT JOIN facture_reception fr ON f.id = fr.fiche_id
                WHERE fr.fiche_id IS NULL
                ORDER BY f.date_reception DESC";

        $details = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $jsFiches = [];
        foreach ($details as $row) {
          $fid = $row['fiche_id'];
          if (!isset($jsFiches[$fid])) {
            $jsFiches[$fid] = [
              'numero_fiche' => $row['numero_fiche'],
              'gie' => $row['gie'],
              'zone' => $row['zone'],
              'date_reception' => $row['date_reception'],
              'materiels' => []
            ];
          }
          $jsFiches[$fid]['materiels'][] = [
            'nom' => $row['materiel_nom'],
            'unite' => $row['unite'],
            'prix_unitaire' => $row['prix_unitaire'],
            'qte_constatee' => $row['qte_constatee']
          ];
        }
        echo "Object.assign(fichesReceptionDetails, " . json_encode($jsFiches) . ");";
      } catch (PDOException $e) {
        echo "console.error('Erreur SQL: " . addslashes($e->getMessage()) . "');";
      }
    }
    ?>
  </script>

  <!-- Script pour gérer la facturation -->
  <script src="../js/facturation.js"></script>

  <!-- Script inline pour formulaire + affichage fiches -->
  <script>
    // Soumission AJAX
    document.getElementById('factureForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const form = this;
      const msgDiv = document.getElementById('factureMsg');
      const submitBtn = document.getElementById('submitBtn');
      msgDiv.innerHTML = '';
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enregistrement...';
      const formData = new FormData(form);
      fetch(form.action, {
          method: 'POST',
          body: formData
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            msgDiv.innerHTML = `<div class='alert alert-success'>${data.message}</div>`;
            form.reset();
            window.scrollTo({
              top: 0,
              behavior: 'smooth'
            });
            setTimeout(() => window.location.reload(), 1500);
          } else {
            msgDiv.innerHTML = `<div class='alert alert-danger'>${data.message}</div>`;
          }
        })
        .catch(() => {
          msgDiv.innerHTML = `<div class='alert alert-danger'>Erreur lors de l'enregistrement.'`;
        })
        .finally(() => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="bi bi-save"></i> Enregistrer la facture';
        });
    });

    // Réinitialiser fiches
    const ficheSelect = document.getElementById('fiche_reception_select');
    const detailsDiv = document.getElementById('detailsFichesReception');
    const montantTotalSpan = document.getElementById('montantTotal');
    const montantTotalInput = document.getElementById('montantTotalInput');
    document.getElementById('resetBtn').addEventListener('click', function() {
      ficheSelect.selectedIndex = -1;
      detailsDiv.innerHTML = '';
      montantTotalSpan.textContent = '0 FCFA';
      montantTotalInput.value = 0;
    });

    // Fonction pour afficher les détails
    function afficherDetailsFiches() {
      detailsDiv.innerHTML = '';
      let total = 0;
      const selectedIds = Array.from(ficheSelect.selectedOptions).map(opt => opt.value);

      selectedIds.forEach(fid => {
        if (fichesReceptionDetails[fid]) {
          const fiche = fichesReceptionDetails[fid];
          let ficheHtml = `
            <div class="card shadow-sm mb-3">
              <div class="card-header bg-secondary text-white">
                <strong>Fiche : ${fiche.numero_fiche}</strong> | ${fiche.gie} | ${fiche.zone} | ${fiche.date_reception}
              </div>
              <div class="card-body p-2">
                <table class="table table-sm table-bordered mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>Matériel</th>
                      <th>Qté</th>
                      <th>Unité</th>
                      <th>Prix Unitaire</th>
                      <th>Sous-total</th>
                    </tr>
                  </thead>
                  <tbody>
          `;

          fiche.materiels.forEach(mat => {
            const sousTotal = mat.qte_constatee * mat.prix_unitaire;
            total += sousTotal;
            ficheHtml += `
              <tr>
                <td>${mat.nom}</td>
                <td>${mat.qte_constatee}</td>
                <td>${mat.unite}</td>
                <td>${mat.prix_unitaire.toLocaleString()} FCFA</td>
                <td>${sousTotal.toLocaleString()} FCFA</td>
              </tr>
            `;
          });

          ficheHtml += `
                  </tbody>
                </table>
              </div>
            </div>
          `;

          detailsDiv.innerHTML += ficheHtml;
        }
      });

      montantTotalSpan.textContent = total.toLocaleString() + ' FCFA';
      montantTotalInput.value = total;
    }
  </script>
</body>

</html>