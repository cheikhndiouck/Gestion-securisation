<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paramétrage Administrateur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../css/global.css?v=1.2">
</head>

<body class="bg-light">

  <?php include './components/header.php'; ?>

  <div class="container py-4">
    <h2 class="mb-4 text-center text-dark">⚙️ Paramètres Administrateur</h2>

    <div class="row g-4">

      <!-- Intervenants / Responsables -->
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-dark text-white">Gestion des Responsables</div>
          <div class="card-body">
            <form id="formResponsables">
              <div class="mb-3">
                <label class="form-label">Ajouter un responsable</label>
                <input type="text" name="nom" class="form-control" placeholder="Nom du responsable">
              </div>
              <div class="mb-3">
                <label class="form-label">Centre de responsabilité</label>
                <select class="form-select" name="centre">
                  <option>SSCC</option>
                  <option>ST DC1</option>
                  <option>ST DC2</option>
                  <option>ST DC3</option>
                  <option>SGC DRCO</option>
                  <option>SGC DRCE</option>
                  <option>SGC DRN</option>
                  <option>SGC DRS</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Gestion GIE -->
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-success text-white">Gestion des GIE</div>
          <div class="card-body">
            <form id="formGIE">
              <div class="mb-3">
                <label class="form-label">Nom du GIE</label>
                <input type="text" class="form-control" name="gie_nom" placeholder="Ex: GIE Dakar Nord">
              </div>
              <button type="submit" class="btn btn-success">Ajouter GIE</button>
            </form>
          </div>
        </div>
      </div>


      <!-- Origine demande -->
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-info text-white">Origines des demandes</div>
          <div class="card-body">
            <?php
            require_once '../php/BD connexion/connexion.php';

            // Récupérer les origines distinctes de bon_sortie
            $stmt = $pdo->query("SELECT DISTINCT origine FROM bon_sortie ORDER BY origine ASC");
            $origines = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <!-- Liste déroulante -->
            <div class="mb-3">
              <label class="form-label">Origines existantes</label>
              <select class="form-select" name="origine_existante">
                <option value="">-- Sélectionner une origine --</option>
                <?php foreach ($origines as $org): ?>
                  <option value="<?= htmlspecialchars($org['origine']) ?>">
                    <?= htmlspecialchars($org['origine']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Formulaire ajout d'une nouvelle origine -->
            <form id="formOrigine" method="POST" action="../php/add_origine.php">
              <div class="mb-3">
                <label class="form-label">Ajouter une nouvelle origine</label>
                <input type="text" class="form-control" name="origine" placeholder="Ex: DD, PNT, projet..." required>
              </div>
              <button type="submit" class="btn btn-info">Enregistrer</button>
            </form>
          </div>
        </div>
      </div>



      <!-- Zones -->
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-secondary text-white">Gestion des Zones</div>
          <div class="card-body">
            <?php
            require_once '../php/BD connexion/connexion.php';

            // Récupérer les zones distinctes de bon_sortie
            $stmt = $pdo->query("SELECT DISTINCT zone FROM bon_sortie ORDER BY zone ASC");
            $zones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <!-- Liste déroulante -->
            <div class="mb-3">
              <label class="form-label">Zones existantes</label>
              <select class="form-select" name="zone_existante">
                <option value="">-- Sélectionner une zone --</option>
                <?php foreach ($zones as $z): ?>
                  <option value="<?= htmlspecialchars($z['zone']) ?>">
                    <?= htmlspecialchars($z['zone']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Formulaire ajout d'une nouvelle zone -->
            <form id="formZone" method="POST" action="../php/add_zone.php">
              <div class="mb-3">
                <label class="form-label">Ajouter une nouvelle zone</label>
                <input type="text" class="form-control" name="zone" placeholder="Ex: Nord, Sud, Est, Ouest..." required>
              </div>
              <button type="submit" class="btn btn-secondary">Enregistrer Zone</button>
            </form>
          </div>
        </div>
      </div>



      <!-- Liste des matériels -->
      <!-- Liste des matériels -->
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">Liste des matériels</div>
          <div class="card-body">
            <?php
            // Connexion à la DB
            require_once '../php/BD connexion/connexion.php';

            // Récupérer les matériels existants
            $stmt = $pdo->query("SELECT id, nom, unite FROM materiel ORDER BY nom ASC");
            $materiels = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <!-- Liste déroulante des matériels -->
            <div class="mb-3">
              <label class="form-label">Matériel existant</label>
              <select class="form-select" name="materiel_id">
                <option value="">-- Sélectionner un matériel --</option>
                <?php foreach ($materiels as $mat): ?>
                  <option value="<?= $mat['id'] ?>">
                    <?= htmlspecialchars($mat['nom']) ?> (<?= htmlspecialchars($mat['unite']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Formulaire ajout d'un nouveau matériel -->
            <form id="formMateriel" method="POST" action="../php/add_materiel.php">
              <div class="mb-3">
                <label class="form-label">Ajouter un nouveau matériel</label>
                <input type="text" class="form-control" name="materiel_nom" placeholder="Ex: Clé à molette" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Unité</label>
                <input type="text" class="form-control" name="materiel_unite" placeholder="Ex: pièce, lot" required>
              </div>
              <button type="submit" class="btn btn-primary">Enregistrer Matériel</button>
            </form>
          </div>
        </div>
      </div>




      <!-- Budget annuel -->
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-warning text-dark">Budget annuel</div>
          <div class="card-body">
            <form id="formBudget">
              <div class="mb-3">
                <label class="form-label">Année</label>
                <input type="number" class="form-control" name="annee" value="<?= date('Y') ?>">
              </div>
              <div class="mb-3">
                <label class="form-label">Montant du budget (FCFA)</label>
                <input type="number" class="form-control" name="montant" placeholder="0">
              </div>
              <button type="submit" class="btn btn-warning">Enregistrer Budget</button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
  <footer class="text-center py-3">
    <p class="text-muted mb-0">© 2025 Senelec - Gestion Sécurisation</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>