
        document.getElementById("addMaterielForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            const response = await fetch("../php/save_materiel.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                // Ajouter le nouvel élément dans la liste déroulante
                const select = document.getElementById("materielSelect");
                const option = document.createElement("option");
                option.value = result.id;
                option.textContent = `${result.nom} (${result.unite})`;
                select.appendChild(option);

                // Sélectionner le nouveau matériel automatiquement
                select.value = result.id;

                // Fermer le modal
                const modal = bootstrap.Modal.getInstance(document.getElementById("addMaterielModal"));
                modal.hide();

                // Réinitialiser le formulaire
                this.reset();
            } else {
                alert("⚠️ Erreur : " + result.message);
            }
        });
