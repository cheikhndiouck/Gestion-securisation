<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=gestion-database;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupère le JSON envoyé
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) throw new Exception("Données invalides");

    $stmt = $pdo->prepare("INSERT INTO inventaire (nom, quantite, unite, date_enregistrement) VALUES (?, ?, ?, NOW())");

    foreach ($data as $item) {
        $stmt->execute([$item['nom'], $item['quantite'], $item['unite']]);
    }

    echo json_encode(["message" => "Inventaire enregistré avec succès !"]);

} catch (Exception $e) {
    echo json_encode(["message" => "Erreur : " . $e->getMessage()]);
}
?>
