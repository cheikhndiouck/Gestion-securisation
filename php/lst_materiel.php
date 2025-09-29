<?php
// materiels.php

// Connexion à la base de données
try {
   require_once 'BD connexion/connexion.php';
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération des matériels
$stmt = $pdo->query("SELECT id, nom, unite FROM materiel ORDER BY nom ASC");
$materiels = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>