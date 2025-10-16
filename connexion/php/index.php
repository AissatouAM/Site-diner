<?php
session_start();
require_once("../../config/db_connect.php");

// Récupération des données du formulaire
$telephone = trim($_POST['telephone']);
$mot_de_passe = $_POST['mdp'];

// Stocke temporairement le numéro pour pré-remplir en cas d'erreur
$_SESSION['last_telephone'] = $telephone;

// Vérifier que les champs sont remplis
if (empty($telephone) || empty($mot_de_passe)) {
    $_SESSION['erreur_connexion'] = "Veuillez remplir tous les champs.";
    header("Location: ../index.php");
    exit();
}

// Vérification du format du numéro
if (!preg_match("/^(77|76|75|78|71|70)[0-9]{7}$/", $telephone)) {
    $_SESSION['erreur_connexion'] = "Numéro invalide.";
    header("Location: ../index.php");
    exit();
}

// Recherche de l'utilisateur dans la base
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE telephone = ?");
$stmt->execute([$telephone]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérification de l'existence du compte
if (!$user) {
    $_SESSION['erreur_connexion'] = "Aucun compte trouvé avec ce numéro.";
    header("Location: ../index.php");
    exit();
}

// Vérification du mot de passe
if (!password_verify($mot_de_passe, $user['mot_de_passe'])) {
    $_SESSION['erreur_connexion'] = "Mot de passe incorrect.";
    header("Location: ../index.php");
    exit();
}

// ✅ Connexion réussie
unset($_SESSION['last_telephone']); // On efface le numéro retenu
$_SESSION['utilisateur_id'] = $user['id_utilisateur'];
$_SESSION['prenom'] = $user['prenom'];
$_SESSION['nom'] = $user['nom'];
$_SESSION['user_role'] = $user['role']; // Ajout du rôle à la session

// Redirection en fonction du rôle
if ($user['role'] === 'admin') {
    header("Location: ../../admin/index.php");
} else {
    header("Location: ../../tableaudebord/index.php");
}
exit();

