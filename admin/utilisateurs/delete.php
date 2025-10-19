<?php
session_start();
require_once('../../config/db_connect.php');

// Sécurité : vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // Pas besoin de message, la redirection suffit
    header('Location: ../../connexion/index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['user_message'] = "Erreur : Méthode de requête non autorisée.";
    header('Location: index.php');
    exit();
}

// 1. Récupérer et Valider le numéro de téléphone via POST
// Le nom du champ doit correspondre à celui dans le formulaire (ici 'telephone')
$phone_to_delete = $_POST['telephone'] ?? null;

// Nettoyage de la donnée
$phone_to_delete = trim($phone_to_delete);

//vérifier si le téléphone est vide après nettoyage
if (empty($phone_to_delete)) {
    $_SESSION['user_message'] = "Erreur : Le numéro de téléphone est manquant.";
    header('Location: index.php');
    exit();
}

// 2. Empêcher un admin de se supprimer lui-même
if ($phone_to_delete == $_SESSION['telephone']) {
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
    $stmt_candidat = $pdo->prepare("DELETE FROM candidats WHERE telephone = ?");
    $stmt_candidat->execute([$phone_to_delete]);

    if ($stmt_candidat->rowCount() > 0) {
        $candidature_message = "Candidature associée également supprimée.";
    }
    // B. SUPPRIMER L'UTILISATEUR DANS LA TABLE 'utilisateurs'
    $stmt_user = $pdo->prepare("DELETE FROM utilisateurs WHERE telephone = ?");
    $stmt_user->execute([$phone_to_delete]);

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

