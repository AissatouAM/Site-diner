<?php
session_start();
require_once("../config/db_connect.php");

$prenom = $_POST['prenom'];
$nom = $_POST['nom'];
$telephone = $_POST['telephone'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if ($password !== $confirm) {
    echo "Les mots de passe ne correspondent pas.";
    exit();
}

$conn = new PDO("mysql:host=localhost;dbname=site_diner", "root", "");


$stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE telephone = ?");
$stmt->execute([$telephone]);
if ($stmt->fetch()) {
    echo "Ce numéro est déjà utilisé.";
    exit();
}


$stmt = $conn->prepare("INSERT INTO utilisateurs (prenom, nom, telephone, mot_de_passe) VALUES (?, ?, ?, ?)");
$stmt->execute([$prenom, $nom, $telephone, $password]);


$_SESSION['prenom'] = $prenom;
$_SESSION['nom'] = $nom;
header("Location: ../accueil2/index.php");
exit();
?>
