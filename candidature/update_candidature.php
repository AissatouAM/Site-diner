<?php
session_start();
require_once("../config/db_connect.php");

// 1. Sécurité de base
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['utilisateur_id'])) {
    header("Location: ../connexion/html/index.php");
    exit();
}

if (!isset($_POST['id_candidat'], $_POST['niveau'], $_POST['titre'])) {
    $_SESSION['candidature_error'] = "Requête invalide, des champs sont manquants.";
    header("Location: index.php");
    exit();
}

$id_candidat = $_POST['id_candidat'];
$id_utilisateur = $_SESSION['utilisateur_id'];

// 2. Autorisation : Vérifier que le candidat correspond bien à l'utilisateur connecté
$stmt_user = $pdo->prepare("SELECT prenom, nom, telephone FROM utilisateurs WHERE id_utilisateur = ?");
$stmt_user->execute([$id_utilisateur]);
$utilisateur = $stmt_user->fetch(PDO::FETCH_ASSOC);

$stmt_candidat = $pdo->prepare("SELECT telephone, photo FROM candidats WHERE id_candidat = ?");
$stmt_candidat->execute([$id_candidat]);
$candidat = $stmt_candidat->fetch(PDO::FETCH_ASSOC);

if (!$candidat || $candidat['telephone'] !== $utilisateur['telephone']) {
    $_SESSION['candidature_error'] = "Action non autorisée.";
    header("Location: index.php");
    exit();
}

// 3. Préparer les données à mettre à jour
$niveau = $_POST['niveau'];
$genre = ($_POST['titre'] === 'roi') ? 'masculin' : 'feminin';

$query_parts = [
    "niveau = ?",
    "genre_candidat = ?"
];
$params = [$niveau, $genre];
$new_filename = null;

// 4. Gérer la photo si une nouvelle a été envoyée
if (isset($_FILES['new_photo']) && $_FILES['new_photo']['error'] === UPLOAD_ERR_OK) {
    $photo = $_FILES['new_photo'];

    // Valider taille et type
    if ($photo['size'] > 2 * 1024 * 1024) { /* 2MB */ $_SESSION['candidature_error'] = "Le fichier est trop volumineux (max 2MB)."; header("Location: index.php"); exit(); }
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array(mime_content_type($photo['tmp_name']), $allowed_types)) { $_SESSION['candidature_error'] = "Format de fichier non autorisé (uniquement JPG, PNG)."; header("Location: index.php"); exit(); }

    // Préparer les noms et chemins
    $old_photo_filename = $candidat['photo'];
    $prenom_sane = strtolower(preg_replace('/[^A-Za-z0-9_]/', '_', $utilisateur['prenom']));
    $nom_sane = strtolower(preg_replace('/[^A-Za-z0-9_]/', '_', $utilisateur['nom']));
    $new_extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
    $new_filename = $prenom_sane . '_' . $nom_sane . '.' . $new_extension;
    $new_photo_path = '../assets/images/candidats/' . $new_filename;

    // Déplacer le nouveau fichier
    if (!move_uploaded_file($photo['tmp_name'], $new_photo_path)) {
        $_SESSION['candidature_error'] = "Impossible de sauvegarder la nouvelle image.";
        header("Location: index.php");
        exit();
    }

    // Ajouter la photo à la requête de mise à jour
    $query_parts[] = "photo = ?";
    $params[] = $new_filename;
}

// 5. Construire et exécuter la requête de mise à jour
$sql = "UPDATE candidats SET " . implode(", ", $query_parts) . " WHERE id_candidat = ?";
$params[] = $id_candidat;

$stmt_update = $pdo->prepare($sql);

if ($stmt_update->execute($params)) {
    // 6. Si la mise à jour a réussi ET qu'une nouvelle photo a été uploadée, supprimer l'ancienne
    if ($new_filename !== null) {
        $old_photo_path = '../assets/images/candidats/' . $candidat['photo'];
        if ($candidat['photo'] !== $new_filename && file_exists($old_photo_path)) {
            unlink($old_photo_path);
        }
    }
    $_SESSION['candidature_success'] = "Votre candidature a été mise à jour avec succès !";
} else {
    $_SESSION['candidature_error'] = "Une erreur est survenue lors de la mise à jour.";
}

header("Location: index.php");
exit();
?>