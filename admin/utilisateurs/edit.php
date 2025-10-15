<?php
session_start();
require_once('../../config/db_connect.php');

// Sécurité : vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../connexion/html/index.php');
    exit();
}

$user_id = null;
$user_data = null;
$error_messages = [];

// --- Logique de soumission du formulaire (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['id_utilisateur'] ?? null;
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $role = $_POST['role'] ?? '';

    // Validation
    if (empty($prenom) || empty($nom) || empty($telephone) || empty($role)) {
        $error_messages[] = "Tous les champs sont obligatoires.";
    }
    if ($role !== 'user' && $role !== 'admin') {
        $error_messages[] = "Rôle non valide.";
    }

    // Validation spéciale : empêcher le dernier admin de changer son rôle
    if ($user_id == $_SESSION['utilisateur_id'] && $role === 'user') {
        $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'admin'");
        $admin_count = $stmt->fetchColumn();
        if ($admin_count <= 1) {
            $error_messages[] = "Vous ne pouvez pas retirer le statut d'administrateur au dernier admin du site.";
        }
    }

    if (empty($error_messages)) {
        try {
            $stmt = $pdo->prepare(
                "UPDATE utilisateurs SET prenom = ?, nom = ?, telephone = ?, role = ? WHERE id_utilisateur = ?"
            );
            $stmt->execute([$prenom, $nom, $telephone, $role, $user_id]);
            $_SESSION['user_message'] = "L'utilisateur a été mis à jour avec succès.";
            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['user_message'] = "Erreur lors de la mise à jour : " . $e->getMessage();
            header('Location: index.php');
            exit();
        }
    }
    // Si erreurs, on recharge les données pour réafficher le formulaire
    $user_data = $_POST;

} else {
    // --- Logique d'affichage du formulaire (GET) ---
    $user_id = $_GET['id'] ?? null;
    if (!$user_id || !filter_var($user_id, FILTER_VALIDATE_INT)) {
        $_SESSION['user_message'] = "ID d'utilisateur non valide.";
        header('Location: index.php');
        exit();
    }

    $stmt = $pdo->prepare("SELECT id_utilisateur, prenom, nom, telephone, role FROM utilisateurs WHERE id_utilisateur = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
        $_SESSION['user_message'] = "Utilisateur non trouvé.";
        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Utilisateur</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Modifier l'Utilisateur</h1>
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

            <form action="edit.php" method="POST">
                <input type="hidden" name="id_utilisateur" value="<?= htmlspecialchars($user_data['id_utilisateur']) ?>">

                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user_data['prenom']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user_data['nom']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($user_data['telephone']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="role">Rôle</label>
                    <select id="role" name="role">
                        <option value="user" <?= ($user_data['role'] === 'user') ? 'selected' : '' ?>>Utilisateur</option>
                        <option value="admin" <?= ($user_data['role'] === 'admin') ? 'selected' : '' ?>>Administrateur</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">Mettre à jour</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
