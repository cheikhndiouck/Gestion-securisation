<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bons de sortie</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"/>
    <link rel="stylesheet" href="./css/global.css" />
    <link rel="stylesheet" href="./css/gestion_intern.css" />
  </head>

  <body>
    <?php include './php/components/header.php'; ?>

    <main class="container my-4">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="text-center">Bons de sorties</h1>

<!-- Button trigger modal -->
<button
  type="button"
  class="btn btn-primary me-3"
  data-bs-toggle="modal"
  data-bs-target="#formModal"
  style="height: 40px"
>
  Lancer le formulaire
</button>

<!-- Modal -->
<div
  class="modal fade"
  id="formModal"
  tabindex="-1"
  aria-labelledby="formModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog modal-lg"> 
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="formModalLabel">
          Remplir le formulaire
        </h1>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <form id="materielForm" class="row g-3" method="post">
          <div class="col-md-6">
            <label for="inputdate" class="form-label">Date</label>
            <input
              type="date"
              class="form-control"
              id="inputdate"
              required
            />
          </div>


                <div class="col-md-6">
                  <label for="num-bon" class="form-label">Numéro du bon</label>
                  <input
                    type="text"
                    class="form-control"
                    id="num-bon"
                    placeholder="Ex: BON-2025-001"
                    required
                  />
                </div>

                <div class="col-md-6">
                  <label for="inputGIE" class="form-label">GIE</label>
                  <select id="inputGIE" class="form-select" required>
                    <option value="society1">A</option>
                    <option value="society2">B</option>
                    <option value="society3">C</option>
                    <option value="society4">D</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="inputAddress" class="form-label"
                    >Zones concernées</label
                  >
                  <select id="inputAddress" class="form-select" required>
                    <option value="Addr1">A</option>
                    <option value="Addr2">B</option>
                    <option value="Addr3">C</option>
                    <option value="Addr4">D</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="inputdemande" class="form-label"
                    >Demande de securisation</label
                  >
                  <select id="inputdemande" class="form-select" required>
                    <option value="sec1">DD</option>
                    <option value="sec2">PNT</option>
                    <option value="sec3">Projet</option>
                    <option value="sec4">Ciblage</option>
                  </select>
                </div>
                <div class="col-12">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="gridCheck"
                      required
                    />
                    <label class="form-check-label" for="gridCheck">
                      Valider les conditions générales d'utilisation
                    </label>
                  </div>
                </div>
                <div class="col-12 modal-footer">
                  <button type="submit" class="btn btn-primary">Charger</button>
                  <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-dismiss="modal"
                    style="background-color: rgb(190, 28, 28); border: none"
                  >
                    Annuler
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>
</div>
    <!-- Tableau des matériels -->

    <div id="materielsTableau" class="mt-4" style="display: none">
      <h3 class="text-center text-primary mb-3">Inventaire des matériels</h3>
      <div class="table-responsive">
        <table
          class="table bg-white text-center"
        >
          <thead class="table-dark" table-group-divider>
            
    <tr>
      <th scope="col">#</th>
      <th scope="col">Materiels</th>
      <th scope="col">Quantites</th>
      <th scope="col">Unites</th>
    </tr>
  </thead>
          <tbody id="ListeMateriels">
            <!-- Les lignes seront ajoutées ici dynamiquement par JS -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- Bouton Valider -->
    <div
      class="col-12 mt-3"
      id="enregistrerDiv"
      style="display: none; text-align: center; color: #0d179c"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="16"
        height="16"
        fill="currentColor"
        class="bi bi-arrow-bar-right"
        viewBox="0 0 16 16"
      >
        <path
          fill-rule="evenodd"
          d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8m-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5"
        />
      </svg>
      <button id="enregistrerBtn" class="btn btn-primary">Valider</button>
    </div>

    <footer></footer>
    <script src="./js/gestion_intern.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
