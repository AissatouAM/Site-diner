<?php
// Démarrer la session
session_start();
require_once("../../config/db_connect.php"); 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    if (empty($email)) {
        echo "<script>alert('Veuillez entrer votre adresse email.'); window.history.back();</script>";
        exit;
    }

    // Vérifier si l'email existe dans la base de données
    $sql = "SELECT * FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Ici, on pourrait générer un token et envoyer un mail
        // Pour le moment, on simule juste l’envoi du mail
        echo "
        <script>
            alert('✅ Un lien de réinitialisation a été envoyé à $email (simulation).');
            window.location.href = '../../connexion/index.php';
        </script>
        ";
    } else {
        echo "
        <script>
            alert('❌ Aucun compte trouvé avec cet email.');
            window.history.back();
        </script>
        ";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../../connexion/index.php");
    exit;
}
?>
