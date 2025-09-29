<?php
require_once '../php/BD connexion/connexion.php';

if (isset($_POST['materiel_nom'], $_POST['materiel_unite'])) {
    $nom = trim($_POST['materiel_nom']);
    $unite = trim($_POST['materiel_unite']);

    if ($nom !== '' && $unite !== '') {
        // Insertion dans la table materiel
        $stmt = $pdo->prepare("INSERT INTO materiel (nom, unite) VALUES (:nom, :unite)");
        $stmt->execute([
            ':nom' => $nom,
            ':unite' => $unite
        ]);
        // Redirection vers la page de paramétrage
        header("Location: ../pages/parametrage.php");
        exit;
    } else {
        echo "⚠️ Veuillez remplir tous les champs.";
    }
} else {
    echo "⚠️ Données manquantes.";
}
