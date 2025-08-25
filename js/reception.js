// cette fonction ajoute une nouvelle ligne de matériel
      function addMaterielRow() {
        const table = document
          .getElementById("materielTable")
          .getElementsByTagName("tbody")[0];
        const newRow = table.rows[0].cloneNode(true);
        newRow.querySelectorAll("input").forEach((input) => (input.value = ""));
        table.appendChild(newRow);
      }

      // cette fonction supprime une ligne de matériel
      function removeMaterielRow(btn) {
        const row = btn.parentNode.parentNode;
        const table = row.parentNode;
        if (table.rows.length > 1) {
          table.removeChild(row);
        }
      }

      // cette fonction gère la soumission du formulaire

      document
        .getElementById("receptionForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();
          const compteurList = document
            .getElementById("compteurs")
            .value.trim()
            .split("\n")
            .map((s) => s.trim())
            .filter((s) => s);
          const compteurSet = new Set();
          let hasDuplicate = false;
          compteurList.forEach((num) => {
            if (compteurSet.has(num)) hasDuplicate = true;
            compteurSet.add(num);
          });
          if (hasDuplicate) {
            document.getElementById("errorMsg").textContent =
              "Doublon détecté dans les numéros de compteurs. Veuillez vérifier.";
            return;
          }
          document.getElementById("errorMsg").textContent = "";
          alert("Réception validée !");
          // Ici, vous pouvez ajouter le code pour envoyer les données au serveur
        });


// ce script gère l'affichage des matériels dans le tableau
        document.getElementById("receptionForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  const compteurs = document.getElementById("compteurs").value.split("\n");

  for (let num of compteurs) {
    num = num.trim();
    if (num !== "") {
      // Vérification via API
      let response = await fetch(`/api/verifier-compteur?numero=${num}`);
      let data = await response.json();

      if (!data.valide) {
        alert(`Le compteur/avis ${num} est invalide !`);
        return; // stoppe l’enregistrement
      }

      if (data.doublon) {
        alert(`Doublon détecté pour ${num} !`);
        return;
      }
    }
  }

  alert("✅ Réception validée et enregistrée !");
});



