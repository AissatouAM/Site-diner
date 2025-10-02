<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion – Dîner Informatique</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <div class="stars"></div>
    <div class="auth-card">
        <a href="../accueil/" class="icon-home" title="Retour à l’accueil">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                <path d="M3 10.5L12 4L21 10.5V18a2 2 0 0 1-2 2h-4v-5h-6v5H5a2 2 0 0 1-2-2v-7.5Z" stroke="#ffd700" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </a>
        <h2 class="gold-glow">Connexion</h2>
        <form action="login.php" method="post">
            <label for="numero">Numéro de téléphone</label>
            <input type="text" name="numero" id="numero" required>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" class="btn">Se connecter</button>
        </form>
        <p class="message">Pas encore de compte ? <a href="../inscription/">Inscris-toi ici</a></p>
    </div>
    <script src="../assets/js/stars.js"></script>
</body>
</html>
