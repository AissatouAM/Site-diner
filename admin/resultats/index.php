<?php
session_start();
require_once('../../config/db_connect.php');

// Sécurité : vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../connexion/index.php');
    exit();
}

try {
    // Récupérer le Roi (candidat masculin avec le plus de votes)
    $stmt_roi = $pdo->prepare("SELECT * FROM candidats WHERE genre_candidat = 'masculin' AND status = 'approved' ORDER BY vote DESC LIMIT 1");
    $stmt_roi->execute();
    $roi = $stmt_roi->fetch(PDO::FETCH_ASSOC);

    // Récupérer la Reine (candidate féminine avec le plus de votes)
    $stmt_reine = $pdo->prepare("SELECT * FROM candidats WHERE genre_candidat = 'feminin' AND status = 'approved' ORDER BY vote DESC LIMIT 1");
    $stmt_reine->execute();
    $reine = $stmt_reine->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des résultats: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats Finaux</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="admin-container">
    <header class="admin-header">
        <h1>Résultats Finaux</h1>
        <div>
            <a href="../index.php" class="back-link">Retour au Tableau de Bord</a>
            <a href="../../deconnexion/index.php" class="logout-link">Déconnexion</a>
        </div>
    </header>

    <h2 style="text-align: center; font-size: 2.5rem; color: #ffd700; text-shadow: 0 0 20px rgba(255, 215, 0, 0.8); margin: 30px 0;" id="winners-title">
        🪄 THE WINNERS 🪄
    </h2>

    <main class="results-grid">
        <!-- Le Roi -->
        <div class="winner-card" data-winner="king">
            <?php if ($roi): ?>
                <div class="crown">👑</div>
                <h2>THE KING</h2>
                <img src="../../assets/images/candidats/<?= htmlspecialchars($roi['photo']) ?>" alt="Photo du Roi">
                <div class="winner-name"><?= htmlspecialchars($roi['prenom'] . ' ' . $roi['nom']) ?></div>
                <div class="vote-count">Votes : <?= htmlspecialchars($roi['vote']) ?></div>
            <?php else: ?>
                <div class="no-winner">Aucun Roi trouvé.</div>
            <?php endif; ?>
        </div>

        <!-- La Reine -->
        <div class="winner-card" data-winner="queen">
            <?php if ($reine): ?>
                <div class="crown">👑</div>
                <h2>THE QUEEN</h2>
                <img src="../../assets/images/candidats/<?= htmlspecialchars($reine['photo']) ?>" alt="Photo de la Reine">
                <div class="winner-name"><?= htmlspecialchars($reine['prenom'] . ' ' . $reine['nom']) ?></div>
                <div class="vote-count">Votes : <?= htmlspecialchars($reine['vote']) ?></div>
            <?php else: ?>
                <div class="no-winner">Aucune Reine trouvée.</div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
// Animation du titre "THE WINNERS"
const title = document.getElementById("winners-title");
const colors = ["#ffd700", "#ff6347", "#ff00ff", "#ffff00"];
let colorIndex = 0;

setInterval(() => {
    title.style.color = colors[colorIndex];
    title.style.transform = "rotate(" + (Math.random() * 4 - 2) + "deg)";
    colorIndex = (colorIndex + 1) % colors.length;
}, 700);

// Animation d'apparition des cartes
const cards = document.querySelectorAll(".winner-card");
cards.forEach((card, i) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(50px)";
    setTimeout(() => {
        card.style.transition = "all 0.8s ease";
        card.style.opacity = "1";
        card.style.transform = "translateY(0)";
    }, i * 250);
});

// Effet hover sur les cartes
cards.forEach(card => {
    card.addEventListener("mouseenter", () => {
        card.style.transform = "scale(1.05)";
        card.style.boxShadow = "0 0 30px #ffd700";
        card.style.transition = "all 0.3s ease";
    });
    card.addEventListener("mouseleave", () => {
        card.style.transform = "scale(1)";
        card.style.boxShadow = "0 10px 40px rgba(218, 165, 32, 0.3)";
    });
});

// Confettis
function createConfetti() {
    const confetti = document.createElement("div");
    confetti.classList.add("confetti");
    confetti.style.left = Math.random() * 100 + "vw";
    confetti.style.animationDuration = 3 + Math.random() * 2 + "s";
    document.body.appendChild(confetti);
    setTimeout(() => confetti.remove(), 5000);
}

setInterval(createConfetti, 400);

// Animation du texte "THE WINNERS"
setInterval(() => {
    title.style.textShadow = `0 0 10px #ffd700, 0 0 20px orange, 0 0 30px yellow`;
    setTimeout(() => {
        title.style.textShadow = "0 0 20px rgba(255, 215, 0, 0.8)";
    }, 300);
}, 1000);

// Styles pour les confettis
const style = document.createElement("style");
style.textContent = `
.confetti {
    position: fixed;
    top: 0;
    width: 8px;
    height: 8px;
    background: #ffd700;
    border-radius: 50%;
    opacity: 0.8;
    animation: fall linear forwards;
    z-index: 9999;
}
@keyframes fall {
    to {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
    }
}`;
document.head.appendChild(style);
</script>

</body>
</html>
