<?php
session_start();

$host = 'localhost';
$db   = 'vote_site';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifie session + POST
if (isset($_SESSION['telephone'], $_POST['nom_candidat'])) {
    $telephone = $_SESSION['telephone'];
    $nom_candidat = $_POST['nom_candidat'];
    $date_vote = date('Y-m-d H:i:s');

    // Récupérer le genre du candidat
    $stmt = $pdo->prepare("SELECT genre_candidat FROM candidat WHERE nom = ?");
    $stmt->execute([$nom_candidat]);
    $candidat = $stmt->fetch();

    if (!$candidat) {
        echo "❌ Candidat introuvable.";
        exit;
    }

    $genre = $candidat['genre_candidat'];

    // Vérifie si l'utilisateur a déjà voté pour ce genre
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM vote WHERE telephone = ? AND genre_candidat = ?");
    $stmt->execute([$telephone, $genre]);

    if ($stmt->fetchColumn() > 0) {
        echo "⚠️ Vous avez déjà voté pour un candidat $genre.";
        exit;
    }

    // Enregistrer le vote
    $stmt = $pdo->prepare("INSERT INTO vote (telephone, nom_candidat, genre_candidat, date_vote)
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$telephone, $nom_candidat, $genre, $date_vote]);

    // Mettre à jour le compteur du candidat
    $stmt = $pdo->prepare("UPDATE candidat SET vote = vote + 1 WHERE nom = ?");
    $stmt->execute([$nom_candidat]);

    echo "✅ Vote enregistré avec succès !";
} else {
    echo "❌ Données manquantes ou session expirée.";
}
?>
