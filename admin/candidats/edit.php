<?php
session_start();
require_once('../../config/db_connect.php');

// Sécurité
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../connexion/index.php');
    exit();
}

$candidat_id = null;
$candidat_data = null;
$error_messages = [];

// --- Logique POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidat_id = $_POST['id_candidat'] ?? null;
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $niveau = trim($_POST['niveau'] ?? '');

    if (empty($prenom) || empty($nom) || empty($niveau)) {
        $error_messages[] = "Tous les champs sont obligatoires.";
    }

    if (empty($error_messages)) {
        try {
            $stmt = $pdo->prepare("UPDATE candidats SET prenom = ?, nom = ?, niveau = ? WHERE id_candidat = ?");
            $stmt->execute([$prenom, $nom, $niveau, $candidat_id]);
            $_SESSION['candidate_message'] = "Le candidat a été mis à jour.";
            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            $error_messages[] = "Erreur de base de données: " . $e->getMessage();
        }
    }
    $candidat_data = $_POST;

} else {
    // --- Logique GET ---
    $candidat_id = $_GET['id'] ?? null;
    if (!$candidat_id || !filter_var($candidat_id, FILTER_VALIDATE_INT)) {
        $_SESSION['candidate_message'] = "ID de candidat non valide.";
        header('Location: index.php');
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM candidats WHERE id_candidat = ?");
    $stmt->execute([$candidat_id]);
    $candidat_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$candidat_data) {
        $_SESSION['candidate_message'] = "Candidat non trouvé.";
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
    <title>Modifier le Candidat</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Modifier le Candidat</h1>
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
