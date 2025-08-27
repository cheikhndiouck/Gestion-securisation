<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    // Connexion PDO
    $pdo = new PDO("mysql:host=localhost;dbname=gestion-database;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupère le JSON envoyé
    $data = json_decode(file_get_contents("php://input"), true);

    // Vérifie que c'est un tableau
    if (!$data || !is_array($data)) {
        throw new Exception("Format JSON attendu : tableau d'objets");
    }

    // Préparation de la requête pour insérer tous les champs
    $stmt = $pdo->prepare("
        INSERT INTO inventaire 
            (nom, quantite, unite, date_enregistrement) 
        VALUES (?, ?, ?, NOW())
    ");
// ...existing code...

    foreach ($data as $item) {
        // Vérifie que chaque clé existe
        if (!isset($item['nom'], $item['quantite'], $item['unite'])) {
            throw new Exception("Champs manquants dans un enregistrement");
        }

        $stmt->execute([
            $item['nom'],
            $item['quantite'],
            $item['unite'],
        ]);
    }

    // Réponse JSON
    echo json_encode([
        "message" => "Inventaire enregistré avec succès !",
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode(["message" => "Erreur : " . $e->getMessage()]);
}
?>
