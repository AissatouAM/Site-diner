<?php
session_start();
require_once("../../config/db_connect.php"); // Connexion PDO $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // 1️⃣ Email vide
    if (empty($email)) {
        $_SESSION['message'] = "Veuillez entrer votre adresse email.";
        $_SESSION['type'] = "error";
        header("Location: ../index.php");
        exit;
    }

    // 2️⃣ Email invalide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Adresse email invalide.";
        $_SESSION['type'] = "error";
        header("Location: ../index.php");
        exit;
    }

    // 3️⃣ Vérification dans la base de données
    $sql = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['message'] = "Un lien de réinitialisation a été envoyé à $email (simulation).";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['message'] = "Aucun compte trouvé avec cet email.";
        $_SESSION['type'] = "error";
    }

    // Redirection vers la page de formulaire
    header("Location: ../index.php");
    exit;
} else {
    // Accès direct sans POST
    header("Location: ../index.php");
    exit;
}
