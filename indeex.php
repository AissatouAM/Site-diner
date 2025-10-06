<?php
session_start();

// Connexion à la base
$conn = new mysqli("localhost", "root", "", "election_diner");

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifie si le numéro est enregistré en session
if (isset($_SESSION['telephone']) && isset($_POST['id_candidat']) && isset($_POST['type'])) {
    $telephone = $_SESSION['telephone'];
    $id_candidat = intval($_POST['id_candidat']);
    $type = $_POST['type'];
    $date_vote = date('Y-m-d H:i:s');

    // Vérifie si le votant a déjà voté pour ce type (roi ou reine)
    $check_sql = "SELECT * FROM votes WHERE telephone = '$telephone' AND type = '$type'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "⚠️ Vous avez déjà voté pour un $type.";
    } else {
        // Enregistrer le vote
        $insert_sql = "INSERT INTO votes (id_candidat, telephone, type, date_vote)
            VALUES ('$id_candidat', '$telephone', '$type', '$date_vote')";

        if ($conn->query($insert_sql) === TRUE) {
            echo "✅ Vote enregistré avec succès !";
        } else {
            echo "Erreur SQL : " . $conn->error;
        }
    }
} else {
    echo "❌ Données manquantes ou session expirée.";
}

$conn->close();
