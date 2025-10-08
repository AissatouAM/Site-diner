<?php
session_start();
require_once("../config/db_connect.php");

// Récupération des données du formulaire
$prenom = trim($_POST['prenom']);
$nom = trim($_POST['nom']);
$telephone = trim($_POST['telephone']);
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

// 1. Vérifier que tous les champs sont remplis
if (empty($prenom) || empty($nom) || empty($telephone) || empty($password) || empty($confirm)) {
    echo "Tous les champs sont obligatoires.";
    exit();
}

// 2. Vérifier que les mots de passe correspondent
if ($password !== $confirm) {
    echo "Les mots de passe ne correspondent pas.";
    exit();
}

// 3. Vérifier le format du numéro (9 chiffres)
if (!preg_match('/^[0-9]{9}$/', $telephone)) {
    echo "Le numéro de téléphone doit contenir exactement 9 chiffres.";
    exit();
}

// 4. Vérifier que le numéro n'existe pas déjà
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE numero = ?");
$stmt->execute([$telephone]);
if ($stmt->fetch()) {
    echo "Ce numéro est déjà utilisé.";
    exit();
}

// 5. Hacher le mot de passe
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 6. Insérer l'utilisateur dans la base
$stmt = $pdo->prepare("INSERT INTO utilisateurs (prenom, nom, numero, mot_de_passe) VALUES (?, ?, ?, ?)");
$stmt->execute([$prenom, $nom, $telephone, $hashedPassword]);

// 7. Démarrer la session
$_SESSION['utilisateur_id'] = $pdo->lastInsertId();
$_SESSION['prenom'] = $prenom;
$_SESSION['nom'] = $nom;

// 8. Rediriger vers la page d'accueil
$_SESSION['inscription_reussie'] = true;
header("Location: ../accueil2/index.php");
exit();
?>
