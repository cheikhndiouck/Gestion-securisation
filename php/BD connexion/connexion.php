<?php
$pdo = new PDO("mysql:host=localhost;dbname=gestion-database;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupérer tous les bons
try {
    $stmt = $pdo->query("SELECT * FROM bon_sortie ORDER BY id DESC");
    $bons = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $bons = [];
    $_SESSION['message'] = "⚠️ Erreur lors de la récupération des bons : " . $e->getMessage();
}
?>
