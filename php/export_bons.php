<?php
$pdo = new PDO("mysql:host=localhost;dbname=gestion-database;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Préparer l’export CSV
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=bons_sortie.csv");

$output = fopen("php://output", "w");

// Ajouter BOM pour une meilleure compatibilité Excel
fwrite($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// En-têtes de colonnes
fputcsv($output, [
    "Numéro bon", "Date de sortie", "GIE", "Zone", "Origine",
    "Matériel", "Unité", "Quantité"
]);

// Requête pour exporter les bons avec leurs inventaires (s'il y en a)
$sql = "
SELECT 
    b.num_bon,
    b.date_bon,
    b.gie,
    b.zone,
    b.origine,
    m.nom AS materiel,
    im.unite,
    im.quantite
FROM bon_sortie b
LEFT JOIN inventaire_materiel im ON im.bon_id = b.id
LEFT JOIN materiel m ON m.id = im.materiel
ORDER BY b.date_bon DESC, b.num_bon DESC
";

$stmt = $pdo->query($sql);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['num_bon'],
        $row['date_bon'],
        $row['gie'],
        $row['zone'],
        $row['origine'],
        $row['materiel'],
        $row['unite'],
        $row['quantite']
    ]);
}

fclose($output);
exit;
?>
