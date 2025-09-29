<?php
session_start();
require_once '../php/BD connexion/connexion.php';

$bon_id = $_POST['bon_id'] ?? null;

if (!$bon_id) {
    $_SESSION['message'] = "⚠️ Aucun bon sélectionné.";
    header("Location: ../views/Bons.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $materiels = $_POST['materiel'] ?? [];
    $quantites = $_POST['quantite'] ?? [];
    $unites    = $_POST['unite'] ?? [];

    if (!empty($materiels)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO inventaire_materiel (bon_id, materiel, quantite, unite)
                VALUES (:bon_id, :materiel, :quantite, :unite)
            ");

            foreach ($materiels as $i => $m) {
                if (!empty($m) && !empty($quantites[$i]) && !empty($unites[$i])) {
                    $stmt->execute([
                        ':bon_id'   => $bon_id,
                        ':materiel' => $m,
                        ':quantite' => $quantites[$i],
                        ':unite'    => $unites[$i]
                    ]);
                }
            }

            $_SESSION['message'] = "✅ Inventaire enregistré pour le bon #$bon_id.";
            header("Location: ../views/Bons.php?success=1");
            exit();

        } catch (Exception $e) {
            $_SESSION['message'] = "⚠️ Erreur : " . $e->getMessage();
            header("Location: ../views/inventaire.php?id=$bon_id");
            exit();
        }
    } else {
        $_SESSION['message'] = "⚠️ Veuillez ajouter au moins un matériel.";
        header("Location: ../views/inventaire.php?id=$bon_id");
        exit();
    }
} else {
    header("Location: ../views/inventaire.php?id=$bon_id");
    exit();
}
