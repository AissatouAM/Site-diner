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

$motDePasseBD = $user['mot_de_passe'];

// Si le mot de passe est en clair ou haché
if ($motDePasseBD === $mot_de_passe || password_verify($mot_de_passe, $motDePasseBD)) {
    // ✅ Connexion réussie
    $_SESSION['utilisateur_id'] = $user['id'];
    $_SESSION['prenom'] = $user['prenom'];
    $_SESSION['nom'] = $user['nom'];
    header("Location: ../../accueil2/index.php");
    exit();
} else {
    echo "Mot de passe incorrect.";
    exit();
}

?>
