<?php
session_start();
require_once("../../config/db_connect.php");

$message = "";
$type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    if (empty($email)) {
        $message = "Veuillez entrer votre adresse email.";
        $type = "error";
    } else {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $message = "Un lien de réinitialisation a été envoyé à $email (simulation).";
            $type = "success";
        } else {
            $message = "Aucun compte trouvé avec cet email.";
            $type = "error";
        }
    }
}
?>