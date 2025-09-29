<?php

session_start();
require_once '../php/BD connexion/connexion.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les informations actuelles de l'utilisateur
$stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = trim($_POST['email'] ?? '');
    $current_password = trim($_POST['current_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Vérifier que l'utilisateur a saisi son mot de passe actuel
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $hash = $stmt->fetchColumn();

    if (!password_verify($current_password, $hash)) {
        $message = "Mot de passe actuel incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $message = "Le nouveau mot de passe et la confirmation ne correspondent pas.";
    } else {
        // Mise à jour de l'email et mot de passe si fourni
        $update_sql = "UPDATE users SET email = :email";
        $params = [':email' => $new_email, ':id' => $user_id];

        if (!empty($new_password)) {
            $update_sql .= ", password = :password";
            $params[':password'] = password_hash($new_password, PASSWORD_DEFAULT);
        }

        $update_sql .= " WHERE id = :id";

        $stmt = $pdo->prepare($update_sql);
        $stmt->execute($params);

        $_SESSION['email'] = $new_email; // Mettre à jour la session
        $message = "Profil mis à jour avec succès.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/global.css?v=1.2">
</head>
<body class="bg-light">
    <?php include './components/header.php'; ?>
    <div class="container p-4">
        <h2>Mon Profil</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Mot de passe actuel</label>
                <input type="password" name="current_password" class="form-control" placeholder="Mot de passe actuel" required>
            </div>
            <div class="mb-3">
                <label>Nouveau mot de passe</label>
                <input type="password" name="new_password" class="form-control" placeholder="Nouveau mot de passe">
            </div>
            <div class="mb-3">
                <label>Confirmer le nouveau mot de passe</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirmer mot de passe">
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="Bons.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</body>
</html>
