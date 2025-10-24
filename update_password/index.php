<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/update_password.css">
</head>

<body>

    <div class="container">
        <h2>Réinitialiser votre mot de passe 🔐</h2>
        
        <form class="register-form">
            <p>Veuillez entrer votre nouveau mot de passe :</p>
            <input type="password" placeholder="Nouveau mot de passe" required>
            <input type="password" placeholder="Confirmez le mot de passe" required>
            <br>
            <button type="submit" class="bouton">Valider</button>
        </form>

        <p class="message"><a href="../connexion/index.php">Retour à la connexion</a></p>
    </div>

</body>

</html>