<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/inscription.css">
    <title>inscription</title>
</head>

<body>
    <div class="fond"></div>
    <div class="centre"></div>
    <div class="container">
        <h2>Inscription</h2>
        <form class="register-form">
            <input type="tel" pattern="[0-9]{9}" title="Veuillez entrer un numéro de téléphone valide (ex: 777777777)" placeholder="Numéro de téléphone" required/>
            <input type="password" placeholder="Mot de passe" required/>
            <input type="password" placeholder="Confirmer le mot de passe" required />
            <button type="submit" class="bouton" onclick="window.location.href='../accueil2/index.php'">S'inscrire</button>

            <p class="message">Déjà inscrit? <a href="../connexion/index.php">Se connecter</a></p>
        </form>
    </div>
</body>

</html>