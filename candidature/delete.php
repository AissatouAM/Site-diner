<?php
session_start();
require_once("../config/db_connect.php");

// 1. Sécurité de base
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['utilisateur_id'])) {
    // Rediriger si la méthode n'est pas POST ou si l'utilisateur n'est pas connecté
    $_SESSION['candidature_error'] = "Accès non autorisé.";
    header("Location: ../connexion/html/index.php");
    exit();
}

// Vérifier que l'ID du candidat est présent
if (!isset($_POST['id_candidat'])) {
    $_SESSION['candidature_error'] = "Requête invalide, ID du candidat manquant.";
    header("Location: index.php");
    exit();
}

$id_candidat = $_POST['id_candidat'];
$id_utilisateur = $_SESSION['utilisateur_id'];

// 2. Récupérer les informations de l'utilisateur (pour le téléphone)
$stmt_user = $pdo->prepare("SELECT telephone FROM utilisateurs WHERE id_utilisateur = ?");
$stmt_user->execute([$id_utilisateur]);
$utilisateur = $stmt_user->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    $_SESSION['candidature_error'] = "Erreur de session utilisateur.";
    header("Location: index.php");
    exit();
}

$telephone_utilisateur = $utilisateur['telephone'];

// 3. Autorisation et Récupération du chemin de la photo
// On vérifie que le candidat existe ET que son téléphone correspond à celui de l'utilisateur.
$stmt_candidat = $pdo->prepare("SELECT photo FROM candidats WHERE id_candidat = ? AND telephone = ?");
$stmt_candidat->execute([$id_candidat, $telephone_utilisateur]);
$candidat = $stmt_candidat->fetch(PDO::FETCH_ASSOC);

if (!$candidat) {
    $_SESSION['candidature_error'] = "Action non autorisée ou candidature introuvable.";
    header("Location: index.php");
    exit();
}

$photo_filename = $candidat['photo'];

// 4. Suppression de l'enregistrement de la base de données (Priorité pour la sécurité)
$stmt_delete = $pdo->prepare("DELETE FROM candidats WHERE id_candidat = ?");

if ($stmt_delete->execute([$id_candidat])) {
    
    // 5. Suppression du fichier photo sur le serveur
    if (!empty($photo_filename)) {
        $photo_path = '../assets/images/candidats/' . $photo_filename;
        
        // Vérifier l'existence du fichier avant de tenter de le supprimer
        if (file_exists($photo_path) && !is_dir($photo_path)) {
            if (unlink($photo_path)) {
                // Fichier supprimé avec succès
            } else {
                // La suppression a échoué. Ceci est une erreur mineure,
                // car l'entrée DB a déjà été supprimée.
            }
        }
    }

    $_SESSION['candidature_success'] = "Votre candidature a été supprimée avec succès. Vous pouvez en déposer une nouvelle.";
    
} else {
    $_SESSION['candidature_error'] = "Une erreur est survenue lors de la suppression de la candidature.";
}

header("Location: index.php");
exit();
?>