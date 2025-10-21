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
   $_SESSION['erreur_mdp'] = "Les mots de passe ne correspondent pas." ;
   header("Location: ../index.php");
    exit();
}

// 3. Vérifier le format du numéro (9 chiffres)
if (!preg_match("/^(77|76|75|78|71|70)[0-9]{7}$/", $telephone)) {
    $_SESSION['erreur_numero_invalide'] = "Numéro invalide. Le numéro doit commencer par 77, 76, 75, 78, 71 ou 70 et contenir 9 chiffres.";
    header("Location: ../index.php");
    exit();
}

// 4. Vérifier que le numéro n'existe pas déjà
$stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE telephone = ?");
$stmt->execute([$telephone]);
if ($stmt->fetch()) {
    $_SESSION['erreur_numero'] = "Ce numéro est déjà utilisé.";
    header("Location: ../index.php");
    exit();
}

// 5. Hacher le mot de passe
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 6. Insérer l'utilisateur dans la base
$stmt = $pdo->prepare("INSERT INTO utilisateurs (prenom, nom, telephone, mot_de_passe) VALUES (?, ?, ?, ?)");
$stmt->execute([$prenom, $nom, $telephone, $hashedPassword]);

// 7. Démarrer la session
$_SESSION['utilisateur_id'] = $pdo->lastInsertId();
$_SESSION['prenom'] = $prenom;
$_SESSION['nom'] = $nom;
$_SESSION['inscription_reussie'] = true;

echo <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=../../connexion/index.php">
    <title>Inscription réussie</title>
    <style>
        body {
            font-family: 'Dancing Script', sans-serif;
            background-image: url("../../assets/images/background.jpg");
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            backdrop-filter: blur(2px);
            flex-direction: column;
            margin: 0;
            padding: 20px;
        }
        .message-box {
            background: rgba(0, 0, 0, 0.4);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.6);
            text-align: center;
        }
        h2 {
            color: rgb(218, 175, 32);
            font-family: 'Dancing Script', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            background: linear-gradient(45deg, #ffd903, #e5af4c, #7e741b, #aea33e);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 3s infinite linear;
        }
        @keyframes shine {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        p { color: #333; }
    </style>
</head>
<body>
    <div class="message-box">
        <h2>✅ Inscription réussie !</h2>
        <p>Vous allez être redirigé vers la page de connexion dans quelques secondes...</p>
        <p><a href="../../connexion/index.php">Cliquez ici si la redirection ne se fait pas.</a></p>
    </div>
</body>
</html>
HTML;
exit();
?>
