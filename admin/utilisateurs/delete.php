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
try {
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
    $stmt->execute([$id_to_delete]);

    // Vérifier si la suppression a bien eu lieu
    if ($stmt->rowCount() > 0) {
        $_SESSION['user_message'] = "L'utilisateur a été supprimé avec succès.";
    } else {
        $_SESSION['user_message'] = "Erreur : L'utilisateur n'a pas pu être trouvé ou a déjà été supprimé.";
    }

} catch (PDOException $e) {
    // Gérer les erreurs de base de données (par exemple, contraintes de clé étrangère)
    $_SESSION['user_message'] = "Erreur de base de données : " . $e->getMessage();
}

// 4. Rediriger vers la liste des utilisateurs
header('Location: index.php');
exit();
?>
