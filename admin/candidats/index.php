<?php
session_start();
require_once('../../config/db_connect.php');

// Sécurité
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../connexion/index.php');
    exit();
}

// Gérer les messages de session
$message = $_SESSION['candidate_message'] ?? null;
unset($_SESSION['candidate_message']);

// Récupérer tous les candidats
$stmt = $pdo->query("SELECT * FROM candidats ORDER BY nom, prenom");
$candidats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Candidats</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .candidate-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        .status-approved { color: #28a745; font-weight: bold; }
        .status-rejected { color: #e74c3c; font-weight: bold; }
        .status-pending { color: #f39c12; font-weight: bold; }
    </style>
</head>
<body>

<div class="admin-container">
    <header class="admin-header">
        <h1>Gestion des Candidats</h1>
        <div>
            <a href="../index.php" class="back-link">Retour au Tableau de Bord</a>
            <a href="../../deconnexion/index.php" class="logout-link">Déconnexion</a>
        </div>
    </header>

    <?php if ($message): ?>
        <div class="message-box success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <main class="content-table">
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Niveau</th>
                    <th>Votes</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($candidats)): ?>
                    <tr>
                        <td colspan="7">Aucun candidat trouvé.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($candidats as $candidat): ?>
                        <tr>
                            <td>
                                <img src="../../assets/images/candidats/<?= htmlspecialchars($candidat['photo']) ?>" alt="Photo de <?= htmlspecialchars($candidat['prenom']) ?>" class="candidate-photo">
                            </td>
                            <td><?= htmlspecialchars($candidat['prenom']) ?></td>
                            <td><?= htmlspecialchars($candidat['nom']) ?></td>
                            <td><?= htmlspecialchars($candidat['niveau']) ?></td>
                            <td><?= htmlspecialchars($candidat['vote']) ?></td>
                            <td>
                                <span class="status-<?= strtolower(htmlspecialchars($candidat['status'])) ?>">
                                    <?= htmlspecialchars($candidat['status']) ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="delete.php?id=<?= $candidat['id_candidat'] ?>" class="btn btn-delete">Supprimer</a>
                                <a href="update_status.php?id=<?= $candidat['id_candidat'] ?>" class="btn">Changer Statut</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>

</body>
</html>
