<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/accueil1.css">
    <script src="../assets/js/countdow.js"></script>
    <title>accueil</title>
</head>

<body>
    <div class="background"></div>
    <main>
        <div class="logo">
            👑 ⚜ Coder's Dinner ⚜ 👑
        </div>
        <div data-target="2025-12-31T23:59:00" id="countdown" aria-live="polite">
            <h2>Compte a rebours jusqu'au dinner</h2>
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
        <button class="buttonup" onclick="window.location.href='../inscription/html/index.php'">S'inscrire</button>
        <button class="buttonin" onclick="window.location.href='../connexion/html/index.php'">Se connecter </button>
    </main>
</body>

</html>