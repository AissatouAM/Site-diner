const title = document.getElementById("perr");
const colors = ["gold", "yellow"];
let index = 0;

setInterval(() => {
  title.style.color = colors[index];
  title.style.transform = "rotate(" + (Math.random() * 4 - 2) + "deg)";
  index = (index + 1) % colors.length;
}, 700);

const cards = document.querySelectorAll(".card");
cards.forEach((card, i) => {
  card.style.opacity = "0";
  card.style.transform = "translateY(50px)";
  setTimeout(() => {
    card.style.transition = "all 0.8s ease";
    card.style.opacity = "1";
    card.style.transform = "translateY(0)";
  }, i * 250);
});



function createConfetti() {
  const confetti = document.createElement("div");
  confetti.classList.add("confetti");
  confetti.style.left = Math.random() * 100 + "vw";
  confetti.style.animationDuration = 3 + Math.random() * 2 + "s";
  document.body.appendChild(confetti);
  setTimeout(() => confetti.remove(), 5000);
}
setInterval(createConfetti, 400);


const winnerText = document.querySelector("h2[style*='gold']");
setInterval(() => {
  winnerText.style.textShadow = `0 0 10px gold, 0 0 20px orange, 0 0 30px yellow`;
  setTimeout(() => {
    winnerText.style.textShadow = "none";
  }, 300);
}, 1000);

const style = document.createElement("style");
style.textContent = `
.confetti {
  position: fixed;
  top: 0;
  width: 8px;
  height: 8px;
  background: gold;
  border-radius: 50%;
  opacity: 0.8;
  animation: fall linear forwards;
  z-index: 9999;
}
@keyframes fall {
  to { transform: translateY(100vh) rotate(720deg); opacity: 0; }
}`;
document.head.appendChild(style);