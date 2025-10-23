<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/reinitialisation.css">
    <title>mot de passe oublie</title>
</head>

<body>
    <div class="container">
        <h2>Mot de passe oublié 🔑</h2>
        <form method="POST" class="register-form" action="envoiemail.php">
            <p>Entrez votre adresse email pour recevoir un lien de réinitialisation :</p>
            <input type="email" name="email" placeholder="Votre email" required>
            <br>
            <button type="submit" class="bouton">Envoyer le lien</button>
        </form>
        <p class="message"><a href="../connexion/index.php">Retour à la connexion</a></p>
    </div>
</body>

</html>