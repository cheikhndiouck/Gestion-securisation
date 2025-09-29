<?php
session_start();
require_once 'BD connexion/connexion.php';

// Activation du mode debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Méthode non autorisée");
    }

    // Vérification des champs obligatoires
    $required = ['numero_facture','zone','date_facturation','matricule_agent','fiche_reception_ids'];
    foreach ($required as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Le champ '$field' est obligatoire.");
        }
    }

    $numero_facture   = $_POST['numero_facture'];
    $zone             = $_POST['zone'];
    $date_facturation = $_POST['date_facturation'];
    $matricule_agent  = $_POST['matricule_agent'];
    $fiche_ids        = $_POST['fiche_reception_ids'];

    // Gestion du fichier uploadé
    $fichier_scan = null;
    if (!empty($_FILES['fichier_scan']['name'])) {
        $upload_dir = "../uploads/factures/";
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                throw new Exception("Impossible de créer le dossier uploads/");
            }
        }
        $fichier_scan = $upload_dir . time() . "_" . basename($_FILES['fichier_scan']['name']);
        if (!move_uploaded_file($_FILES['fichier_scan']['tmp_name'], $fichier_scan)) {
            throw new Exception("Erreur lors de l'upload du fichier.");
        }
    }

    // Calcul du montant total à partir des fiches sélectionnées
    $montant_total = 0;
    $details = [];
    foreach ($fiche_ids as $fiche_id) {
        $stmt = $pdo->prepare("SELECT mr.materiel_id, mr.qte_constatee, m.nom, m.prix_unitaire 
                               FROM materiels_reception mr 
                               JOIN materiel m ON mr.materiel_id = m.id 
                               WHERE mr.fiche_id = ?");
        $stmt->execute([$fiche_id]);
        $materiels = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($materiels as $mat) {
            $q = floatval($mat['qte_constatee']);
            $p = floatval($mat['prix_unitaire']);
            $montant = $q * $p;
            $montant_total += $montant;
            $details[] = [
                'materiel_id' => $mat['materiel_id'],
                'quantite' => $q,
                'prix_unitaire' => $p,
                'montant' => $montant
            ];
        }
    }

    // Insertion facture
    $stmt = $pdo->prepare("INSERT INTO facture (numero_facture, zone, date_facturation, matricule_agent, fichier_scan, montant_total) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$numero_facture, $zone, $date_facturation, $matricule_agent, $fichier_scan, $montant_total]);
    $facture_id = $pdo->lastInsertId();

    // Lier la facture aux fiches de réception
    foreach ($fiche_ids as $fiche_id) {
        $stmt = $pdo->prepare("INSERT INTO facture_reception (facture_id, fiche_id) VALUES (?, ?)");
        $stmt->execute([$facture_id, $fiche_id]);
    }

    // Insertion des détails matériels
    foreach ($details as $d) {
        $stmt = $pdo->prepare("INSERT INTO facture_details (facture_id, materiel_id, quantite, prix_unitaire, montant) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$facture_id, $d['materiel_id'], $d['quantite'], $d['prix_unitaire'], $d['montant']]);
    }

    echo "<h3>✅ Facture enregistrée avec succès !</h3>";
    echo "<p><a href='facturation.php'>Retour à la facturation</a></p>";

} catch (Exception $e) {
    echo "<h3>❌ Erreur :</h3> " . $e->getMessage();
}
