<?php
// save_bon.php
session_start();
require_once '../php/BD connexion/connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numBon  = $_POST['numBon'] ?? null;
    $dateBon = $_POST['dateBon'] ?? null;
    $gie     = $_POST['gie'] ?? null;
    $zone    = $_POST['zone'] ?? null;
    $origine = $_POST['origine'] ?? null;

    // Vérification que tous les champs sont remplis
    if (!$numBon || !$dateBon || !$gie || !$zone || !$origine) {
        $_SESSION['message'] = "⚠️ Tous les champs sont requis.";
        header("Location: ../views/Bons.php");
        exit();
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO bon_sortie (num_bon, date_bon, gie, zone, origine)
            VALUES (:numBon, :dateBon, :gie, :zone, :origine)
        ");
        $stmt->execute([
            ':numBon'  => $numBon,
            ':dateBon' => $dateBon,
            ':gie'     => $gie,
            ':zone'    => $zone,
            ':origine' => $origine
        ]);

        // Stocker l'id du bon créé pour l'inventaire
$bon_id = $pdo->lastInsertId();
header("Location: ../views/inventaire.php?id=$bon_id");
exit();
        $_SESSION['message'] = "✅ Bon de sortie créé avec succès.";
        $_SESSION['bon_id'] = $bon_id;

        // Redirection vers inventaire.php avec le bon_id
        header("Location: ../views/inventaire.php?bon_id=" . $_SESSION['bon_id']);
        exit();

    } catch (Exception $e) {
        $_SESSION['message'] = "⚠️ Erreur lors de la création du bon : " . $e->getMessage();
        header("Location: ../views/Bons.php");
        exit();
    }
} else {
    header("Location: ../views/Bons.php");
    exit();
}
