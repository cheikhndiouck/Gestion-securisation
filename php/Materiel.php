<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=gestion-database;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id, nom, unite FROM materiel"; // vérifie le nom exact de la table
    $stmt = $pdo->query($sql);
    $materiel = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($materiel, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
         