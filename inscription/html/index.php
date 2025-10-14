<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/inscription.css">
    <title>Inscription</title>
    <style>
        p[style*="color: red"] {
            font-family: 'Dancing Script', cursive;
            font-size: 1.2rem;
            background: rgba(255, 0, 0, 0.1);
            padding: 10px;
            border-radius: 10px;
            border: 2px solid rgba(255, 0, 0, 0.3);
        }
    </style>
</head>

<body>
    <div class="fond"></div>
    <div class="centre"></div>
    <div class="container">
        <h2>Inscription</h2>

        <?php
        session_start();
        $confirm_error = isset($_GET['confirm_error']) && $_GET['confirm_error'] == 1;
        $pref_prenom = isset($_GET['prenom']) ? htmlspecialchars($_GET['prenom']) : '';
        $pref_nom = isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : '';
        $pref_telephone = isset($_GET['telephone']) ? htmlspecialchars($_GET['telephone']) : '';

        // Message si le numéro existe déjà
        if (isset($_SESSION['erreur_numero'])) {
            echo "<p style='color: red; text-align:center;'>" . $_SESSION['erreur_numero'] . "</p>";
            unset($_SESSION['erreur_numero']);
        }

        //Message si le format du numéro est invalide
        if (isset($_SESSION['erreur_numero_invalide'])) {
            echo "<p style='color: red; text-align:center;'>" . $_SESSION['erreur_numero_invalide'] . "</p>";
            unset($_SESSION['erreur_numero_invalide']);
        }
        ?>

        <form class="register-form" method="POST" action="../php/index.php">
            <input type="text" name="prenom" placeholder="Prénom" value="<?= $pref_prenom ?>" required />
            <input type="text" name="nom" placeholder="Nom" value="<?= $pref_nom ?>" required />
            <input type="tel" name="telephone" pattern="[0-9]{9}" title="Veuillez entrer un numéro de téléphone valide (ex: 777777777)" 
             placeholder="Numéro de téléphone" pattern="^(77|76|75|78|71|70)[0-9]{7}$" maxlength="9" value="<?= $pref_telephone ?>" required/>
            <input type="password" name="password" placeholder="Mot de passe" required/>
            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required />
            
            <button type="submit" class="bouton">S'inscrire</button>
             
            <p class="message">Déjà inscrit? <a href="../../connexion/html/index.php">Se connecter</a></p>
        </form>
        <?php
            if (isset($_SESSION['erreur_mdp'])) {
                echo "<p style='color: red; text-align:center;'>" . $_SESSION['erreur_mdp'] . "</p>";
                unset($_SESSION['erreur_mdp']);
            }
        ?>

    </div>
</body>

</html>