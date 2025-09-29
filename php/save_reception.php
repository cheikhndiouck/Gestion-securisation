<?php
session_start();
require_once 'BD connexion/connexion.php';

// Activation du mode debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        'success' => false,
        'message' => "❌ Méthode non autorisée"
    ]);
    exit;
}

try {
    // Récupération et nettoyage des données
    $gie = trim($_POST["gie"] ?? '');
    $dateReception = trim($_POST["dateReception"] ?? '');
    $zone = trim($_POST["zone"] ?? '');
    $materiels = $_POST["materiel"] ?? [];
    $unites = $_POST["unite"] ?? [];
    $quantites = $_POST["quantite"] ?? [];
    $compteurs = $_POST["compteurs"] ?? '';
    
    // Validation des champs obligatoires
    if (empty($gie) || empty($dateReception) || empty($zone)) {
        throw new Exception("⚠️ Veuillez remplir tous les champs obligatoires (GIE, date, zone).");
    }
    if (empty($materiels) || empty($quantites)) {
        throw new Exception("⚠️ Veuillez ajouter au moins un matériel avec une quantité.");
    }

    // Générer numéro de fiche (format : GIE-ZONE-ANNÉE-N°)
    $annee = date("Y");
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM fiche_reception WHERE YEAR(date_reception) = ?");
    $stmt->execute([$annee]);
    $nbFiches = $stmt->fetchColumn() + 1;
    $numero_fiche = "GIE-{$zone}-{$annee}-" . str_pad($nbFiches, 3, "0", STR_PAD_LEFT);

    // Début de la transaction
    $pdo->beginTransaction();

    // 1. Insertion de la fiche de réception
    $stmt = $pdo->prepare("
        INSERT INTO fiche_reception (numero_fiche, gie, zone, date_reception) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$numero_fiche, $gie, $zone, $dateReception]);
    $fiche_id = $pdo->lastInsertId();

    // 2. Insertion des matériels
    $stmt = $pdo->prepare("
        INSERT INTO materiels_reception (fiche_reception_id, materiel_id, unite, qte_constatee) 
        VALUES (?, ?, ?, ?)
    ");
    
    foreach ($materiels as $i => $materiel_id) {
        if (!empty($quantites[$i])) {
            $stmt->execute([
                $fiche_id,
                $materiel_id,
                $unites[$i] ?? '',
                $quantites[$i]
            ]);
        }
    }

    // 3. Insertion des compteurs
    if (!empty($compteurs)) {
        $stmt = $pdo->prepare("
            INSERT INTO receptions_compteurs (fiche_reception_id, numero_compteur) 
            VALUES (?, ?)
        ");
        
        $compteursList = array_filter(
            explode("\n", trim($compteurs)),
            function($item) { return !empty(trim($item)); }
        );
        
        foreach ($compteursList as $compteur) {
            $stmt->execute([$fiche_id, trim($compteur)]);
        }
    }

    // Valider transaction
    $pdo->commit();

    // Réponse succès
    echo json_encode([
        'success' => true,
        'message' => "✅ Réception N° $numero_fiche enregistrée avec succès !",
        'numero_fiche' => $numero_fiche
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Réponse erreur
    echo json_encode([
        'success' => false,
        'message' => "❌ Erreur : " . $e->getMessage()
    ]);
}

// Assurer que le script se termine ici
exit;
