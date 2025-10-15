<?php
session_start();
session_regenerate_id(true);
if (!isset($_SESSION['prenom']) || !isset($_SESSION['nom'])) {
    header("Location: ../connexion/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/tableaudebord.css">
    <title>Page d'accueil interne</title>
</head>

<body>
    <header>
        <div class="menu">
            <h3>Coder's dinner 2026</h3>
            <nav id="nav-links">
                <a href="../profil/index.php">Profil</a>
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
        <div class="main-text">
            <h1>Bienvenue <?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']; ?> !</h1>
            <p>Que souhaitez-vous faire ?</p>
        </div>
        <a href="../candidature/index.php" class="btn">Déposer ou gérer ma candidature</a>
        <a href="../vote/index.php" class="btn">Voter pour un candidat</a>

    </main>

    <script>
        // Script pour le menu hamburger
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const navLinks = document.getElementById('nav-links');

        hamburgerBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>
</body>

</html>