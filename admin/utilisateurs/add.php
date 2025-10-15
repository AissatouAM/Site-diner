<?php
session_start();
require_once('../../config/db_connect.php');

// Sécurité : vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../connexion/html/index.php');
    exit();
}

$errors = [];
$success = false;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    // Validation
    if (empty($prenom)) {
        $errors[] = "Le prénom est requis.";
    }
    if (empty($nom)) {
        $errors[] = "Le nom est requis.";
    }
    if (empty($telephone)) {
        $errors[] = "Le téléphone est requis.";
    }
    if (empty($mot_de_passe)) {
        $errors[] = "Le mot de passe est requis.";
    }
    if ($mot_de_passe !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    if (strlen($mot_de_passe) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    // Vérifier si le téléphone existe déjà
    if (empty($errors)) {
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE telephone = ?");
        $stmt_check->execute([$telephone]);
        if ($stmt_check->fetchColumn() > 0) {
            $errors[] = "Ce numéro de téléphone est déjà utilisé.";
        }
    }

    // Si pas d'erreur, ajouter l'utilisateur
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (prenom, nom, telephone, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$prenom, $nom, $telephone, $hashed_password, $role]);
            
            $_SESSION['user_message'] = "Utilisateur ajouté avec succès !";
            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="admin-container">
        <!-- En-tête -->
        <header class="admin-header">
            <h1>Ajouter un Utilisateur</h1>
            <div>
                <a href="index.php" class="back-link">Retour à la liste</a>
                <a href="../../deconnexion/index.php" class="logout-link">Déconnexion</a>
            </div>
        </header>

        <!-- Messages d'erreur -->
        <?php if (!empty($errors)): ?>
            <div class="message-box error">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'ajout -->
        <main class="form-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone *</label>
                    <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe * (min. 6 caractères)</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <div class="form-group">
                    <label for="role">Rôle</label>
                    <select id="role" name="role">
                        <option value="user" <?= ($_POST['role'] ?? 'user') === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                        <option value="admin" <?= ($_POST['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Ajouter l'utilisateur</button>
                    <a href="index.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </main>
    </div>

</body>
</html>
