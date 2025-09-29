<?php
session_start();
require_once '../php/lst_materiel.php'; // Pour accéder à la liste des matériels
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Saisie de la Réception</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../css/global.css?v=1.2" />
  <!-- <link rel="stylesheet" href="../css/reception.css"> -->

</head>

<body class="bg-light">

  <?php include './components/header.php'; ?>


  <main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Réception des Travaux</h1>
      <div>
        <button class="btn btn-primary bg-senelec-blue" type="button" data-bs-toggle="collapse" data-bs-target="#formSection">
          <i class="bi bi-plus-circle"></i> Nouvelle Réception
        </button>
      </div>
    </div>

    <!-- Formulaire de nouvelle réception -->
    <div id="formSection" class="collapse show">

      <div class="conteneur">
        <form id="receptionForm" class="card p-4 shadow rounded" method="POST" action="../php/save_reception.php">
          <?php if (isset($_SESSION['reception_message'])): ?>
            <div class="mb-3">
              <?php
              echo $_SESSION['reception_message'];
              unset($_SESSION['reception_message']);
              ?>
            </div>
          <?php endif; ?>

          <div class="row mb-3">
            <div class="col-md-4">
              <label for="gie" class="form-label">Nom du GIE</label>
              <input
                type="text"
                id="gie"
                name="gie"
                class="form-control"
                placeholder="Nom du GIE..."
                required />
            </div>
            <div class="col-md-4">
              <label for="zone" class="form-label">Zone</label>
              <select id="zone" name="zone" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <option value="zone 1">zone 1</option>
                <option value="zone 2">zone 2</option>
                <option value="zone 3">zone 3</option>
                <option value="zone 4">zone 4</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="dateReception" class="form-label">Date de réception</label>
              <input
                type="date"
                id="dateReception"
                name="dateReception"
                class="form-control"
                value="<?= date('Y-m-d') ?>" readonly />
            </div>
          </div>

          <!-- Matériel réceptionné -->
          <div class="mb-3">
            <label class="form-label">Matériel réceptionné</label>
            <table id="materielTable" class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th>Nom du matériel</th>
                  <th>Unité</th>
                  <th>Qté constatée</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <select name="materiel[]" class="form-select" required>
                      <option value="">-- Sélectionner --</option>
                      <?php foreach ($materiels as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                  <td>
                    <input type="text" class="form-control" name="unite[]" readonly>
                  </td>
                  <td><input type="number" class="form-control" name="quantite[]"></td>
                  <td>
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      onclick="removeMaterielRow(this)">
                      Supprimer
                    </button>
                  </td>
                </tr>
                <!-- Le JavaScript a été déplacé vers reception.js -->
              </tbody>
            </table>
            <button
              type="button"
              class="btn btn-success"
              onclick="addMaterielRow()">
              + Ajouter un matériel
            </button>
          </div>

          <!-- Compteurs -->
          <div class="mb-3">
            <label for="compteurs" class="form-label">Numéros de compteurs ou avis de services concernés</label>
            <textarea
              id="compteurs"
              name="compteurs"
              rows="2"
              class="form-control"
              placeholder="Un numéro par ligne, Ex: C-001, C-002, C-003..."
              required></textarea>
          </div>

          <!-- Zone d’affichage des erreurs -->
          <div id="errorMsg" class="mb-3"></div>

          <!-- Bouton validation -->
          <div class="text-center">
            <button type="submit" class="btn btn-primary px-5">
              Valider la réception
            </button>
          </div>

        </form>
        <!-- Zone messages Bootstrap -->
        <div id="errorMsg" class="mb-3"></div>
        <div id="successMsg" class="mb-3"></div>

      </div>
  </main>

  <footer class="text-center py-3">
    <p class="text-muted mb-0">© 2025 Senelec - Gestion Sécurisation</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Données des matériels
    const materiels = <?php echo json_encode($materiels); ?>;
    const materielsUnites = {};

    // Créer un mapping des unités par ID de matériel
    materiels.forEach(m => {
      materielsUnites[m.id] = m.unite;
    });

    // Mettre à jour l'unité quand un matériel est sélectionné
    document.querySelectorAll('select[name="materiel[]"]').forEach(select => {
      select.addEventListener('change', function() {
        const uniteInput = this.closest('tr').querySelector('input[name="unite[]"]');
        const selectedId = this.value;
        uniteInput.value = materielsUnites[selectedId] || '';
      });
    });

    // Ajouter une nouvelle ligne de matériel
    function addMaterielRow() {
      const tbody = document.querySelector('#materielTable tbody');
      const firstRow = tbody.querySelector('tr');
      const newRow = firstRow.cloneNode(true);

      // Réinitialiser les valeurs
      newRow.querySelectorAll('input, select').forEach(input => {
        input.value = '';
      });

      // Ajouter l'écouteur d'événements pour l'unité
      const materialSelect = newRow.querySelector('select[name="materiel[]"]');
      materialSelect.addEventListener('change', function() {
        const uniteInput = this.closest('tr').querySelector('input[name="unite[]"]');
        const selectedId = this.value;
        uniteInput.value = materielsUnites[selectedId] || '';
      });

      tbody.appendChild(newRow);
    }

    // Supprimer une ligne de matériel
    function removeMaterielRow(btn) {
      const tbody = document.querySelector('#materielTable tbody');
      if (tbody.querySelectorAll('tr').length > 1) {
        btn.closest('tr').remove();
      }
    }

    // Gestion du formulaire
    document.getElementById('receptionForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch('../php/save_reception.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            window.location.reload();
          } else {
            alert(data.message || 'Erreur lors de l\'enregistrement');
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert('Erreur lors de l\'enregistrement');
        });
    });

    // Fermer automatiquement le formulaire après un succès
    <?php if (isset($_SESSION['reception_message']) && strpos($_SESSION['reception_message'], 'succès') !== false): ?>
      document.addEventListener('DOMContentLoaded', function() {
        const formSection = document.getElementById('formSection');
        if (formSection.classList.contains('show')) {
          formSection.classList.remove('show');
        }
      });
    <?php endif; ?>
  </script>
</body>

</html>