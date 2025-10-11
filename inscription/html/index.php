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


    <!-- Aficher un message simple si la confirmation du mot de passe a échoué -->
    <?php
    $confirm_error = isset($_GET['confirm_error']) && $_GET['confirm_error'] == 1;
    $pref_prenom = isset($_GET['prenom']) ? htmlspecialchars($_GET['prenom']) : '';
    $pref_nom = isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : '';
    $pref_telephone = isset($_GET['telephone']) ? htmlspecialchars($_GET['telephone']) : '';
    ?>

        <form class="register-form" method="POST" action="../php/index.php">
            <input type="text" name="prenom" placeholder="Prénom" value="<?= $pref_prenom ?>" required />
            <input type="text" name="nom" placeholder="Nom" value="<?= $pref_nom ?>" required />
            <input type="tel" name="telephone" pattern="[0-9]{9}" title="Veuillez entrer un numéro de téléphone valide (ex: 777777777)" 
             placeholder="Numéro de téléphone" pattern="^(77|76|75|78|71|70)[0-9]{7}$" maxlength="9" value="<?= $pref_telephone ?>" required/>
            <input type="password" name="password" placeholder="Mot de passe" required/>
            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required />
            
            
            <!--Affichage du message-->
            <?php if ($confirm_error): ?>
                <div style="color:red;margin-top:6px;">Les mot de passe ne correspondent pas</div>
            <?php endif; ?>


            <button type="submit" class="bouton">S'inscrire</button>
            
            
            <p class="message">Déjà inscrit? <a href="../../connexion/html/index.php">Se connecter</a></p>
        </form>
    </div>
</body>

</html>