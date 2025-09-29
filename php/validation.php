<?php
// Affichage des erreurs pour debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    ;

//     echo "Bonjour, $nom !<br>";
//     echo "Ton email est : $email";
// } else {
//     echo "Aucune donnée envoyée.";
// }

try {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("ID de facture manquant.");
    }

    $facture_id = intval($_GET['id']);

    // Connexion BDD
    $pdo = new PDO("mysql:host=localhost;dbname=gestion-database;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération facture
    $stmt = $pdo->prepare("SELECT * FROM facture WHERE id = ?");
    $stmt->execute([$facture_id]);
    $facture = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$facture) {
        throw new Exception("Facture introuvable.");
    }

    // Récupération des détails
    $stmt = $pdo->prepare("SELECT fd.*, m.nom, m.unite
                           FROM facture_details fd
                           JOIN materiel m ON fd.materiel_id = m.id
                           WHERE fd.facture_id = ?");
    $stmt->execute([$facture_id]);
    $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

