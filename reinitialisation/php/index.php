<?php
session_start();
require_once("../../config/db_connect.php"); // Connexion PDO $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $_SESSION['message'] = "Veuillez entrer votre adresse email.";
        $_SESSION['type'] = "error";
    } else {
        // Vérification dans la base de données
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['message'] = "✅ Un lien de réinitialisation a été envoyé à $email (simulation).";
            $_SESSION['type'] = "success";

            // Ici tu pourrais générer un token et envoyer un mail réel
        } else {
            $_SESSION['message'] = "❌ Aucun compte trouvé avec cet email.";
            $_SESSION['type'] = "error";
        }
    }

    // Redirection vers la page de formulaire
    header("Location: ../index.php");
    exit;
} else {
    // Si on accède à ce fichier directement sans POST
    header("Location: ../index.php");
    exit;
}
