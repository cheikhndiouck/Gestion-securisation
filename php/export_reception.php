<?php
require_once './BD connexion/connexion.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=receptions.csv');

$output = fopen('php://output', 'w');

// En-têtes du fichier
fputcsv($output, ['Numéro de Fiche', 'Matériel', 'Quantité', 'Unité', 'Numéros de Compteurs']);

// Requête
$sql = "
    SELECT fr.numero_fiche, 
           m.nom AS materiel, 
           mr.qte_constatee, 
           m.unite,
           GROUP_CONCAT(rc.numero_compteur SEPARATOR ', ') AS compteurs
    FROM fiche_reception fr
    LEFT JOIN materiels_reception mr 
        ON fr.id = mr.fiche_reception_id
    LEFT JOIN materiel m 
        ON mr.materiel_id = m.id
    LEFT JOIN receptions_compteurs rc
        ON fr.id = rc.fiche_reception_id
    GROUP BY fr.id, m.nom, mr.qte_constatee, m.unite
    ORDER BY fr.id DESC
";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Écriture des données
foreach ($rows as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit;
