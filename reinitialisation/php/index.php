<?php
session_start();
require_once("../../config/db_connect.php"); // Connexion PDO $pdo

// Inclure PHPMailer (chemin selon où tu as dézippé PHPMailer)
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';
require '../../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    //  Email vide
    if (empty($email)) {
        $_SESSION['message'] = "Veuillez entrer votre adresse email.";
        $_SESSION['type'] = "error";
        header("Location: ../index.php");
        exit;
    }

    // Email invalide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Adresse email invalide.";
        $_SESSION['type'] = "error";
        header("Location: ../index.php");
        exit;
    }

    //  Vérification dans la base de données
    $sql = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Génération du token sécurisé
        $token = bin2hex(random_bytes(32));
        $expire = date('Y-m-d H:i:s', strtotime('+1 hour')); // token valable 1h

        // Stocker le token dans la base
        $sqlUpdate = "UPDATE utilisateurs SET reset_token = :token, token_expire = :expire WHERE email = :email";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            'token' => $token,
            'expire' => $expire,
            'email' => $email
        ]);

        // Créer le lien de réinitialisation
        $link = "http://localhost/SITE-DINER-1/reinitialisation/index.php?email=" . urlencode($email) . "&token=" . $token;

        // 5️⃣ Envoi du mail avec PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Paramètres SMTP (exemple Gmail)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'aissataba395@gmail.com';      // ton email Gmail
            $mail->Password   = 'fefo djfy geci waib';        // mot de passe App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('no-reply@tonsite.com', 'Ton Site');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body    = "Bonjour,<br><br>Cliquez sur ce lien pour réinitialiser votre mot de passe : <a href='$link'>$link</a><br><br>Ce lien est valable 1 heure.";

            $mail->send();
            $_SESSION['message'] = "✅ Un lien de réinitialisation a été envoyé à $email.";
            $_SESSION['type'] = "success";

        } catch (Exception $e) {
            $_SESSION['message'] = "❌ Le mail n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
            $_SESSION['type'] = "error";
        }

    } else {
        $_SESSION['message'] = "❌ Aucun compte trouvé avec cet email.";
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
