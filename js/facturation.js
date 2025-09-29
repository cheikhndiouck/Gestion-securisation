// ---------------------------------------------
// FACTURATION.JS
// ---------------------------------------------

// ❗ Ne pas redéclarer fichesReceptionDetails ici si tu l'as déjà déclaré dans le <script> PHP
// let fichesReceptionDetails = {}; // <-- déjà initialisé depuis PHP

// Fonction pour afficher les détails des fiches sélectionnées
function afficherDetailsFiches() {
  const select = document.getElementById("fiche_reception_select");
  const detailsDiv = document.getElementById("detailsFichesReception");
  const montantTotalSpan = document.getElementById("montantTotal");
  const montantTotalInput = document.getElementById("montantTotalInput");

  let html = "";
  let montantTotal = 0;

  const selected = Array.from(select.selectedOptions)
    .map((opt) => opt.value)
    .filter(Boolean);

  if (selected.length === 0) {
    detailsDiv.innerHTML = "";
    montantTotalSpan.textContent = "0 FCFA";
    montantTotalInput.value = 0;
    return;
  }

  html += `<table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Numéro fiche</th>
                        <th>GIE</th>
                        <th>Zone</th>
                        <th>Date réception</th>
                        <th>Matériel</th>
                        <th>Unité</th>
                        <th>Qté</th>
                        <th>Prix unitaire</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody>`;

  selected.forEach((fid) => {
    const fiche = fichesReceptionDetails[fid];
    if (fiche) {
      fiche.materiels.forEach((mat) => {
        const montant = mat.qte_constatee * mat.prix_unitaire;
        montantTotal += montant;

        html += `<tr>
                            <td>${fiche.numero_fiche}</td>
                            <td>${fiche.gie}</td>
                            <td>${fiche.zone}</td>
                            <td>${fiche.date_reception}</td>
                            <td>${mat.nom}</td>
                            <td>${mat.unite}</td>
                            <td>${mat.qte_constatee}</td>
                            <td>${mat.prix_unitaire.toLocaleString()} FCFA</td>
                            <td>${montant.toLocaleString()} FCFA</td>
                        </tr>`;
      });
    }
  });

  html += "</tbody></table>";
  detailsDiv.innerHTML = html;
  montantTotalSpan.textContent = montantTotal.toLocaleString() + " FCFA";
  montantTotalInput.value = montantTotal;

  // Mettre à jour la zone sélectionnée
  const zoneSelect = document.querySelector('select[name="zone"]');
  if (selected.length > 0) {
    const premiereZone = fichesReceptionDetails[selected[0]].zone;
    zoneSelect.value = premiereZone;
    zoneSelect.disabled = true;
  } else {
    zoneSelect.disabled = false;
  }
}

// Validation du formulaire avant soumission
document.getElementById("factureForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const form = this;
  const msgDiv = document.getElementById("factureMsg");
  const submitBtn = document.getElementById("submitBtn");
  msgDiv.innerHTML = "";

  // Validation simple
  const numeroFacture = form.numero_facture.value.trim();
  if (!numeroFacture) {
    msgDiv.innerHTML = `<div class="alert alert-danger">⚠️ Le numéro de facture est obligatoire</div>`;
    return;
  }

  const zone = form.zone.value;
  if (!zone) {
    msgDiv.innerHTML = `<div class="alert alert-danger">⚠️ Veuillez sélectionner une zone</div>`;
    return;
  }

  const matricule = form.matricule_agent.value.trim();
  if (!matricule) {
    msgDiv.innerHTML = `<div class="alert alert-danger">⚠️ Le matricule de l'agent est obligatoire</div>`;
    return;
  }

  const fichesSelectionnees = Array.from(
    form.querySelectorAll("#fiche_reception_select option:checked")
  );
  if (fichesSelectionnees.length === 0) {
    msgDiv.innerHTML = `<div class="alert alert-danger">⚠️ Veuillez sélectionner au moins une fiche de réception</div>`;
    return;
  }

  // Gestion fichier upload
  const fichier = form.fichier_scan.files[0];
  if (fichier) {
    const allowedTypes = ["application/pdf", "image/jpeg", "image/png"];
    const maxSize = 5 * 1024 * 1024;
    if (!allowedTypes.includes(fichier.type)) {
      msgDiv.innerHTML = `<div class="alert alert-danger">⚠️ Le fichier doit être PDF, JPG ou PNG</div>`;
      return;
    }
    if (fichier.size > maxSize) {
      msgDiv.innerHTML = `<div class="alert alert-danger">⚠️ Le fichier ne doit pas dépasser 5MB</div>`;
      return;
    }
  }

  // Soumission AJAX
  submitBtn.disabled = true;
  submitBtn.innerHTML =
    '<span class="spinner-border spinner-border-sm"></span> Enregistrement...';

  const formData = new FormData(form);
  fetch(form.action, { method: "POST", body: formData })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        msgDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        form.reset();
        detailsFichesReception.innerHTML = "";
        montantTotal.textContent = "0 FCFA";
        montantTotalInput.value = 0;
        setTimeout(() => window.location.reload(), 1500);
      } else {
        msgDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
      }
    })
    .catch(() => {
      msgDiv.innerHTML = `<div class="alert alert-danger">Erreur lors de l'enregistrement</div>`;
    })
    .finally(() => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="bi bi-save"></i> Enregistrer la facture';
    });
});

// Bouton réinitialiser
document.getElementById("resetBtn").addEventListener("click", function () {
  document.getElementById("fiche_reception_select").selectedIndex = -1;
  document.getElementById("detailsFichesReception").innerHTML = "";
  document.getElementById("montantTotal").textContent = "0 FCFA";
  document.getElementById("montantTotalInput").value = 0;
  document.querySelector('select[name="zone"]').disabled = false;
});
