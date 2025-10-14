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
  <link rel="stylesheet" href="../../assets/css/connexion.css">
  <style>
    .message-erreur {
      color: red;
      text-align: center;
      font-family: 'Dancing Script', cursive;
      font-size: 1.2rem;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Connexion</h2>

    <?php
    if (isset($_SESSION['erreur_connexion'])) {
        echo "<p class='message-erreur'>" . $_SESSION['erreur_connexion'] . "</p>";
        unset($_SESSION['erreur_connexion']);
    }
    ?>

    <form class="register-form" method="post" action="../php/index.php">
      <input type="tel" name="telephone"
             placeholder="Numéro de téléphone"
             pattern="^(77|76|75|78|71|70)[0-9]{7}$"
             title="Veuillez entrer un numéro de téléphone valide (ex: 771234567)"
             value="<?= isset($_SESSION['last_telephone']) ? htmlspecialchars($_SESSION['last_telephone']) : '' ?>"
             required>

      <input type="password" id="password" name="mdp" placeholder="Mot de passe" required><br><br>

      <button type="submit" class="bouton">Se connecter</button><br><br>

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

