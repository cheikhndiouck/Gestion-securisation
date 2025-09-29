<?php
// Activation des erreurs PHP pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Tentative de connexion
    require_once '../php/BD connexion/connexion.php';
    echo "✅ Connexion à la base de données réussie<br>";

    // Vérification des tables
    $tables = ['fiche_reception', 'materiels_reception', 'receptions_compteurs'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Table $table existe<br>";
            
            // Afficher la structure de la table
            $structure = $pdo->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
            echo "<pre>Structure de $table :\n";
            print_r($structure);
            echo "</pre>";
        } else {
            echo "❌ Table $table n'existe pas<br>";
        }
    }

    // Vérifier les permissions de l'utilisateur
    $grants = $pdo->query("SHOW GRANTS")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>Permissions de l'utilisateur :\n";
    print_r($grants);
    echo "</pre>";

} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
}