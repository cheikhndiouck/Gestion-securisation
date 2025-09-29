<?php
session_start();
require_once 'BD connexion/connexion.php'; // chemin vers ta connexion PDO

// Vérifier que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['gmail'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        header("Location: ../views/index.php?error=" . urlencode("Veuillez remplir tous les champs"));
        exit;
    }

    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        // Redirection vers Bons.php
        header("Location: ../views/Bons.php");
        exit;
    } else {
        // Login échoué
        header("Location: ../views/index.php?error=" . urlencode("Email ou mot de passe incorrect"));
        exit;
    }
} else {
    // Accès direct à traitement.php interdit
    header("Location: ../views/index.php");
    exit;
}
