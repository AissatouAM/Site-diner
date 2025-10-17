<?php
session_start();
require_once('../../config/db_connect.php');

// Sécurité : vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // Pas besoin de message, la redirection suffit
    header('Location: ../../connexion/index.php');
    exit();
}

// 1. Valider l'ID de l'utilisateur
$id_to_delete = $_GET['id'] ?? null;

if (!$id_to_delete || !filter_var($id_to_delete, FILTER_VALIDATE_INT)) {
    $_SESSION['user_message'] = "Erreur : ID d'utilisateur invalide.";
    header('Location: index.php');
    exit();
}

// 2. Empêcher un admin de se supprimer lui-même
if ($id_to_delete == $_SESSION['utilisateur_id']) {
    $_SESSION['user_message'] = "Erreur : Vous ne pouvez pas supprimer votre propre compte administrateur.";
    header('Location: index.php');
    exit();
}

// 3. Procéder à la suppression

$candidature_message = "";

try {
    // DÉBUT DE LA TRANSACTION (pour assurer que les deux suppressions se fassent ou qu'aucune ne se fasse)
    $pdo->beginTransaction();

    // A. SUPPRIMER LA CANDIDATURE CORRESPONDANTE DANS LA TABLE 'candidats'
    $stmt_candidat = $pdo->prepare("DELETE FROM candidats WHERE id_utilisateur = ?");
    $stmt_candidat->execute([$id_to_delete]);

    if ($stmt_candidat->rowCount() > 0) {
        $candidature_message = "Candidature associée également supprimée.";
    }
    // B. SUPPRIMER L'UTILISATEUR DANS LA TABLE 'utilisateurs'
    $stmt_user = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
    $stmt_user->execute([$id_to_delete]);

    // C. Vérifier le résultat et finaliser
    if ($stmt_user->rowCount() > 0) {
        $pdo->commit(); // Validation des deux suppressions
        $_SESSION['user_message'] = "L'utilisateur a été supprimé avec succès. " . $candidature_message;
    } else {
        $pdo->rollBack(); // Annulation de tout si l'utilisateur n'existait pas
        $_SESSION['user_message'] = "Erreur : L'utilisateur n'a pas pu être trouvé ou a déjà été supprimé.";
    }

} catch (PDOException $e) {
    $pdo->rollBack(); 
    $_SESSION['user_message'] = "Erreur de base de données : Suppression annulée. " . $e->getMessage();
}

// 4. Rediriger vers la liste des utilisateurs
header('Location: index.php');
exit();
?>

