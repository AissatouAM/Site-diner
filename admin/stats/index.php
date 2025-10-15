<?php
session_start();
require_once('../../config/db_connect.php');

// Sécurité
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../connexion/html/index.php');
    exit();
}

// Récupérer les candidats approuvés, triés par votes
$stmt = $pdo->prepare("SELECT * FROM candidats WHERE status = 'approved' ORDER BY vote DESC");
$stmt->execute();
$candidats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Séparer hommes et femmes
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
    <title>Statistiques des Votes</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .candidate-photo { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
        h2 { color: #daa520; font-size: 2rem; text-align: center; margin-bottom: 20px; }
        @media (max-width: 992px) { .stats-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<div class="admin-container">
    <header class="admin-header">
        <h1>Statistiques des Votes</h1>
        <div>
            <a href="../index.php" class="back-link">Retour au Tableau de Bord</a>
            <a href="../../deconnexion/index.php" class="logout-link">Déconnexion</a>
        </div>
    </header>

    <main class="stats-grid">
        <!-- Classement Messieurs -->
        <div class="ranking-container">
            <h2>Classement Messieurs</h2>
            <div class="content-table">
                <table>
                    <thead><tr><th>Rang</th><th>Photo</th><th>Prénom</th><th>Nom</th><th>Votes</th></tr></thead>
                    <tbody>
                        <?php if (empty($hommes)): ?>
                            <tr><td colspan="5">Aucun candidat masculin.</td></tr>
                        <?php else: ?>
                            <?php foreach ($hommes as $index => $h): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><img src="../../assets/images/candidats/<?= htmlspecialchars($h['photo']) ?>" class="candidate-photo"></td>
                                    <td><?= htmlspecialchars($h['prenom']) ?></td>
                                    <td><?= htmlspecialchars($h['nom']) ?></td>
                                    <td><?= htmlspecialchars($h['vote']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Classement Dames -->
        <div class="ranking-container">
            <h2>Classement Dames</h2>
            <div class="content-table">
                <table>
                    <thead><tr><th>Rang</th><th>Photo</th><th>Prénom</th><th>Nom</th><th>Votes</th></tr></thead>
                    <tbody>
                        <?php if (empty($dames)): ?>
                            <tr><td colspan="5">Aucune candidate féminine.</td></tr>
                        <?php else: ?>
                            <?php foreach ($dames as $index => $d): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><img src="../../assets/images/candidats/<?= htmlspecialchars($d['photo']) ?>" class="candidate-photo"></td>
                                    <td><?= htmlspecialchars($d['prenom']) ?></td>
                                    <td><?= htmlspecialchars($d['nom']) ?></td>
                                    <td><?= htmlspecialchars($d['vote']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>
