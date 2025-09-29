<?php
// Récupérer l'ID du bon depuis l'URL
$bon_id = $_GET['id'] ?? null;
if (!$bon_id) {
    die("⚠️ Bon non trouvé");
}

// Récupérer les informations du bon
$stmt = $pdo->prepare("SELECT * FROM bon_sortie WHERE id = :id");
$stmt->execute([':id' => $bon_id]);
$bon = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$bon) {
    die("⚠️ Bon non trouvé");
}

// Récupérer les inventaires liés à ce bon
$stmt = $pdo->prepare("
    SELECT im.id, m.nom AS materiel, im.quantite, im.unite
    FROM inventaire_materiel im
    JOIN materiel m ON m.id = im.materiel
    WHERE im.bon_id = :bon_id
");
$stmt->execute([':bon_id' => $bon_id]);
$inventaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>