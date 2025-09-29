// ➕ Ajouter une nouvelle// Fonction pour afficher les messages
function showMessage(me      console.log('Réponse reçue:', response); // Debug
      
      try {
          const result = await response.json();
          console.log('Données reçues:', result); // Debug

          if (result.success) {
              showMessage(result.message, false);
              // Réinitialiser le formulaire
              this.reset();
              
              // Recharger la page après 2 secondes
              setTimeout(() => {
                  window.location.reload();
              }, 2000);
          } else {
              showMessage(result.message, true);
          }
      } catch (error) {
          console.error('Erreur parsing JSON:', error); // Debug
          showMessage('Erreur lors du traitement de la réponse', true);
      }false) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `alert ${isError ? 'alert-danger' : 'alert-success'} alert-dismissible fade show`;
    messageDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.querySelector('#receptionForm').insertBefore(messageDiv, document.querySelector('#receptionForm').firstChild);
}

document
  .getElementById("receptionForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault(); // empêcher l'envoi normal
    console.log("Formulaire soumis"); // Debugne de matériel
function addMaterielRow() {
  const table = document
    .getElementById("materielTable")
    .getElementsByTagName("tbody")[0];

  const newRow = table.rows[0].cloneNode(true);

  // Réinitialiser les champs de la nouvelle ligne
  newRow.querySelectorAll("input, select").forEach((input) => {
    input.value = "";
  });

  table.appendChild(newRow);
}

// ❌ Supprimer une ligne de matériel
function removeMaterielRow(btn) {
  const row = btn.closest("tr");
  const table = row.parentNode;
  if (table.rows.length > 1) {
    row.remove();
  }
}

document
  .getElementById("receptionForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault(); // empêcher l’envoi normal

    const errorMsg = document.getElementById("errorMsg");
    const successMsg = document.getElementById("successMsg");

    // Reset messages
    errorMsg.textContent = "";
    errorMsg.className = "mb-3";
    successMsg.textContent = "";
    successMsg.className = "mb-3";

    // Vérifier doublons de compteurs
    const compteurList = document
      .getElementById("compteurs")
      .value.trim()
      .split("\n")
      .map((s) => s.trim())
      .filter((s) => s);

    const compteurSet = new Set();
    let hasDuplicate = false;

    compteurList.forEach((num) => {
      if (compteurSet.has(num)) {
        hasDuplicate = true;
      }
      compteurSet.add(num);
    });

    if (hasDuplicate) {
      errorMsg.textContent =
        "⚠️ Doublon détecté dans les numéros de compteurs. Veuillez vérifier.";
      errorMsg.classList.add("alert", "alert-danger");
      return;
    }

    // Préparer les données du formulaire
    const formData = new FormData(this);

    try {
      const response = await fetch("../php/save_reception.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        successMsg.innerHTML = result.message;
        successMsg.classList.add("alert", "alert-success");
        
        // Réinitialiser le formulaire
        this.reset();
        
        // Recharger la page après 2 secondes pour montrer la nouvelle entrée dans le tableau
        setTimeout(() => {
          window.location.reload();
        }, 2000);
      } else {
        errorMsg.innerHTML = result.message;
        errorMsg.classList.add("alert", "alert-danger");
      }
    } catch (err) {
      errorMsg.textContent = "❌ Erreur réseau : " + err.message;
      errorMsg.classList.add("alert", "alert-danger");
    }
  });
