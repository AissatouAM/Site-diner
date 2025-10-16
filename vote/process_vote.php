<?php
session_start();
require_once('../config/db_connect.php');

// Sécurité : l'utilisateur doit être connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../connexion/index.php');
    exit();
}

// 1. Valider les données POST
$id_candidat = $_POST['id_candidat'] ?? null;
$user_id = $_SESSION['utilisateur_id'];

if (!$id_candidat || !filter_var($id_candidat, FILTER_VALIDATE_INT)) {
    // Gérer l'erreur, peut-être rediriger avec un message
    header('Location: index.php');
    exit();
}

// 2. Récupérer les informations du candidat
try {
    $stmt = $pdo->prepare("SELECT genre_candidat FROM candidats WHERE id_candidat = ? AND status = 'approved'");
    $stmt->execute([$id_candidat]);
    $candidat = $stmt->fetch();

    if (!$candidat) {
        // Candidat non trouvé ou non approuvé
        header('Location: index.php');
        exit();
    }
    $genre_candidat = $candidat['genre_candidat'];

    // 3. Vérifier si l'utilisateur a déjà voté pour ce genre
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE id_utilisateur = ? AND genre_candidat = ?");
    $stmt_check->execute([$user_id, $genre_candidat]);
    
    if ($stmt_check->fetchColumn() > 0) {
        // L'utilisateur a déjà voté, rediriger (l'interface utilisateur devrait déjà l'empêcher)
        header('Location: index.php');
        exit();
    }

    // 4. Procéder au vote (Transaction)
    $pdo->beginTransaction();

    // Inserer le vote
    $stmt_insert = $pdo->prepare("INSERT INTO votes (id_utilisateur, id_candidat, genre_candidat) VALUES (?, ?, ?)");
    $stmt_insert->execute([$user_id, $id_candidat, $genre_candidat]);

    // Mettre à jour le compteur
    $stmt_update = $pdo->prepare("UPDATE candidats SET vote = vote + 1 WHERE id_candidat = ?");
    $stmt_update->execute([$id_candidat]);

    $pdo->commit();

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Gérer l'erreur, peut-être logger et rediriger
    // Pour le débogage : die("Erreur de vote : " . $e->getMessage());
    header('Location: index.php'); // Redirection simple en cas d'erreur
    exit();
}

// 5. Rediriger vers la page de vote
header('Location: index.php');
exit();
?>
