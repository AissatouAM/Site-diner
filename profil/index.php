<?php
session_start();
require_once("../config/db_connect.php");

// 1. Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: ../connexion/html/index.php");
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];

// 2. Récupérer les informations de l'utilisateur
$stmt_user = $pdo->prepare("SELECT prenom, nom, telephone FROM utilisateurs WHERE id_utilisateur = ?");
$stmt_user->execute([$id_utilisateur]);
$utilisateur = $stmt_user->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    session_destroy();
    header("Location: ../connexion/html/index.php");
    exit();
}

// 3. Récupérer les votes de l'utilisateur
$stmt_votes = $pdo->prepare("
    SELECT c.prenom, c.nom, c.photo, c.genre_candidat 
    FROM candidats c
    JOIN votes v ON c.id_candidat = v.id_candidat
    WHERE v.id_utilisateur = ?
");
$stmt_votes->execute([$id_utilisateur]);
$votes = $stmt_votes->fetchAll(PDO::FETCH_ASSOC);

$vote_masculin = null;
$vote_feminin = null;
foreach ($votes as $vote) {
    if ($vote['genre_candidat'] == 'masculin') {
        $vote_masculin = $vote;
    } elseif ($vote['genre_candidat'] == 'feminin') {
        $vote_feminin = $vote;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="../assets/css/profil.css">
</head>
<body>

    <header>
        <div class="menu">
            <h3>Mon Profil</h3>
            <nav id="nav-links">
                <a href="../accueil2/index.php">Accueil</a>
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
        <?php 
        if (isset($_SESSION['update_success'])):
        ?>
            <div class="success-message" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                <?php echo $_SESSION['update_success']; unset($_SESSION['update_success']); ?>
            </div>
        <?php 
        endif;
        if (isset($_SESSION['update_error'])):
        ?>
            <div class="error-message" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                <?php echo $_SESSION['update_error']; unset($_SESSION['update_error']); ?>
            </div>
        <?php endif; ?>

        <div class="profile-card" id="profile-display">
            <h2>Mes informations personnelles</h2>
            <p><strong>Prénom:</strong> <?php echo htmlspecialchars($utilisateur['prenom']); ?></p>
            <p><strong>Nom:</strong> <?php echo htmlspecialchars($utilisateur['nom']); ?></p>
            <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($utilisateur['telephone']); ?></p>
            <button class="btn" id="edit-btn">Modifier mes informations</button>
        </div>

        <div class="edit-profile-card" id="profile-edit">
            <h2>Modifier mes informations</h2>
            <form action="update_profil.php" method="POST">
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($utilisateur['prenom']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($utilisateur['nom']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="text" id="telephone" name="telephone" value="<?php echo htmlspecialchars($utilisateur['telephone']); ?>" required>
                </div>
                <hr style="border-color: #555; margin: 20px 0;">
                <p>Pour enregistrer les modifications, veuillez entrer votre mot de passe actuel.</p>
                <div class="form-group">
                    <label for="current_password">Mot de passe actuel</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <button type="submit" class="btn">Enregistrer les modifications</button>
                <button type="button" class="btn btn-secondary" id="cancel-btn">Annuler</button>
            </form>
        </div>

        <div class="votes-section">
            <h2>Mes votes</h2>
            <div class="vote-cards-container">
                <div class="vote-card">
                    <h3>Mon vote Masculin</h3>
                    <?php if ($vote_masculin): ?>
                        <img src="../assets/images/<?php echo htmlspecialchars($vote_masculin['photo']); ?>" alt="Photo de <?php echo htmlspecialchars($vote_masculin['prenom']); ?>">
                        <p><?php echo htmlspecialchars($vote_masculin['prenom']) . ' ' . htmlspecialchars($vote_masculin['nom']); ?></p>
                    <?php else: ?>
                        <p class="no-vote">Vous n'avez pas encore voté pour un candidat masculin.</p>
                    <?php endif; ?>
                </div>
                <div class="vote-card">
                    <h3>Mon vote Féminin</h3>
                    <?php if ($vote_feminin): ?>
                        <img src="../assets/images/<?php echo htmlspecialchars($vote_feminin['photo']); ?>" alt="Photo de <?php echo htmlspecialchars($vote_feminin['prenom']); ?>">
                        <p><?php echo htmlspecialchars($vote_feminin['prenom']) . ' ' . htmlspecialchars($vote_feminin['nom']); ?></p>
                    <?php else: ?>
                        <p class="no-vote">Vous n'avez pas encore voté pour une candidate féminine.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Script pour le formulaire d'édition
        const displayCard = document.getElementById('profile-display');
        const editCard = document.getElementById('profile-edit');
        const editBtn = document.getElementById('edit-btn');
        const cancelBtn = document.getElementById('cancel-btn');

        editBtn.addEventListener('click', () => {
            displayCard.style.display = 'none';
            editCard.style.display = 'block';
        });

        cancelBtn.addEventListener('click', () => {
            editCard.style.display = 'none';
            displayCard.style.display = 'block';
        });

        // Script pour le menu hamburger
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const navLinks = document.getElementById('nav-links');

        hamburgerBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>

</body>
</html>