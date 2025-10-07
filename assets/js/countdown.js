(function() {
    const container = document.getElementById('countdown');
    const dataTarget = container.dataset.target;
    const fallback = '2025-12-31T23:59:00'; // changer ici si tu veux
    const targetDate = new Date(dataTarget || fallback);

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
        const diff = targetDate - now;

        if (isNaN(targetDate.getTime())) {
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

    update();
    const timer = setInterval(update, 1000);
})();