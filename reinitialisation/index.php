<?php
session_start();

// Récupérer le message et le type s'ils existent
$message = $_SESSION['message'] ?? '';
$type = $_SESSION['type'] ?? '';
unset($_SESSION['message'], $_SESSION['type']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mot de passe oublié</title>
<link rel="stylesheet" href="../assets/css/reinitialisation.css">
<style>
.message {
    padding: 10px 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-weight: bold;
    text-align: center;
}
.message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f25d6cff;
}
</style>
</head>
<body>
<div class="container">
    <h2>Mot de passe oublié 🔑</h2>

    <!-- Affichage du message -->
    <?php if(!empty($message)): ?>
        <div class="message <?php echo $type; ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire de réinitialisation -->
    <form method="POST" class="register-form" action="./php/index.php">
        <p>Entrez votre adresse email pour recevoir un lien de réinitialisation :</p>
        <input type="email" name="email" placeholder="Votre email" required>
        <br><br>
        <button type="submit" class="bouton">Envoyer le lien</button>
    </form>

    <p class="message"><a href="../connexion/index.php">Retour à la connexion</a></p>
</div>
</body>
</html>
