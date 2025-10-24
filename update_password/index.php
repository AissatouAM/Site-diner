<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/update_password.css">
    <style>
.message.success {
    background: rgba(0, 128, 0, 0.1) ;
    color: #155724;
    border: 1px solid rgba(0, 128, 0, 0.3);
    font-family: 'Dancing Script', sans-serif;
    font-size: 1.2rem;
}
.message.error {
     font-family: 'Dancing Script', sans-serif;
    font-size: 1.2rem;
    background: rgba(255, 0, 0, 0.1);
    color: red;
    padding: 10px;
    border-radius: 10px;
    border: 2px solid rgba(255, 0, 0, 0.3);
} 
</style>
</head>

<body>

    <div class="container">
        <h2>Réinitialiser votre mot de passe 🔐</h2>

        <?php if(!empty($message)): ?>
            <div class="message <?php echo $type; ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <form class="register-form">
            <p>Veuillez entrer votre nouveau mot de passe :</p>
            <input type="password" placeholder="Nouveau mot de passe" required>
            <input type="password" placeholder="Confirmez le mot de passe" required>
            <br>
            <button type="submit" class="bouton">Valider</button>
        </form>

        <p class="message"><a href="../connexion/index.php">Retour à la connexion</a></p>
    </div>

</body>

</html>