<?php
$pdo = new PDO("mysql:host=localhost;dbname=gestion-database;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Exécuter la requête et stocker le résultat dans $stmt

// Préparer l’export CSV
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=rapport_fiches-reception.csv");

$output = fopen("php://output", "w");

// En-têtes de colonnes
fputcsv($output, [
    "Numéro fiche", "GIE", "Date réception", "Zone",
    "Matériel", "Unité", "Quantité constatée"
]);

// Nouvelle requête pour inclure les détails matériels
$sql = "
SELECT f.numero_fiche, f.gie, f.date_reception, f.zone,
       m.nom as materiel, m.unite, mr.qte_constatee
FROM fiche_reception f
JOIN materiels_reception mr ON f.id = mr.fiche_id
JOIN materiel m ON mr.materiel_id = m.id
ORDER BY f.date_reception DESC, f.numero_fiche DESC
";
$stmt = $pdo->query($sql);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['numero_fiche'],
        $row['gie'],
        $row['date_reception'],
        $row['zone'],
        $row['materiel'],
        $row['unite'],
        $row['qte_constatee']
    ]);
}

fclose($output);
exit;
?>
