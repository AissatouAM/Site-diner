<?php
session_start();
require_once("../config/db_connect.php");

// 1. Sécurité : Vérifier la méthode, la connexion et les données POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['utilisateur_id'])) {
    header("Location: ../connexion/index.php");
    exit();
}

if (!isset($_POST['niveau'], $_POST['titre'], $_FILES['photo'])) {
    $_SESSION['candidature_error'] = "Des informations sont manquantes.";
    header("Location: index.php");
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];

// 2. Récupérer les informations de l'utilisateur pour les utiliser
$stmt_user = $pdo->prepare("SELECT nom, prenom, telephone FROM utilisateurs WHERE id_utilisateur = ?");
$stmt_user->execute([$id_utilisateur]);
$utilisateur = $stmt_user->fetch(PDO::FETCH_ASSOC);

// 3. Vérifier (côté serveur) que l'utilisateur n'est pas déjà candidat
$stmt_check = $pdo->prepare("SELECT id_candidat FROM candidats WHERE telephone = ?");
$stmt_check->execute([$utilisateur['telephone']]);
if ($stmt_check->fetch()) {
    $_SESSION['candidature_error'] = "Vous avez déjà une candidature en cours.";
    header("Location: index.php");
    exit();
}

// 4. Gérer le téléversement de la photo
$photo = $_FILES['photo'];
if ($photo['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['candidature_error'] = "Erreur lors du téléversement de la photo.";
    header("Location: index.php");
    exit();
}

// Vérifier la taille (ex: max 2MB)
if ($photo['size'] > 2 * 1024 * 1024) {
    $_SESSION['candidature_error'] = "Le fichier est trop volumineux (max 2MB).";
    header("Location: index.php");
    exit();
}

// Vérifier le type de fichier
$allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
$file_type = mime_content_type($photo['tmp_name']);
if (!in_array($file_type, $allowed_types)) {
    $_SESSION['candidature_error'] = "Format de fichier non autorisé (uniquement JPG, PNG).";
    header("Location: index.php");
    exit();
}

// Créer un nom de fichier basé sur le prénom et le nom
$prenom_sane = strtolower(preg_replace('/[^A-Za-z0-9_]/', '_', $utilisateur['prenom']));
$nom_sane = strtolower(preg_replace('/[^A-Za-z0-9_]/', '_', $utilisateur['nom']));
$extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
$base_filename = $prenom_sane . '_' . $nom_sane;
$unique_filename = $base_filename . '.' . $extension;
$upload_path = '../assets/images/candidats/' . $unique_filename;

// Gérer les doublons
$counter = 1;
while (file_exists($upload_path)) {
    $unique_filename = $base_filename . '_' . $counter . '.' . $extension;
    $upload_path = '../assets/images/candidats/' . $unique_filename;
    $counter++;
}

// Déplacer le fichier
if (!move_uploaded_file($photo['tmp_name'], $upload_path)) {
    $_SESSION['candidature_error'] = "Impossible de sauvegarder l'image.";
    header("Location: index.php");
    exit();
}

// 5. Préparer les données pour l'insertion
$niveau = $_POST['niveau'];
$titre = $_POST['titre'];

// Traduction du titre en genre
$genre = ($titre === 'roi') ? 'masculin' : 'feminin';

// 6. Insérer dans la base de données
$sql = "INSERT INTO candidats (nom, prenom, telephone, photo, niveau, genre_candidat) VALUES (?, ?, ?, ?, ?, ?)";
$stmt_insert = $pdo->prepare($sql);

try {
    $stmt_insert->execute([
        $utilisateur['nom'],
        $utilisateur['prenom'],
        $utilisateur['telephone'],
        $unique_filename, // On ne stocke que le nom du fichier
        $niveau,
        $genre
    ]);

    // Redirection avec succès
    $_SESSION['update_success'] = "Votre candidature a été enregistrée avec succès !";
    header("Location: ../profil/index.php");
    exit();

} catch (PDOException $e) {
    // En cas d'erreur BDD, on peut supprimer la photo uploadée pour nettoyer
    if (file_exists($upload_path)) {
        unlink($upload_path);
    }
    $_SESSION['candidature_error'] = "Une erreur de base de données est survenue."; // Pour le débogage : . $e->getMessage();
    header("Location: index.php");
    exit();
}
?>
