<?php
session_start();
require_once('../../config/db_connect.php');

// Sécurité
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../connexion/index.php');
    exit();
}

// 1. Valider l'ID
$candidat_id = $_GET['id'] ?? null;
if (!$candidat_id || !filter_var($candidat_id, FILTER_VALIDATE_INT)) {
    $_SESSION['candidate_message'] = "Erreur : ID de candidat non valide.";
    header('Location: index.php');
    exit();
}

// 2. Récupérer le statut actuel
try {
    $stmt = $pdo->prepare("SELECT status FROM candidats WHERE id_candidat = ?");
    $stmt->execute([$candidat_id]);
    $current_status = $stmt->fetchColumn();

    if ($current_status === false) {
        $_SESSION['candidate_message'] = "Erreur : Candidat non trouvé.";
        header('Location: index.php');
        exit();
    }

    // 3. Déterminer le nouveau statut
    $new_status = ($current_status === 'approved') ? 'rejected' : 'approved';

    // 4. Mettre à jour le statut
    $update_stmt = $pdo->prepare("UPDATE candidats SET status = ? WHERE id_candidat = ?");
    $update_stmt->execute([$new_status, $candidat_id]);

    $_SESSION['candidate_message'] = "Le statut du candidat a été mis à jour avec succès.";

} catch (PDOException $e) {
    $_SESSION['candidate_message'] = "Erreur de base de données : " . $e->getMessage();
}

// 5. Rediriger
header('Location: index.php');
exit();
?>
