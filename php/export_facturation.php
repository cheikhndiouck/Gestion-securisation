<?php
$pdo = new PDO("mysql:host=localhost;dbname=gestion-database;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Exécution de la requête

// Préparation de l’export CSV
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=rapport_facturation.csv");

$output = fopen("php://output", "w");

// En-têtes colonnes
fputcsv($output, [
    "ID Facture", "N° Facture", "Zone", "Date facturation", "Matricule agent", "Fichier scan", "Montant total",
    "Fiche(s) de réception", "Matériel", "Quantité", "Prix unitaire", "Montant"
]);

// Récupérer toutes les factures avec leurs fiches et détails matériels
$sql = "
SELECT f.id as facture_id, f.numero_facture, f.zone, f.date_facturation, f.matricule_agent, f.fichier_scan, f.montant_total,
       GROUP_CONCAT(DISTINCT fr.numero_fiche SEPARATOR ' | ') as fiches,
       m.nom as materiel, fd.quantite, fd.prix_unitaire, fd.montant
FROM facture f
LEFT JOIN facture_reception frl ON f.id = frl.facture_id
LEFT JOIN fiche_reception fr ON frl.fiche_id = fr.id
LEFT JOIN facture_details fd ON f.id = fd.facture_id
LEFT JOIN materiel m ON fd.materiel_id = m.id
GROUP BY f.id, m.nom, fd.quantite, fd.prix_unitaire, fd.montant
ORDER BY f.date_facturation DESC, f.id DESC
";
$stmt = $pdo->query($sql);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['facture_id'],
        $row['numero_facture'],
        $row['zone'],
        $row['date_facturation'],
        $row['matricule_agent'],
        $row['fichier_scan'],
        $row['montant_total'],
        $row['fiches'],
        $row['materiel'],
        $row['quantite'],
        $row['prix_unitaire'],
        $row['montant']
    ]);
}

fclose($output);
exit;
?>
