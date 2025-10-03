<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/accueil1.css">
    <title>acceuil</title>
</head>

<body>
    <div class="background"></div>
    <main>
        <div class="logo">
            👑 ⚜ Coder's Dinner ⚜ 👑
        </div>
        <div data-target="2025-10-02T17:13:05" id="countdown" aria-live="polite">
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
                l'heure est arrivee sortez vos sangse !
            </div>

        </div>
        <button class="buttonup" onclick="window.location.href='../inscription/index.php'">S'inscrire</button>
        <button class="buttonin" onclick="window.location.href='../connexion/index.php'">Se connecter </button>
        <script>
            /*(function() {
                                        const container = document.getElementById('countdown');
                                        // Récupère la date cible depuis data-target, sinon utilise la date définie ci-dessous
                                        const dataTarget = container.dataset.target;
                                        const fallback = '2025-10-02T17:13:05'; // changer ici si tu veux
                                        const targetDate = new Date(dataTarget || fallback);

                                        // éléments DOM
                                        const daysEl = document.getElementById('days');
                                        const hoursEl = document.getElementById('hours');
                                        const minutesEl = document.getElementById('minutes');
                                        const secondsEl = document.getElementById('seconds');
                                        const messageEl = document.getElementById('message');

                                        function pad(n) {
                                            return String(n).padStart(2, '0');
                                        }

                                        function update() {
                                            const now = new Date();
                                            const diff = targetDate - now; // en ms

                                            if (isNaN(targetDate.getTime())) {
                                                // date invalide
                                                daysEl.textContent = '--';
                                                hoursEl.textContent = '--';
                                                minutesEl.textContent = '--';
                                                secondsEl.textContent = '--';
                                                messageEl.style.display = 'block';
                                                messageEl.textContent = "Date cible invalide.";
                                                clearInterval(timer);
                                                return;
                                            }

                                            if (diff <= 0) {
                                                // terminé
                                                daysEl.textContent = '0';
                                                hoursEl.textContent = '00';
                                                minutesEl.textContent = '00';
                                                secondsEl.textContent = '00';
                                                messageEl.style.display = 'block';
                                                messageEl.textContent = "c'est le jour du dinner sortez vos plus baux sangse !";
                                                clearInterval(timer);
                                                return;
                                            }

                                            const sec = Math.floor(diff / 1000);
                                            const days = Math.floor(sec / (3600 * 24));
                                            const hours = Math.floor((sec % (3600 * 24)) / 3600);
                                            const minutes = Math.floor((sec % 3600) / 60);
                                            const seconds = sec % 60;

                                            daysEl.textContent = days;
                                            hoursEl.textContent = pad(hours);
                                            minutesEl.textContent = pad(minutes);
                                            secondsEl.textContent = pad(seconds);
                                        }

                                        // Mise à jour immédiate puis chaque seconde
                                        update();
                        const timer = setInterval(update, 1000);
                    })();*/
        </script>
    </main>
</body>

</html>