<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription – Dîner Informatique</title>
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
        <h2 class="gold-glow">Inscription</h2>
        <form action="process.php" method="post">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" required>
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" required>
            <label for="numero">Numéro de téléphone</label>
            <input type="text" name="numero" id="numero" required>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" class="btn">S’inscrire</button>
        </form>
        <p class="message">Déjà inscrit ? <a href="../connexion/">Connecte-toi ici</a></p>
    </div>
    <script src="../assets/js/stars.js"></script>
</body>
</html>
