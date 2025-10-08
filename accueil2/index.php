<?php
session_start();
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
    <link rel="stylesheet" href="../assets/css/accueil2.css">
    <title>Page d'accueil interne</title>
</head>

<body>
    <header>
        <div class="menu">
            <h3>Coder's dinner 2026-Election Roi & Reine</h3>
            <nav>
                <a href="../accueil1/index.php" onclick="return confirm('voulez-vous vraiment vous deconnecter?')">Déconnexion</a>
                <a href="../profil/index.php">Profil</a>
            </nav>
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
</body>

</html>