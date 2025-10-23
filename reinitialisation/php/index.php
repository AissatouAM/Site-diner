<?php
// Démarrer la session
session_start();
require_once("../../config/db_connect.php"); // Assure-toi que $pdo est défini ici

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    if (empty($email)) {
        echo "<script>alert('Veuillez entrer votre adresse email.'); window.history.back();</script>";
        exit;
    }

    try {
        // Vérifier si l'email existe dans la base de données
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Ici, tu pourrais générer un token et envoyer un mail
            // Pour l’instant, on simule juste l’envoi
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
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "<script>alert('Erreur de base de données : " . $e->getMessage() . "'); window.history.back();</script>";
        exit;
    }

} else {
    // Redirection si la page est accédée sans POST
    header("Location: ../../connexion/index.php");
    exit;
}
?>
