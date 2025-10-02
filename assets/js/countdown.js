const eventDate = new Date("2025-12-15T20:00:00");
function updateCountdown() {
    const now = new Date();
    const diff = eventDate - now;
    if (diff < 0) {
        document.getElementById('timer').textContent = "L'événement est passé !";
        return;
    }
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
    const minutes = Math.floor((diff / (1000 * 60)) % 60);
    const seconds = Math.floor((diff / 1000) % 60);
    document.getElementById('timer').textContent =
        `${days}j ${hours}h ${minutes}m ${seconds}s`;
}
setInterval(updateCountdown, 1000);
updateCountdown();
