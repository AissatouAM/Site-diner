<?php
session_start();
require_once('../../config/db_connect.php');

// Sécurité : vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../connexion/index.php');
    exit();
}

// Gérer les messages de session
$message = $_SESSION['user_message'] ?? null;
unset($_SESSION['user_message']);

// Logique pour récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT id_utilisateur, prenom, nom, telephone, role FROM utilisateurs ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .btn-delete {
            font-family: 'Dancing Script', cursive, Arial, sans-serif;
        }
    </style>
</head>
<body>

    <div class="admin-container">
        <!-- En-tête -->
        <header class="admin-header">
            <h1>Gestion des Utilisateurs</h1>
            <div>
                <a href="../index.php" class="back-link">Retour au Tableau de Bord</a>
                <a href="../../deconnexion/index.php" class="logout-link">Déconnexion</a>
            </div>
        </header>

        <?php if ($message): ?>
            <div class="message-box success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- Bouton pour ajouter un utilisateur -->
        <div style="margin: 20px 0; text-align: right;">
            <a href="add.php" class="btn btn-add">➕ Ajouter un utilisateur</a>
        </div>

        <!-- Tableau des utilisateurs -->
        <main class="content-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6">Aucun utilisateur trouvé.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id_utilisateur']) ?></td>
                                <td><?= htmlspecialchars($user['prenom']) ?></td>
                                <td><?= htmlspecialchars($user['nom']) ?></td>
                                <td><?= htmlspecialchars($user['telephone']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td class="actions">
                                    <a href="edit.php?id=<?= htmlspecialchars($user['id_utilisateur']) ?>" class="btn btn-edit">Modifier</a>
                                    <form action="delete.php" method="POST" 
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer l\'utilisateur <?= htmlspecialchars($user['telephone']) ?> ? Cette action est irréversible.');" 
                                        style="display:inline;">
                                        
                                        <input type="hidden" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>">
                                        
                                        <button type="submit" class="btn btn-delete">Supprimer</button>
                                    </form>
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
