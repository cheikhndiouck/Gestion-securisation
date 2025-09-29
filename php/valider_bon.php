<?php
// valider_bon.php
session_start();

if (isset($_SESSION['bon_id'])) {
    $bon_id = (int) $_SESSION['bon_id'];

    // Quand on valide définitivement le bon, on libère la session
    unset($_SESSION['bon_id']);

    $_SESSION['message'] = "✅ Bon #$bon_id validé et enregistré avec succès.";
} else {
    $_SESSION['message'] = "⚠️ Aucun bon en cours à valider.";
}

// Rediriger vers la liste des bons
header("Location: ../views/Bons.php");
exit();
