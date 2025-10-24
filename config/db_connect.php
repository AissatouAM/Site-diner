<?php
$host = "localhost";
$username = "site-diner";
$password = "mafe-4444"; // Mets ton mot de passe MySQL ici
$database = "site_diner";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

