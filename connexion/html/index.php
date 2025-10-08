<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>connexion</title>
    <link rel="stylesheet" href="../../assets/css/connexion.css">
</head>

<body>
    <div class="container">
        <h2>Connexion</h2>
        <form class="register-form" method="post" action="../php/index.php">
            <input type="tel" name="numero" placeholder="Numéro de téléphone" pattern="^(77|76|75|78|71|70)[0-9]{7}$" title="Veuillez entrer un numéro de téléphone valide (ex: 771234567)" required>
            <input type="password" id="password" name="mdp" placeholder="Mot de passe" required><br><br>
            <button type="submit" class="bouton">Se connecter</button><br><br>
            <a>Mot de passe oublié ?</a>
        </form>
    </div>
</body>

</html>