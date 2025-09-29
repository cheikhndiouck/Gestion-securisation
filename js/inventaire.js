function mettreAJourUnite(select) {
    const uniteInput = select.closest('tr').querySelector('.unite');
    const selectedOption = select.options[select.selectedIndex];
    uniteInput.value = selectedOption.dataset.unite || '';
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.materiel-select').forEach(select => {
        select.addEventListener('change', function() {
            mettreAJourUnite(this);
        });
    });
});

function ajouterLigne() {
    const table = document.getElementById('inventaireTable').querySelector('tbody');
    const newRow = table.rows[0].cloneNode(true);
    newRow.querySelectorAll('input').forEach(i => i.value = '');
    newRow.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
    table.appendChild(newRow);

    newRow.querySelector('.materiel-select').addEventListener('change', function() {
        mettreAJourUnite(this);
    });
}

function supprimerLigne(btn) {
    const tbody = btn.closest('tbody');
    if (tbody.rows.length > 1) {
        btn.closest('tr').remove();
    } else {
        alert("⚠️ Impossible de supprimer la dernière ligne !");
    }
}
