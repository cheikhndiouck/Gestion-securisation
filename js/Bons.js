document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("materielForm");
  const materielsTableau = document.getElementById("materielsTableau");
  const listeMateriels = document.getElementById("ListeMateriels");
  const enregistrerDiv = document.getElementById("enregistrerDiv");
  const enregistrerBtn = document.getElementById("enregistrerBtn");
  const ajouterLigneBtn = document.getElementById("ajouterLigneBtn");

  let materielData = [];

  // Charger les matériels depuis PHP
  fetch("http://localhost/Projet-stage/php/Materiel.php")
    .then((response) => response.json())
    .then((data) => {
      materielData = data;
    });

  // Fonction pour ajouter une ligne
  function ajouterLigne() {
    let options = materielData
      .map(
        (mat) =>
          `<option value="${mat.id}" data-unite="${mat.unite}">${mat.nom}</option>`
      )
      .join("");

    const rowCount =
      document.querySelectorAll("#materielsTableau tbody tr").length + 1;

    const tbody = document.querySelector("#materielsTableau tbody");

    tbody.insertAdjacentHTML(
      "beforeend",
      `
        <tr>
          <td>${rowCount}</td>
          <td>
            <select class="form-select materiel-select">
              <option value="">-- Choisir --</option> 
              ${options}
            </select>
          </td>
          <td>
            <input type="number" min="0" value="0" class="form-control quantite-input">
          </td>
          <td>
            <input type="number" min="0" value="0" class="form-control prix-input">
          </td>
          <td class="unite-cell">
          <input type="number" min="0" value="0" class="form-control quantite-input">
          </td>
          <td>
            <button type="button" class="btn btn-danger btn-sm supprimer-ligne">Supprimer</button>
          </td>
        </tr>
      `
    );

    // ---- Gestion dynamique ----
    let lastRow = tbody.lastElementChild;
    let selectMateriel = lastRow.querySelector(".materiel-select");
    let uniteCell = lastRow.querySelector(".unite-cell");

    // Mettre l'unité automatiquement selon le matériel choisi
    selectMateriel.addEventListener("change", function () {
      let unite =
        selectMateriel.options[selectMateriel.selectedIndex].dataset.unite ||
        "";
      uniteCell.textContent = unite;
    });

    // Suppression de la ligne
    lastRow
      .querySelector(".supprimer-ligne")
      .addEventListener("click", function () {
        lastRow.remove();
        recalculerNumerotation();
      });
  }

  // Recalculer numérotation après suppression
  function recalculerNumerotation() {
    document.querySelectorAll("#materielsTableau tbody tr").forEach((tr, i) => {
      tr.querySelector("td").textContent = i + 1;
    });
  }

  // Quand on soumet le formulaire : affiche tableau et 1ère ligne
  document.addEventListener("DOMContentLoaded", function () {
    e.preventDefault();
    listeMateriels.innerHTML = "";
    ajouterLigne(); // Ajoute la première ligne
    materielsTableau.style.display = "block";
    enregistrerDiv.style.display = "block";
    ajouterLigneBtn.style.display = "inline-block";

    const modal = document.getElementById("formModal");
    if (modal) {
      const modalInstance =
        bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
      modalInstance.hide();
    }
    form.reset();
  });

  // Bouton pour ajouter une ligne
  ajouterLigneBtn.addEventListener("click", function () {
    ajouterLigne();
  });

  // Enregistrement de l’inventaire
  enregistrerBtn.addEventListener("click", function () {
    const rows = document.querySelectorAll("#materielsTableau tbody tr");
    const inventaire = [];

    rows.forEach((row) => {
      const select = row.querySelector(".materiel-select");
      const quantite = row.querySelector(".quantite-input").value;
      const prix = row.querySelector(".prix-input").value;
      const unite = row.querySelector(".unite-cell").textContent;

      if (select.value) {
        inventaire.push({
          id: select.value,
          nom: select.options[select.selectedIndex].text,
          quantite: quantite,
          prix: prix,
          unite: unite,
        });
      }
    });
    // ce script permet d'envoyer les données au serveur pour les enregistrer
    fetch("./php/Enregistrer.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(inventaire),
    })
      .then((response) => response.json())
      .then((result) => {
        alert(result.message);
      })
      .catch((error) => {
        alert("Erreur lors de l’enregistrement");
      });
  });
});
