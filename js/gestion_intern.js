document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("materielForm");
  const materielsTableau = document.getElementById("materielsTableau");
  const listeMateriels = document.getElementById("ListeMateriels");
  const enregistrerDiv = document.getElementById("enregistrerDiv");
  const enregistrerBtn = document.getElementById("enregistrerBtn");

  // Chargement des matériels depuis la base au submit du formulaire
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    fetch("./php/Materiel.php")
      .then((response) => response.json())
      .then((data) => {
        listeMateriels.innerHTML = "";
        data.forEach((item, idx) => {
          listeMateriels.innerHTML += `
            <tr>
              <td>${idx + 1}</td>
              <td>${item.nom}</td>
              <td>
                <input type="number" min="0" value="0" class="form-control quantite-input" data-nom="${
                  item.nom
                }" data-unite="${item.unite}">
              </td>
              <td>${item.unite}</td>
            </tr>
          `;
        });
        materielsTableau.style.display = "block";
        enregistrerDiv.style.display = "block";

        // Ferme la modal et réinitialise le formulaire APRÈS affichage du tableau
        const modal = document.getElementById("formModal");
        if (modal) {
          const modalInstance =
            bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
          modalInstance.hide();
        }
        form.reset();
      })
      .catch((error) => {
        ListeMateriels.innerHTML = `<tr><td colspan="4">Erreur de chargement des matériels</td></tr>`;
        materielsTableau.style.display = "block";
      });
  });

  // Enregistrement de l'inventaire
  enregistrerBtn.addEventListener("click", function () {
    const inputs = document.querySelectorAll(".quantite-input");
    const inventaire = [];
    inputs.forEach((input) => {
      inventaire.push({
        nom: input.getAttribute("data-nom"),
        quantite: input.value,
        unite: input.getAttribute("data-unite"),
      });
    });

    fetch("./php/Enregistrer.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(inventaire),
    })
      .then((response) => response.json())
      .then((result) => {
        alert(result.message);
        // Optionnel : vider la table après enregistrement
        // listeMateriels.innerHTML = '';
        // materielsTableau.style.display = 'none';
        // enregistrerDiv.style.display = 'none';
      })
      .catch((error) => {
        alert("Erreur lors de l’enregistrement");
      });
  });
});
