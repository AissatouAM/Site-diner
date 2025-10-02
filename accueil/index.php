<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dîner du Département Informatique</title>
    <link rel="stylesheet" href="../assets/css/accueil.css">
</head>
<body>
    <div class="stars"></div>
    <header>
        <h1 class="gold-glow">✨ Dîner du Département Génie Informatiqueee ✨</h1>
        <p class="slogan">Un événement d’exception, une nuit inoubliable !</p>
        <p class="intro">
            Bienvenue sur <b>LA</b> plateforme du dîner où l’élégance rencontre l’innovation. Prépare-toi à vivre une soirée festive, pleine de surprises, de rires et de rencontres !
        </p>
    </header>
    <section class="actions">
        <a href="../inscription/" class="btn">Inscription</a>
        <a href="../connexion/" class="btn">Connexion</a>
    </section>
    <section class="explainer">
        <h2>Pourquoi ce dîner ?</h2>
        <p>
            L’occasion parfaite de célébrer la réussite du département informatique : networking, musique live, et la prestigieuse élection de notre Roi et Reine ! Laisse-toi porter par l’ambiance dorée et partage ce moment unique entre étudiants et enseignants.
        </p>
        <ul>
            <li>Inscris-toi et réserve ta place</li>
            <li>Vote pour le Roi & la Reine du bal</li>
            <li>Découvre le menu et les animations exclusives</li>
            <li>Retrouve toutes les infos pratiques : lieu, date, dress code</li>
        </ul>
    </section>
    <section class="countdown">
        <h2>⏳ Compte à rebours avant le dîner</h2>
        <div id="timer"></div>
    </section>
    <section class="theme">
        <h2>Thème de la soirée</h2>
        <p class="theme-desc">
            Plonge dans l’univers <b>noir & doré</b> : élégance, raffinement et éclat seront les mots d’ordre.<br>
            Habille-toi, brille, et laisse les étoiles guider ta soirée !
        </p>
    </section>
    <footer>
        <p>© Département Informatique 2025 – Dîner de prestige.</p>
    </footer>
    <script src="../assets/js/countdown.js"></script>
    <script src="../assets/js/stars.js"></script>
    <script>
    function pleaseLogin(action) {
        alert("Vous devez être connecté pour " + (action === 'voter' ? 'voter' : 'déposer une candidature') + " !");
        window.location.href = "../connexion/";
    }
    </script>
</body>
</html>
