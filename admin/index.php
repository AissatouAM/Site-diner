<?php
session_start();

// Sécurité : vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion/html/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="admin-container">
        <!-- En-tête -->
        <header class="admin-header">
            <h1>Tableau de Bord</h1>
            <a href="../deconnexion/index.php" class="logout-link">Déconnexion</a>
        </header>

        <!-- Grille de navigation -->
        <main class="nav-grid">
            <a href="utilisateurs/index.php" class="nav-item">
                <h2>Gérer les Utilisateurs</h2>
                <p>Modifier, supprimer ou voir les utilisateurs inscrits.</p>
            </a>

            <a href="candidats/index.php" class="nav-item">
                <h2>Gérer les Candidats</h2>
                <p>Consulter, et rejeter les candidatures.</p>
            </a>

            <a href="stats/index.php" class="nav-item">
                <h2>Statistiques des votes</h2>
                <p>Visualiser la répartition des votes en temps réel.</p>
            </a>

            <a href="resultats/index.php" class="nav-item">
                <h2>Résultats Finaux</h2>
                <p>Afficher le Roi et la Reine du dîner.</p>
            </a>

        </main>
    </div>

</body>
</html>
