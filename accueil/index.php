<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/accueil.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&display=swap" rel="stylesheet">
    <title>accueil</title>
</head>
<body>
    <div class="background"></div>
    <main>
        <div class="logo">
            👑 ⚜ Coder's Dinner ⚜ 👑
        </div>
        <div data-target="2025-12-31T23:59:00" id="countdown" aria-live="polite">
            <h2>Compte a rebours jusqu'au diner</h2>
            <div class="grille" aria-hidden="false">
                <div class="item">
                    <div class="time-value" id="days">0</div>
                    <div class="mots">jours</div>
                </div>
                <div class="item">
                    <div class="time-value" id="hours">00</div>
                    <div class="mots">Heures</div>
                </div>
                <div class="item">
                    <div class="time-value" id="minutes">00</div>
                    <div class="mots">Minutes</div>
                </div>
                <div class="item">
                    <div class="time-value" id="seconds">00</div>
                    <div class="mots">Secondes</div>
                </div>
            </div>
            <div class="message" id="message" role="status" style="display: none;">
                💃🏼💃🏼l'heure est arrivee sortez vos plus beaux sangse !💃🏼💃🏼
            </div>

        </div>
        <div class="button-container">
            <button class="buttonup" onclick="window.location.href='../inscription/index.php'">S'inscrire</button>
            <button class="buttonin" onclick="window.location.href='../connexion/index.php'">Se connecter </button>
        </div>
    </main>
    <script src="../assets/js/countdown.js"></script>
</body>

</html>