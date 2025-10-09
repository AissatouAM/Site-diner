<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/inscription.css">
    <title>inscription</title>
</head>

<body>
    <div class="fond"></div>
    <div class="centre"></div>
    <div class="container">
        <h2>Inscription</h2>
        <form class="register-form" method="POST" action="../php/index.php">
            <input type="text" name="prenom" placeholder="Prénom" required />
            <input type="text" name="nom" placeholder="Nom" required />
            <input type="tel" name="telephone" pattern="[0-9]{9}" title="Veuillez entrer un numéro de téléphone valide (ex: 777777777)" 
             placeholder="Numéro de téléphone" pattern="^(77|76|75|78|71|70)[0-9]{7}$" maxlength="9" required/>
            <input type="password" name="password" placeholder="Mot de passe" required/>
            <input type="password" name="confirm_passwoed" placeholder="Confirmer le mot de passe" required />
            <button type="submit" class="bouton">S'inscrire</button>
            
            
            <p class="message">Déjà inscrit? <a href="../../connexion/html/index.php">Se connecter</a></p>
        </form>
    </div>
</body>

</html>