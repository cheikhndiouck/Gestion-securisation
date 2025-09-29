<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// ce script permet de récupérer les matériels depuis la base de données

try {
    // Connexion à la BDD
   require_once 'BD connexion/connexion.php';

    $sql = "SELECT id, nom, unite FROM materiel"; // vérifie le nom exact de la table
    $stmt = $pdo->query($sql);
    $materiel = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($materiel, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
         