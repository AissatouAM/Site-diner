<?php 
// connection a la base de donnee
require_once("../../config/db_connect.php");

// Récupération des données du formulaire
$prenom = $_POST['prenom'];
$nom = $_POST['nom'];
$numero = $_POST['numero'];
$mot_de_passe = $_POST['mdp'];
$confirmer = $_POST['confirmation'];

// Validation des données
if ($mot_de_passe !== $confirmer) {
    echo "Les mots de passe ne correspondent pas.";
    exit();
}

// Vérification si le numéro de téléphone est déjà utilisé
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE numero = ?");
$stmt->execute([$numero]);
if ($stmt->fetch()) {
    echo "Ce numéro est déjà utilisé.";
    exit();
}

// Validation du numéro de téléphone
if (!preg_match("/^(77|76|75|78|71|70)[0-9]{7}$/", $numero)) {
    echo "Numéro invalide. Doit commencer par 77, 76, 75, 78 , 71 ou 70 et avoir 9 chiffres.";
    exit();
}

// Insertion des données dans la base de données
$sql = " INSERT INTO utilisateurs (prenom, nom, numero, mot_de_passe) VALUES (:prenom, :nom, :numero, :mdp)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':prenom' => $prenom,
    ':nom' => $nom,
    ':numero' => $numero,
    ':mdp' => $mot_de_passe
]);


// Récupération de l'ID de l'utilisateur créé
$user_id = $pdo->lastInsertId();


// Stockage des informations importantes dans la session
$_SESSION['user_id'] = $user_id;
$_SESSION['prenom'] = $prenom;
$_SESSION['nom'] = $nom;


// Redirection vers la page d'accueil après inscription
header("Location: ../../accueil2/index.php");
exit();
?>





