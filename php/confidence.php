<?php
// PHP code can go here if needed
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Bon de sortie de matériel</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"/>
  <link rel="stylesheet" href="../css/global.css" /> 
    <link rel="stylesheet" href="../css/confidence.css" />
</head>
<body>
    <?php include './components/header.php'; ?>
  <div class="container mt-5">
    <div class="card shadow mx-auto" style="max-width: 600px;">
      <div class="card-header bg-primary text-white text-center">
        <h3>Bon de sortie de matériel</h3>
      </div>
      <div class="card-body">
        <form id="bonSortieForm" method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="numBon" class="form-label">Numéro du bon de sortie :</label>
            <input type="text" id="numBon" name="numBon" class="form-control" placeholder="Ex: BON-2025-001" required>
          </div>
          <div class="mb-3">
            <label for="dateBon" class="form-label">Date :</label>
            <input type="date" id="dateBon" name="dateBon" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="gie" class="form-label">Nom du GIE :</label>
            <input type="text" id="gie" name="gie" class="form-control" placeholder="Nom du GIE..." required>
          </div>
          <div class="mb-3">
            <label for="zone" class="form-label">Zone concernée (fichier ANDS) :</label>
            <input type="file" id="zone" name="zone" class="form-control" accept=".csv,.xls,.xlsx">
          </div>
          <div class="mb-3">
            <label for="origine" class="form-label">Origine de la demande :</label>
            <select id="origine" name="origine" class="form-select" required>
              <option value="">Sélectionner...</option>
              <option value="DD">DD</option>
              <option value="PNT">PNT</option>
              <option value="Projet">Projet</option>
              <option value="Ciblage">Ciblage</option>
              <!-- Ajoute d'autres options si besoin -->
            </select>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-success px-4">Valider</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>