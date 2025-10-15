<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
  <title>Connexion</title>
  <link rel="stylesheet" href="../assets/css/connexion.css">
  <style>
    .message-erreur {
      color: red;
      text-align: center;
      font-family: 'Dancing Script', cursive;
      font-size: 1.2rem;
    }

    .home-link {
        text-decoration: none;
        color: #daa520; /* Couleur dorée */
        display: block;
        margin-bottom: 10px;
        transition: transform 0.3s ease;
    }

    .home-link:hover {
        transform: scale(1.1);
    }
  </style>
</head>

<body>
  <div class="container">
    <a href="../Accueil/index.php" class="home-link" title="Retour à l'accueil">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
        </svg>
    </a>
    <h2>Connexion</h2>

    <?php
    if (isset($_SESSION['erreur_connexion'])) {
        echo "<p class='message-erreur'>" . $_SESSION['erreur_connexion'] . "</p>";
        unset($_SESSION['erreur_connexion']);
    }
    ?>

    <form class="register-form" method="post" action="./php/index.php">
      <input type="tel" name="telephone"
             placeholder="Numéro de téléphone"
             pattern="^(77|76|75|78|71|70)[0-9]{7}$"
             title="Veuillez entrer un numéro de téléphone valide (ex: 771234567)"
             value="<?= isset($_SESSION['last_telephone']) ? htmlspecialchars($_SESSION['last_telephone']) : '' ?>"
             required>

      <input type="password" id="password" name="mdp" placeholder="Mot de passe" required><br><br>

      <button type="submit" class="bouton">Se connecter</button><br><br>

      <p class="message">Pas encore de compte? <a href="../inscription/index.php">S'inscrire</a></p>

      <a href="#" onclick="afficherMessage(); return false;">Mot de passe oublié ?</a>
      <div class="message" aria-live="polite"></div>
    </form>
  </div>

  <script>
    function afficherMessage() {
      const zoneMessage = document.querySelector(".message");
      zoneMessage.textContent = "Veuillez contacter l'administrateur du site pour plus d'aide.";
      zoneMessage.style.color = "orange";

      setTimeout(() => {
        zoneMessage.textContent = "";
      }, 5000);
    }
  </script>
</body>

</html>

