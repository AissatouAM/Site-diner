<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "site_diner";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

try {
    // Roi
    $stmtRoi = $pdo->prepare("SELECT * FROM candidat WHERE genre_candidat='masculin' ORDER BY vote DESC LIMIT 1");
    $stmtRoi->execute();
    $roi = $stmtRoi->fetch(PDO::FETCH_ASSOC);

    // Reine
    $stmtReine = $pdo->prepare("SELECT * FROM candidat WHERE genre_candidat='feminin' ORDER BY vote DESC LIMIT 1");
    $stmtReine->execute();
    $reine = $stmtReine->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des résultats: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats</title>
    <link rel="stylesheet" href="resultats.css">
</head>
<body>

<div class="top-right">
    <a href="../connexion/index.php"><button>Connexion</button></a>
    <a href="../tableaudebord/index.php"><button>Tableau de bord</button></a>
</div>
<br><br>
<h1 id="perr"> 👑CODERS'DINNER👑 </h1>
<h2 style="color:gold;"><i>🪄THE WINNERS🪄</i></h2>

<div class="container">
    <div class="card">
        <h2>THE KING👑</h2>
        <img src="../<?= $roi['photo']; ?>" alt="Photo du Roi">
        <h2><?= $roi['prenom']." ". $roi['nom']; ?></h2>
        <p>Votes : <?= $roi['vote']; ?></p>
    </div>

    <div class="card">
        <h2>THE QUEEN👑</h2>
        <img src="../<?= $reine['photo']; ?>" alt="Photo de la Reine">
        <h2><?= $reine['prenom']." ". $reine['nom']; ?></h2>
        <p>Votes : <?= $reine['vote']; ?></p>
    </div>
</div>

<script src="resultat.js"></script>
</body>
</html>

