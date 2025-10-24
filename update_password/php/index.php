<?php
session_start();
require_once("../../config/db_connect.php"); // ton fichier PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $email = $_POST['email'] ?? '';


    // Vérifie que tous les champs sont remplis
    if (empty($password) || empty($confirm)) {
        $_SESSION['erreur_mdp'] = "Veuillez remplir tous les champs.";
        header("Location: ../index.php?token=" . urlencode($token));
        exit;
    }

    // Vérifie que les deux mots de passe correspondent
    if ($password !== $confirm) {
        $_SESSION['erreur_mdp'] = "Les mots de passe ne correspondent pas.";
        header("Location: ../index.php?token=" . urlencode($token));
        exit;
    }

    // Vérifie que le token existe et n’a pas expiré
    $sql = "SELECT * FROM utilisateurs WHERE reset_token = :token AND email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'token' => $token,
        'email' => $email
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || strtotime($user['token_expire']) < time())  {
        $_SESSION['erreur_mdp'] = "Lien de réinitialisation invalide ou expiré.";
        header("Location: ../index.php?token=" . urlencode($token));
        exit;
    }

    // Hash du nouveau mot de passe
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Mise à jour du mot de passe et suppression du token
    $update = "UPDATE utilisateurs SET mot_de_passe = :mdp, reset_token = NULL, token_expire = NULL WHERE id_utilisateur = :id";
    $stmt = $pdo->prepare($update);
    $stmt->execute([
        'mdp' => $hash,
        'id' => $user['id_utilisateur']
    ]);

    $_SESSION['message'] = "Votre mot de passe a été réinitialisé avec succès.";
    $_SESSION['type'] = "success";
    header("Location: ../index.php?success=1 ");
    exit;

} else {
    header("Location: ../index.php");
    exit;
}
?>
