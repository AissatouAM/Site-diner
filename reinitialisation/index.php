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
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
<title>Mot de passe oublié</title>
<link rel="stylesheet" href="../assets/css/reinitialisation.css">
<style>
.message[style*="color: red"] {
     font-family: 'Dancing Script', sans-serif;
    font-size: 1.2rem;
    background: rgba(255, 0, 0, 0.1);
    padding: 10px;
    border-radius: 10px;
    border: 2px solid rgba(255, 0, 0, 0.3);
}
.message.success {
    background: rgba(0, 128, 0, 0.1) ;
    color: #155724;
    border: 1px solid rgba(0, 128, 0, 0.3);
    font-family: 'Dancing Script', sans-serif;
    font-size: 1.2rem;
}
.message.error[style*="color: red"] {
     font-family: 'Dancing Script', sans-serif;
    font-size: 1.2rem;
    background: rgba(255, 0, 0, 0.1);
    padding: 10px;
    border-radius: 10px;
    border: 2px solid rgba(255, 0, 0, 0.3);
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
