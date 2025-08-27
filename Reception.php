<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Saisie de la Réception</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./css/global.css" />
    <!-- <link rel="stylesheet" href="./css/reception.css"> -->
  </head>

  <body>
    <!-- NAVBAR -->
    <?php include './php/components/header.php'; ?>

    <!-- FORMULAIRE -->
    <main class="container my-4">
      <h1 class="text-center mb-4">Réception des Travaux</h1>

      <div class="conteneur">
        <form id="receptionForm" class="card p-4 shadow rounded" method="post">
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="gie" class="form-label">Nom du GIE</label>
              <input
                type="text"
                id="gie"
                name="gie"
                class="form-control"
                placeholder="Nom du GIE..."
                required
              />
            </div>
            <div class="col-md-6">
              <label for="dateReception" class="form-label"
                >Date de réception</label
              >
              <input
                type="date"
                id="dateReception"
                name="dateReception"
                class="form-control"
                required
              />
            </div>
          </div>

          <!-- Matériel réceptionné -->
          <div class="mb-3">
            <label class="form-label">Matériel réceptionné</label>
            <table id="materielTable" class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th>Nom du matériel</th>
                  <th>Quantité sur place</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <input
                      type="text"
                      name="materiel[]"
                      class="form-control"
                      required
                    />
                  </td>
                  <td>
                    <input
                      type="number"
                      name="quantite[]"
                      class="form-control"
                      min="1"
                      required
                    />
                  </td>
                  <td>
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      onclick="removeMaterielRow(this)"
                    >
                      Supprimer
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            <button
              type="button"
              class="btn btn-success"
              onclick="addMaterielRow()"
            >
              + Ajouter un matériel
            </button>
          </div>

          <!-- Compteurs -->
          <div class="mb-3">
            <label for="compteurs" class="form-label"
              >Numéros de compteurs ou avis de services concernés</label
            >
            <textarea
              id="compteurs"
              name="compteurs"
              rows="2"
              class="form-control"
              placeholder="Un numéro par ligne, Ex: C-001, C-002, C-003..."
              required
            ></textarea>
          </div>

          <div id="errorMsg" class="text-danger fw-bold mb-3"></div>

          <!-- Bouton validation -->
          <div class="text-center">
            <button type="submit" class="btn btn-primary px-5">
              Valider la réception
            </button>
          </div>
        </form>
      </div>
    </main>

    <footer class="text-center py-3"></footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/reception.js"></script>
  </body>
</html>
