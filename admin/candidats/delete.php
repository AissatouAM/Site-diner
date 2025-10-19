<?php
    session_start();
    require_once('../../config/db_connect.php');

    // 1. Sécurité : Vérification du rôle 'admin'
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        // Redirige si l'utilisateur n'est pas un admin
        header('Location: ../../connexion/index.php');
        exit();
    }

    // 2. Récupération et validation de l'ID
    $candidat_id = $_GET['id'] ?? null;
    $message = "";

    // Vérifie si l'ID est présent et est un entier valide
    if (!$candidat_id || !filter_var($candidat_id, FILTER_VALIDATE_INT)) {
        $_SESSION['candidate_message'] = "ID de candidat non valide pour la suppression.";
        header('Location: index.php'); // Redirige vers la liste
        exit();
    }

    // 3. Logique de Suppression
    try {
        // Prépare et exécute la requête de suppression
        $stmt = $pdo->prepare("DELETE FROM candidats WHERE id_candidat = ?");
        $stmt->execute([$candidat_id]);

        // Vérifie si une ligne a été affectée (si le candidat existait)
        if ($stmt->rowCount() > 0) {
            $_SESSION['candidate_message'] = "Le candidat (ID: " . htmlspecialchars($candidat_id) . ") a été **supprimé** avec succès. ✅";
        } else {
            $_SESSION['candidate_message'] = "Erreur : Candidat (ID: " . htmlspecialchars($candidat_id) . ") non trouvé ou déjà supprimé. ⚠️";
        }

    } catch (PDOException $e) {
        // Gestion des erreurs de base de données
        $_SESSION['candidate_message'] = "Erreur de base de données lors de la suppression: " . $e->getMessage();
    }

    // 4. Redirection finale vers la liste des candidats
    header('Location: index.php');
    exit();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Candidat</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Supprimer le Candidat</h1>
            <div>
                <a href="index.php" class="back-link">Retour à la liste</a>
            </div>
        </header>

        <main class="form-container">
            <?php if (!empty($error_messages)): ?>
                <div class="message-box error">
                    <ul>
                        <?php foreach ($error_messages as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="delete.php" method="POST">
                <input type="hidden" name="id_candidat" value="<?= htmlspecialchars($candidat_data['id_candidat']) ?>">

                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($candidat_data['prenom']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($candidat_data['nom']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="niveau">Niveau</label>
                    <input type="text" id="niveau" name="niveau" value="<?= htmlspecialchars($candidat_data['niveau']) ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">Mettre à jour</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
