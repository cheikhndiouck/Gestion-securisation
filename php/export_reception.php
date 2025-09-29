<?php
try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=localhost;dbname=gestion-database;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête principale
    $stmt = $pdo->query("
        SELECT f.numero_fiche, f.gie, f.date_reception, f.zone, m.id_materiel, m.qte_constatee
        FROM fiche_reception f
        JOIN materiels_reception m ON f.id = m.id_reception
        ORDER BY f.date_reception DESC
    ");

    // Préparation des en-têtes HTTP pour le téléchargement CSV
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=rapport_fiches-reception.csv");

    // Ouverture du flux de sortie
    $output = fopen("php://output", "w");

    // En-têtes de colonnes
    fputcsv($output, ["Numéro fiche", "GIE", "Date réception", "Zone", "ID Matériel", "Quantité constatée"]);

    // Remplissage du fichier CSV
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;

} catch (PDOException $e) {
    echo "Erreur de connexion ou d'exécution SQL : " . $e->getMessage();
    exit;
}
?>
