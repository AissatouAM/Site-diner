<?php
session_start();
require_once("../../config/db_connect.php");

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
if (!preg_match("/^(77|76|75|78|71|70)[0-9]{7}$/", $telephone)) {
    echo "Numéro invalide.";
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
$_SESSION['inscription_reussie'] = true;

echo "
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta http-equiv='refresh' content='3;url=../../connexion/html/index.php'>
    <title>Inscription réussie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .message-box {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #28a745;
        }
        p {
            color: #333;
        }
    </style>
</head>
<body>
    <div class='message-box'>
        <h2>✅ Inscription réussie !</h2>
        <p>Vous allez être redirigé vers la page de connexion dans quelques secondes...</p>
        <p><a href='../../connexion/html/index.php'>Cliquez ici si la redirection ne se fait pas.</a></p>
    </div>
</body>
</html>
";
exit();
?>
