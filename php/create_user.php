<?php
require_once '../php/BD connexion/connexion.php'; // inclut ton $pdo existant

$email = "admin@test.com";
$password = password_hash("1234", PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
$stmt->execute([
    ':email' => $email,
    ':password' => $password
]);

echo "Utilisateur créé avec succès.";
