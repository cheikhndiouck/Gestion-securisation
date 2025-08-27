<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = new PDO("mysql:host=localhost;dbname=gestion-database;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id, nom, unite FROM materiel";
    $stmt = $pdo->query($sql);
    $materiels = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste déroulante des matériels</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/global.css">
</head>
<body>
<?php include './components/header.php'; ?>


    <div class="container mt-5" style="max-width: 500px;">
        <h1 class="mb-4">Sélectionner un matériel</h1>
        <form>
            <div class="mb-3 dropdown">
                <label for="materielSelect" class="form-label">Matériel :</label>
                <select id="materielSelect" class="form-select">
                    <option value="">-- Choisir --</option>
                    <?php foreach ($materiels as $mat): ?>
                        <option value="<?= htmlspecialchars($mat['id']) ?>">
                            <?= htmlspecialchars($mat['nom']) ?> (<?= htmlspecialchars($mat['unite']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</body>
</html>