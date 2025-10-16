<?php
session_start();
require_once("../config/db_connect.php");

// Vérifier que la requête est bien en POST et que l'utilisateur est connecté
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['utilisateur_id'])) {
    header("Location: ../connexion/index.php");
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];

// Récupérer les données du formulaire
$prenom = trim($_POST['prenom']);
$nom = trim($_POST['nom']);
$telephone = trim($_POST['telephone']);
$current_password = $_POST['current_password'];

// Valider les champs
if (empty($prenom) || empty($nom) || empty($telephone) || empty($current_password)) {
    $_SESSION['update_error'] = "Tous les champs sont obligatoires.";
    header("Location: index.php");
    exit();
}

// Valider le format du numéro de téléphone
if (!preg_match("/^(77|76|75|78|71|70)[0-9]{7}$/", $telephone)) {
    $_SESSION['update_error'] = "Le format du numéro de téléphone est invalide.";
    header("Location: index.php");
    exit();
}

// 1. Vérifier le mot de passe actuel
$stmt = $pdo->prepare("SELECT mot_de_passe, telephone FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($current_password, $user['mot_de_passe'])) {
    $_SESSION['update_error'] = "Le mot de passe actuel est incorrect.";
    header("Location: index.php");
    exit();
}

$old_telephone = $user['telephone'];

// 2. Vérifier si le nouveau numéro de téléphone est déjà utilisé par quelqu'un d'autre
if ($telephone !== $old_telephone) {
    $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE telephone = ?");
    $stmt->execute([$telephone]);
    if ($stmt->fetch()) {
        $_SESSION['update_error'] = "Ce numéro de téléphone est déjà utilisé par un autre compte.";
        header("Location: index.php");
        exit();
    }
}

// 3. Mettre à jour les informations
$update_stmt = $pdo->prepare("
    UPDATE utilisateurs 
    SET prenom = ?, nom = ?, telephone = ? 
    WHERE id_utilisateur = ?
");

if ($update_stmt->execute([$prenom, $nom, $telephone, $id_utilisateur])) {
    // Mettre à jour les informations de la session

    //mettre directement le nouveau nom et prenom dans la candidature
    $sync_candidat_stmt = $pdo->prepare("
        UPDATE candidats 
        SET prenom = ?, nom = ?, telephone = ?
        WHERE telephone = ?
    ");

    $sync_candidat_stmt->execute([$prenom, $nom, $telephone, $old_telephone]);

    $_SESSION['prenom'] = $prenom;
    $_SESSION['nom'] = $nom;
    
    $_SESSION['update_success'] = "Vos informations ont été mises à jour avec succès.";
} else {
    $_SESSION['update_error'] = "Une erreur est survenue lors de la mise à jour.";
}

header("Location: index.php");
exit();
?>
