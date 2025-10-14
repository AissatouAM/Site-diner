<?php
session_start();
require_once("../../config/db_connect.php");

// Récupération des données du formulaire
$numero = trim($_POST['numero']);
$mot_de_passe = $_POST['mdp'];

// Stocke temporairement le numéro pour pré-remplir en cas d'erreur
$_SESSION['last_numero'] = $numero;

// Vérifier que les champs sont remplis
if (empty($numero) || empty($mot_de_passe)) {
    $_SESSION['erreur_connexion'] = "Veuillez remplir tous les champs.";
    header("Location: ../html/index.php");
    exit();
}

// Vérification du format du numéro
if (!preg_match("/^(77|76|75|78|71|70)[0-9]{7}$/", $numero)) {
    $_SESSION['erreur_connexion'] = "Numéro invalide.";
    header("Location: ../html/index.php");
    exit();
}

// Recherche de l’utilisateur dans la base
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE numero = ?");
$stmt->execute([$numero]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérification de l'existence du compte
if (!$user) {
    $_SESSION['erreur_connexion'] = "Aucun compte trouvé avec ce numéro.";
    header("Location: ../html/index.php");
    exit();
}

// Vérification du mot de passe
if (!password_verify($mot_de_passe, $user['mot_de_passe'])) {
    $_SESSION['erreur_connexion'] = "Mot de passe incorrect.";
    header("Location: ../html/index.php");
    exit();
}

// ✅ Connexion réussie
unset($_SESSION['last_numero']); // On efface le numéro retenu
$_SESSION['utilisateur_id'] = $user['id'];
$_SESSION['prenom'] = $user['prenom'];
$_SESSION['nom'] = $user['nom'];

header("Location: ../../accueil2/index.php");
exit();

