<?php
session_start();
require_once('config/db_connect.php');

// --- 1. Vérifier si un admin existe déjà ---
$stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE role = 'admin' LIMIT 1");
$stmt->execute();

if ($stmt->fetch()) {
    // Un admin existe, stocker un message et rediriger
    $_SESSION['admin_message'] = 'Un compte administrateur existe déjà.';
    header('Location: accueil1/index.php');
    exit();
}

$error_messages = [];

// --- 2. Traiter le formulaire si soumis ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($prenom) || empty($nom) || empty($telephone) || empty($password)) {
        $error_messages[] = "Tous les champs sont obligatoires.";
    }
    if ($password !== $confirm_password) {
        $error_messages[] = "Les mots de passe ne correspondent pas.";
    }
    if (!preg_match("/^(77|76|75|78|71|70)[0-9]{7}$/", $telephone)) {
        $error_messages[] = "Le format du numéro de téléphone est invalide.";
    }

    // Vérifier si le téléphone n'est pas déjà utilisé
    $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE telephone = ?");
    $stmt->execute([$telephone]);
    if ($stmt->fetch()) {
        $error_messages[] = "Ce numéro de téléphone est déjà utilisé.";
    }

    // Si pas d'erreurs, créer l'admin
    if (empty($error_messages)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare(
            "INSERT INTO utilisateurs (prenom, nom, telephone, mot_de_passe, role) VALUES (?, ?, ?, ?, 'admin')"
        );
        
        if ($stmt->execute([$prenom, $nom, $telephone, $hashedPassword])) {
            // Afficher un message de succès et arrêter le script
            echo '
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>Succès</title>
                    <link rel="stylesheet" href="assets/css/inscription.css">
                    <style>
                        body { align-items: center; justify-content: center; height: 100vh; }
                        .container { background: rgba(0, 0, 0, 0.7); padding: 40px; border-radius: 20px; text-align: center; color: white; }
                        h2 { color: #28a745; }
                        a { color: #daa520; font-size: 1.2rem; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2>✅ Administrateur créé avec succès !</h2>
                        <p>Vous pouvez maintenant vous connecter via la page de connexion normale.</p>
                        <a href="connexion/html/index.php">Aller à la page de connexion</a>
                    </div>
                </body>
                </html>
            ';
            exit();
        } else {
            $error_messages[] = "Une erreur est survenue lors de la création du compte.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration de l'Administrateur</title>
    <link rel="stylesheet" href="assets/css/inscription.css">
    <style>
        .error-box {
            background: rgba(255, 0, 0, 0.1);
            border: 2px solid rgba(255, 0, 0, 0.3);
            color: red;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-family: 'Dancing Script', cursive;
        }
        .error-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="fond"></div>
    <div class="container">
        <h2>Création du Premier Administrateur</h2>
        <p style="font-size: 1.1rem; color: #ccc; margin-bottom: 20px;">Ce formulaire n'est accessible qu'une seule fois pour initialiser le site.</p>

        <?php if (!empty($error_messages)): ?>
            <div class="error-box">
                <ul>
                    <?php foreach ($error_messages as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="register-form" method="POST" action="setup_admin.php">
            <input type="text" name="prenom" placeholder="Prénom" required value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
            <input type="text" name="nom" placeholder="Nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
            <input type="tel" name="telephone" placeholder="Numéro de téléphone" required pattern="^(77|76|75|78|71|70)[0-9]{7}$" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
            
            <button type="submit" class="bouton">Créer l'Administrateur</button>
        </form>
    </div>
</body>
</html>
