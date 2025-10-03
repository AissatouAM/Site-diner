<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>connexion</title>
    <link rel="stylesheet" href="../assets/css/connexion.css">
</head>

<body>
    <div class="container">
        <h2>Connexion</h2>
        <form class="register-form">
            <input type="tel" placeholder="Numéro de téléphone" pattern="[0-9]{9}" title="Veuillez entrer un numéro de téléphone valide (ex: 777777777)" required>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required><br><br>
            <button type="submit" class="bouton" onclick="window.location.href='../accueil2/index.php'">Se connecter</button><br><br>
            <a>Mot de passe oublié ?</a>
        </form>
    </div>
</body>

</html>