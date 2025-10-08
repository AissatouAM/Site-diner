<?php

session_start();

// Connexion à la base de données
require_once("../../config/db_connect.php");

// Récupération des données du formulaire
$numero = $_POST['numero'];
$mot_de_passe = $_POST['mdp'];

// Vérification que les champs ne sont pas vides
if (empty($numero) || empty($mot_de_passe)) {
    echo "Veuillez remplir tous les champs.";
    exit();
}

// Vérification que le numéro a un bon format
if (!preg_match("/^(77|76|75|78|71|70)[0-9]{7}$/", $numero)) {
    echo "Numéro invalide.";
    exit();
}


// Recherche de l’utilisateur dans la base
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE numero = ?");
$stmt->execute([$numero]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérification du résultat
if (!$user) {
    echo "Aucun compte trouvé avec ce numéro.";
    exit();
}

// Vérification du mot de passe
if ($user['mot_de_passe'] !== $mot_de_passe) {
    echo "Mot de passe incorrect.";
    exit();
}

// Si tout est bon : création de la session
$_SESSION['user_id'] = $user['id'];
$_SESSION['prenom'] = $user['prenom'];
$_SESSION['nom'] = $user['nom'];

// 🔟 Redirection vers la page d’accueil
header("Location: ../../accueil2/index.php");
exit();
?>
