<?php
session_start();
require_once("../../config/db_connect.php");

// Récupération des données du formulaire
$prenom = trim($_POST['prenom']);
$nom = trim($_POST['nom']);
$email = trim($_POST['email']);
$telephone = trim($_POST['telephone']);
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

// 1. Vérifier que tous les champs sont remplis
if (empty($prenom) || empty($nom) || empty($email) || empty($telephone) || empty($password) || empty($confirm)) {
    echo "Tous les champs sont obligatoires.";
    exit();
}

// 2. Vérifier que les mots de passe correspondent
if ($password !== $confirm) {
    $_SESSION['erreur_mdp'] = "Les mots de passe ne correspondent pas.";
    header("Location: ../index.php?prenom=" . urlencode($prenom) .
                          "&nom=" . urlencode($nom) .
                          "&telephone=" . urlencode($telephone) .
                          "&email=" . urlencode($email));
    exit();
}

// 3. Vérifier le format du numéro (9 chiffres)
if (!preg_match("/^(77|76|75|78|71|70)[0-9]{7}$/", $telephone)) {
    $_SESSION['erreur_numero_invalide'] = "Numéro invalide. Le numéro doit commencer par 77, 76, 75, 78, 71 ou 70 et contenir 9 chiffres.";
    header("Location: ../index.php?prenom=" . urlencode($prenom) .
                          "&nom=" . urlencode($nom) .
                          "&telephone=" . urlencode($telephone) .
                          "&email=" . urlencode($email));
    exit();
}

// 4. Vérifier que l'email est valide
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erreur_email'] = "L'adresse email est invalide.";
    header("Location: ../index.php?prenom=" . urlencode($prenom) .
                          "&nom=" . urlencode($nom) .
                          "&telephone=" . urlencode($telephone) .
                          "&email=" . urlencode($email));
    exit();
}

// 5. Vérifier si l'email est déjà utilisé
$stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    $_SESSION['erreur_email'] = "Cette adresse email est déjà utilisée.";
    header("Location: ../index.php?prenom=" . urlencode($prenom) .
                          "&nom=" . urlencode($nom) .
                          "&telephone=" . urlencode($telephone) .
                          "&email=" . urlencode($email));
    exit();
}

// 6. Vérifier que le numéro n'existe pas déjà
$stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE telephone = ?");
$stmt->execute([$telephone]);
if ($stmt->fetch()) {
    $_SESSION['erreur_numero'] = "Ce numéro est déjà utilisé.";
    header("Location: ../index.php?prenom=" . urlencode($prenom) .
                          "&nom=" . urlencode($nom) .
                          "&telephone=" . urlencode($telephone) .
                          "&email=" . urlencode($email));
    exit();
}

// 7. Hacher le mot de passe
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 8. Insérer l'utilisateur dans la base
$stmt = $pdo->prepare("INSERT INTO utilisateurs (prenom, nom, email, telephone, mot_de_passe) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$prenom, $nom, $email, $telephone, $hashedPassword]);

// 9. Démarrer la session
$_SESSION['utilisateur_id'] = $pdo->lastInsertId();
$_SESSION['prenom'] = $prenom;
$_SESSION['nom'] = $nom;
$_SESSION['inscription_reussie'] = true;

// 10. Afficher message de succès
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
            border: 2px solid rgba(218, 165, 32, 0.4);
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
        p { color:  #e0d6a0; }

        a {
            color: #ffa500;
            text-decoration: none;
            font-weight: 600;
            position: relative;
            transition: all 0.3s ease;
        }

        a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 0%;
            height: 2px;
            background: linear-gradient(90deg, #ffb733, #ffa500, #cc8400);
            transition: width 0.4s ease;
        }

        a:hover {
            color: #ffb733;
            text-shadow: 0 0 10px rgba(255, 165, 0, 0.8);
        }

        a:hover::after {
            width: 100%;
        }

        a:visited {
            color: #cc8400;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2>Inscription réussie !</h2>
        <p>Vous allez être redirigé vers la page de connexion dans quelques secondes...</p>
        <p><a href="../../connexion/index.php">Cliquez ici si la redirection ne se fait pas.</a></p>
    </div>
</body>
</html>
HTML;
exit();
?>
