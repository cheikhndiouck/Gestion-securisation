// ce script gère l'affichage et la soumission du formulaire pour la gestion des matériels

document
  .getElementById("materielForm")
  .addEventListener("submit", function (e) {
    e.preventDefault(); // empêche le rechargement de la page

    fetch("Materiel.php")
      .then((response) => response.json())
      .then((data) => {
        const tbody = document.getElementById("listeMateriels");
        tbody.innerHTML = ""; // vider le tableau avant de remplir

        data.forEach((item) => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
                  <td>${item.nom}</td>
                  <td><input type="number" min="0" placeholder="Entrer quantité"></td>
                  <td>${item.unite}</td>
              `;
          tbody.appendChild(tr);
        });

        // afficher le tableau
        document.getElementById("materielsTableau").style.display = "block";
      })
      .catch((err) => console.error("Erreur fetch:", err));
  });


//   ce script gère l'enregistrement des données saisies dans le tableau
document
  .getElementById("enregistrerBtn")
  .addEventListener("click", function () {
    const rows = document.querySelectorAll("#listeMateriels tr");
    const inventaire = [];

    rows.forEach((row) => {
      const nom = row.cells[0].innerText;
      const quantite = row.cells[1].querySelector("input").value;
      const unite = row.cells[2].innerText;

      if (quantite !== "") {
        inventaire.push({ nom, quantite, unite });
      }
    });

    // Envoi via POST au PHP
    fetch("enregistrer.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(inventaire),
    })
      .then((response) => response.json())
      .then((data) => {
        alert(data.message);
      })
      .catch((err) => console.error("Erreur:", err));
  });

// ce script gère l'affichage du formulaire et la soumission pour la gestion des matériels
document
  .getElementById("materielForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    fetch("Materiel.php")
      .then((response) => response.json())
      .then((data) => {
        const tbody = document.getElementById("listeMateriels");
        tbody.innerHTML = "";

        data.forEach((item) => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
                  <td>${item.nom}</td>
                  <td><input type="number" min="0" placeholder="Entrer quantité"></td>
                  <td>${item.unite}</td>
              `;
          tbody.appendChild(tr);
        });

        // afficher le tableau
        document.getElementById("materielsTableau").style.display = "block";

        // afficher le bouton Enregistrer
        document.getElementById("enregistrerDiv").style.display = "block";
      })
      .catch((err) => console.error("Erreur fetch:", err));
  });

// ce script gère l'enregistrement des données saisies dans le tableau
document
  .getElementById("enregistrerBtn")
  .addEventListener("click", function () {
    const rows = document.querySelectorAll("#listeMateriels tr");
    const inventaire = [];

    rows.forEach((row) => {
      const nom = row.cells[0].innerText;
      const quantite = row.cells[1].querySelector("input").value;
      const unite = row.cells[2].innerText;

      if (quantite !== "") {
        inventaire.push({ nom, quantite, unite });
      }
    });

    fetch("enregistrer.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(inventaireS),
    })
      .then((response) => response.json())
      .then((data) => {
        alert(data.message);
      })
      .catch((err) => console.error("Erreur:", err));
  });
