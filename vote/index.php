<?php
session_start();
require_once('../config/db_connect.php');

// Sécurité : l'utilisateur doit être connecté pour voter
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../connexion/index.php');
    exit();
}

$user_id = $_SESSION['utilisateur_id'];

// 1. Récupérer les votes de l'utilisateur
$stmt_user_votes = $pdo->prepare("SELECT genre_candidat, id_candidat FROM votes WHERE id_utilisateur = ?");
$stmt_user_votes->execute([$user_id]);
$user_votes = $stmt_user_votes->fetchAll(PDO::FETCH_KEY_PAIR);

$voted_for_male_id = $user_votes['masculin'] ?? null;
$voted_for_female_id = $user_votes['feminin'] ?? null;

// 2. Récupérer tous les candidats approuvés
$stmt_candidats = $pdo->query("SELECT * FROM candidats WHERE status = 'approved'");
$candidats = $stmt_candidats->fetchAll(PDO::FETCH_ASSOC);

// 3. Séparer les candidats par genre
$hommes = [];
$dames = [];
foreach ($candidats as $candidat) {
    if ($candidat['genre_candidat'] === 'masculin') {
        $hommes[] = $candidat;
    } else {
        $dames[] = $candidat;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Vote</title>
    <link rel="stylesheet" href="../assets/css/vote.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">

    <style>
        .no-candidate-message {
            text-align: center;
            font-size: 1.3em;
            color: gold;
            margin: 40px 0;
            font-family: 'Dancing Script', cursive;
        }
    </style>
</head>
<body>

    <header>
        <div class="menu">
            <h3>Page de Vote</h3>
            <nav id="nav-links">
                <a href="../tableaudebord/index.php">Accueil</a>
                <a href="../profil/index.php">Profil</a>
                <a href="index.php">Voter</a>
                <a href="../deconnexion/index.php">Déconnexion</a>
            </nav>
            <button class="hamburger" id="hamburger-btn">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </button>
        </div>
    </header>

    <main>
        <div class="vote-container">
            <div class="election-header">
                <h1>Élections Roi et Reine</h1>
                <p>Who will be on the throne this year?</p>
            </div>

            <!-- Section pour les hommes -->
            <section class="category-section">
                <h2 class="category-title">Choisissez votre Roi</h2>

                <?php if (empty($hommes)): ?>
                    <p class="no-candidate-message">Aucun candidat pour l’instant.</p>
                <?php else: ?>

                <?php if ($voted_for_male_id): ?>
                    <div class="voted-message">Vous avez déjà voté pour un Roi.</div>
                <?php endif; ?>

                <div class="carousel-container">
                    <button class="nav-button left" data-target="male-candidates-grid">&#10094;</button>
                    <div class="candidates-grid" id="male-candidates-grid">
                        <?php 
                            $cloned_end_cards_male = array_slice($hommes, -3);
                            foreach ($cloned_end_cards_male as $h_cloned):
                                $isDisabled = $voted_for_male_id !== null;
                                $isVotedFor = $h_cloned['id_candidat'] == $voted_for_male_id;
                                $cardClasses = 'candidate-card cloned-card';
                                if ($isDisabled) $cardClasses .= ' disabled';
                                if ($isVotedFor) $cardClasses .= ' voted-for';
                        ?>
                                <div class="<?= $cardClasses ?>">
                                    <img src="../assets/images/candidats/<?= htmlspecialchars($h_cloned['photo']) ?>" alt="Photo de <?= htmlspecialchars($h_cloned['prenom']) ?>">
                                    <div class="candidate-info">
                                        <h3 class="candidate-name"><?= htmlspecialchars($h_cloned['prenom'] . ' ' . $h_cloned['nom']) ?></h3>
                                        <p class="candidate-level"><?= htmlspecialchars($h_cloned['niveau']) ?></p>
                                        <form action="process_vote.php" method="POST">
                                            <input type="hidden" name="id_candidat" value="<?= $h_cloned['id_candidat'] ?>">
                                            <button type="submit" class="vote-button <?= $isDisabled ? 'disabled' : '' ?>" <?= $isDisabled ? 'disabled' : '' ?>>Voter</button>
                                        </form>
                                    </div>
                                </div>
                        <?php endforeach; ?>

                        <?php foreach ($hommes as $h): ?>
                            <?php 
                                $isDisabled = $voted_for_male_id !== null;
                                $isVotedFor = $h['id_candidat'] == $voted_for_male_id;
                                $cardClasses = 'candidate-card';
                                if ($isDisabled) $cardClasses .= ' disabled';
                                if ($isVotedFor) $cardClasses .= ' voted-for';
                            ?>
                            <div class="<?= $cardClasses ?>">
                                <img src="../assets/images/candidats/<?= htmlspecialchars($h['photo']) ?>" alt="Photo de <?= htmlspecialchars($h['prenom']) ?>">
                                <div class="candidate-info">
                                    <h3 class="candidate-name"><?= htmlspecialchars($h['prenom'] . ' ' . $h['nom']) ?></h3>
                                    <p class="candidate-level"><?= htmlspecialchars($h['niveau']) ?></p>
                                    <form action="process_vote.php" method="POST">
                                        <input type="hidden" name="id_candidat" value="<?= $h['id_candidat'] ?>">
                                        <button type="submit" class="vote-button <?= $isDisabled ? 'disabled' : '' ?>" <?= $isDisabled ? 'disabled' : '' ?>>Voter</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php 
                            $cloned_start_cards_male = array_slice($hommes, 0, 3);
                            foreach ($cloned_start_cards_male as $h_cloned): 
                                $isDisabled = $voted_for_male_id !== null;
                                $isVotedFor = $h_cloned['id_candidat'] == $voted_for_male_id;
                                $cardClasses = 'candidate-card cloned-card';
                                if ($isDisabled) $cardClasses .= ' disabled';
                                if ($isVotedFor) $cardClasses .= ' voted-for';
                        ?>
                                <div class="<?= $cardClasses ?>">
                                    <img src="../assets/images/candidats/<?= htmlspecialchars($h_cloned['photo']) ?>" alt="Photo de <?= htmlspecialchars($h_cloned['prenom']) ?>">
                                    <div class="candidate-info">
                                        <h3 class="candidate-name"><?= htmlspecialchars($h_cloned['prenom'] . ' ' . $h_cloned['nom']) ?></h3>
                                        <p class="candidate-level"><?= htmlspecialchars($h_cloned['niveau']) ?></p>
                                        <form action="process_vote.php" method="POST">
                                            <input type="hidden" name="id_candidat" value="<?= $h_cloned['id_candidat'] ?>">
                                            <button type="submit" class="vote-button <?= $isDisabled ? 'disabled' : '' ?>" <?= $isDisabled ? 'disabled' : '' ?>>Voter</button>
                                        </form>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="nav-button right" data-target="male-candidates-grid">&#10095;</button>
                </div>

                <?php endif; ?>
            </section>

            <!-- Section pour les femmes -->
            <section class="category-section">
                <h2 class="category-title">Choisissez votre Reine</h2>

                <?php if (empty($dames)): ?>
                    <p class="no-candidate-message">Aucun candidat pour l’instant.</p>
                <?php else: ?>

                <?php if ($voted_for_female_id): ?>
                    <div class="voted-message">Vous avez déjà voté pour une Reine.</div>
                <?php endif; ?>

                <div class="carousel-container">
                    <button class="nav-button left" data-target="female-candidates-grid">&#10094;</button>
                    <div class="candidates-grid" id="female-candidates-grid">
                        <?php 
                            $cloned_end_cards_female = array_slice($dames, -3);
                            foreach ($cloned_end_cards_female as $d_cloned):
                                $isDisabled = $voted_for_female_id !== null;
                                $isVotedFor = $d_cloned['id_candidat'] == $voted_for_female_id;
                                $cardClasses = 'candidate-card cloned-card';
                                if ($isDisabled) $cardClasses .= ' disabled';
                                if ($isVotedFor) $cardClasses .= ' voted-for';
                        ?>
                                <div class="<?= $cardClasses ?>">
                                    <img src="../assets/images/candidats/<?= htmlspecialchars($d_cloned['photo']) ?>" alt="Photo de <?= htmlspecialchars($d_cloned['prenom']) ?>">
                                    <div class="candidate-info">
                                        <h3 class="candidate-name"><?= htmlspecialchars($d_cloned['prenom'] . ' ' . $d_cloned['nom']) ?></h3>
                                        <p class="candidate-level"><?= htmlspecialchars($d_cloned['niveau']) ?></p>
                                        <form action="process_vote.php" method="POST">
                                            <input type="hidden" name="id_candidat" value="<?= $d_cloned['id_candidat'] ?>">
                                            <button type="submit" class="vote-button <?= $isDisabled ? 'disabled' : '' ?>" <?= $isDisabled ? 'disabled' : '' ?>>Voter</button>
                                        </form>
                                    </div>
                                </div>
                        <?php endforeach; ?>

                        <?php foreach ($dames as $d): ?>
                            <?php 
                                $isDisabled = $voted_for_female_id !== null;
                                $isVotedFor = $d['id_candidat'] == $voted_for_female_id;
                                $cardClasses = 'candidate-card';
                                if ($isDisabled) $cardClasses .= ' disabled';
                                if ($isVotedFor) $cardClasses .= ' voted-for';
                            ?>
                            <div class="<?= $cardClasses ?>">
                                <img src="../assets/images/candidats/<?= htmlspecialchars($d['photo']) ?>" alt="Photo de <?= htmlspecialchars($d['prenom']) ?>">
                                <div class="candidate-info">
                                    <h3 class="candidate-name"><?= htmlspecialchars($d['prenom'] . ' ' . $d['nom']) ?></h3>
                                    <p class="candidate-level"><?= htmlspecialchars($d['niveau']) ?></p>
                                    <form action="process_vote.php" method="POST">
                                        <input type="hidden" name="id_candidat" value="<?= $d['id_candidat'] ?>">
                                        <button type="submit" class="vote-button <?= $isDisabled ? 'disabled' : '' ?>" <?= $isDisabled ? 'disabled' : '' ?>>Voter</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php 
                            $cloned_start_cards_female = array_slice($dames, 0, 3);
                            foreach ($cloned_start_cards_female as $d_cloned): 
                                $isDisabled = $voted_for_female_id !== null;
                                $isVotedFor = $d_cloned['id_candidat'] == $voted_for_female_id;
                                $cardClasses = 'candidate-card cloned-card';
                                if ($isDisabled) $cardClasses .= ' disabled';
                                if ($isVotedFor) $cardClasses .= ' voted-for';
                        ?>
                                <div class="<?= $cardClasses ?>">
                                    <img src="../assets/images/candidats/<?= htmlspecialchars($d_cloned['photo']) ?>" alt="Photo de <?= htmlspecialchars($d_cloned['prenom']) ?>">
                                    <div class="candidate-info">
                                        <h3 class="candidate-name"><?= htmlspecialchars($d_cloned['prenom'] . ' ' . $d_cloned['nom']) ?></h3>
                                        <p class="candidate-level"><?= htmlspecialchars($d_cloned['niveau']) ?></p>
                                        <form action="process_vote.php" method="POST">
                                            <input type="hidden" name="id_candidat" value="<?= $d_cloned['id_candidat'] ?>">
                                            <button type="submit" class="vote-button <?= $isDisabled ? 'disabled' : '' ?>" <?= $isDisabled ? 'disabled' : '' ?>>Voter</button>
                                        </form>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="nav-button right" data-target="female-candidates-grid">&#10095;</button>
                </div>

                <?php endif; ?>
            </section>
        </div>
    </main>

    <script>
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const navLinks = document.getElementById('nav-links');

        hamburgerBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>
    <script src="../assets/js/vote.js"></script>

</body>
</html>