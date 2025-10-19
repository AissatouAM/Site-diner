<?php
session_start();
require_once("../config/db_connect.php");

// 1. Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: ../connexion/index.php");
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];

// 2. Récupérer les informations de l'utilisateur connecté
$stmt_user = $pdo->prepare("SELECT prenom, nom, telephone FROM utilisateurs WHERE id_utilisateur = ?");
$stmt_user->execute([$id_utilisateur]);
$utilisateur = $stmt_user->fetch(PDO::FETCH_ASSOC);

// 3. Vérifier si l'utilisateur est déjà candidat
$stmt_candidat = $pdo->prepare("SELECT * FROM candidats WHERE telephone = ?");
$stmt_candidat->execute([$utilisateur['telephone']]);
$candidat_existant = $stmt_candidat->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Dancing+Script:wght@400..700&family=Playwrite+CA:wght@100..400&display=swap" rel="stylesheet">
  <!-- Lier les deux feuilles de style -->
  <link rel="stylesheet" href="../assets/css/tableaudebord.css">
  <link rel="stylesheet" href="../assets/css/candidature.css">
  <title>Page Candidature</title>
</head>
<body class="page-candidature">

<header>
    <div class="menu">
        <h3>Candidature</h3>
        <nav id="nav-links">
            <a href="../tableaudebord/index.php">Accueil</a>
            <a href="../profil/index.php">Profil</a>
            <a href="../deconnexion/index.php">Déconnexion</a>
        </nav>
        <button class="hamburger" id="hamburger-btn">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </button>
    </div>
</header>

  <section id="container">

    <div class="form">
        <?php if ($candidat_existant): ?>
            <!-- Vue d'affichage (par défaut) -->
            <div class="candidat-info" id="candidat-display">
                <h1 id="h1">Vous êtes déjà candidat !</h1>
                <img src="../assets/images/candidats/<?php echo htmlspecialchars($candidat_existant['photo']); ?>" alt="Votre photo de profil">
                <p><strong>Titre:</strong> <?php echo ($candidat_existant['genre_candidat'] == 'masculin') ? 'Roi' : 'Reine'; ?></p>
                <p><strong>Nom complet:</strong> <?php echo htmlspecialchars($candidat_existant['prenom']) . ' ' . htmlspecialchars($candidat_existant['nom']); ?></p>
                <p><strong>Niveau d'étude:</strong> <?php echo htmlspecialchars($candidat_existant['niveau']); ?></p>
                
                <button type="button" class="btn-modify" id="btn-edit">Modifier ma candidature</button>
                <button type="button" class="btn-delete" id="btn-del">Supprimer ma candidature</button>
            </div>

            <!-- Formulaire de modification (caché par défaut) -->
            <div class="candidat-edit" id="candidat-edit" style="display: none;">
                <h1 id="h1">Modifier ma candidature</h1>
                
                <form action="update_candidature.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_candidat" value="<?php echo $candidat_existant['id_candidat']; ?>">

                    <fieldset id="middle">
                        <legend>Modifier mon niveau d'étude</legend>
                        <input type="radio" name="niveau" value="DUT 1" <?php if($candidat_existant['niveau'] == 'DUT 1') echo 'checked'; ?>><label>DUT 1</label>
                        <input type="radio" name="niveau" value="DUT 2" <?php if($candidat_existant['niveau'] == 'DUT 2') echo 'checked'; ?>><label>DUT 2</label>
                        <input type="radio" name="niveau" value="DUT Mame" <?php if($candidat_existant['niveau'] == 'DUT Mame') echo 'checked'; ?>><label>DUT Mame</label>
                    </fieldset>

                    <fieldset id="titre">
                        <legend>Modifier mon titre</legend>
                        <input type="radio" name="titre" value="roi" <?php if($candidat_existant['genre_candidat'] == 'masculin') echo 'checked'; ?>><label>Roi</label>
                        <input type="radio" name="titre" value="reine" <?php if($candidat_existant['genre_candidat'] == 'feminin') echo 'checked'; ?>><label>Reine</label>
                    </fieldset>

                    <fieldset id="last">
                        <legend>Changer ma photo (optionnel)</legend>
                        <input type="file" name="new_photo" accept="image/*">
                    </fieldset><br>
                    
                    <button type="submit">Valider les modifications</button>
                    <button type="button" class="btn-cancel" id="btn-cancel">Annuler</button>
                </form>
            </div>

            

    <?php if (isset($_SESSION['candidature_success'])): ?>
            <div class="message success">
                <?php echo htmlspecialchars($_SESSION['candidature_success']); ?>
            </div>
            <?php unset($_SESSION['candidature_success']); ?>
        <?php elseif (isset($_SESSION['candidature_error'])): ?>
            <div class="message error">
                <?php echo htmlspecialchars($_SESSION['candidature_error']); ?>
            </div>
            <?php unset($_SESSION['candidature_error']); ?>
        <?php endif; ?>
                <form id="delete-form" action="delete.php" method="POST" style="display: none;">
                    <input type="hidden" name="id_candidat" value="<?php echo $candidat_existant['id_candidat']; ?>">
                </form>

        <?php else: ?>

            <?php if (isset($_SESSION['candidature_success'])): ?>
            <div class="message success">
                <?php echo htmlspecialchars($_SESSION['candidature_success']); ?>
            </div>
            <?php unset($_SESSION['candidature_success']); ?>
        <?php elseif (isset($_SESSION['candidature_error'])): ?>
            <div class="message error">
                <?php echo htmlspecialchars($_SESSION['candidature_error']); ?>
            </div>
            <?php unset($_SESSION['candidature_error']); ?>
        <?php endif; ?>
                <form id="delete-form" action="delete.php" method="POST" style="display: none;">
                    <input type="hidden" name="id_candidat" value="<?php echo $candidat_existant['id_candidat']; ?>">
                </form>


            <h1 id="h1">Tu veux être Roi ou Reine des codeurs ?</h1>
            <h2 id="h2">Dépose ta candidature!</h2>

            <form action="process_candidature.php" method="POST" enctype="multipart/form-data">
                <fieldset id="haut">
                  <legend>Informations du Candidat</legend>
                  <p style="color: white; text-align: center;">Candidat: <strong><?php echo htmlspecialchars($utilisateur['prenom']) . ' ' . htmlspecialchars($utilisateur['nom']); ?></strong></p>
                </fieldset>

                <fieldset id="middle">
                      <legend>Niveau d'étude</legend>
                      <input type="radio" id="Promo" name="niveau" value="DUT 1" checked ><label for="dut1">DUT 1</label>
                      <input type="radio" id="Parrain" name="niveau" value="DUT 2"><label for="Parrain">DUT 2</label>
                      <input type="radio" id="Mame"name="niveau" value="DUT Mame"><label for="Mame">DUT Mame</label>
                </fieldset>

                <fieldset id="last">
                    <legend>Importer une photo</legend>
                    <input type="file" name="photo" accept="image/*" required>
                </fieldset><br>

                <fieldset id="titre">
                    <legend>Titre</legend>
                    <input type="radio" id="roi" name="titre" value="roi" checked >
                    <label for="roi">Roi</label>
                    <input type="radio" id="reine" name="titre" value="reine"><label for="reine">Reine</label>
                </fieldset><br>
                
                <button type="submit">Valider ma candidature</button>
            </form>
        <?php endif; ?>
    </div>

  </section>

  <script>
    // Script pour le menu hamburger
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const navLinks = document.getElementById('nav-links');
    hamburgerBtn.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });

    // Script pour le toggle affichage/édition de la candidature
    const candidatDisplay = document.getElementById('candidat-display');
    const candidatEdit = document.getElementById('candidat-edit');
    const btnEdit = document.getElementById('btn-edit');
    const btnCancel = document.getElementById('btn-cancel');

    if (btnEdit) {
        btnEdit.addEventListener('click', () => {
            candidatDisplay.style.display = 'none';
            candidatEdit.style.display = 'block';
        });
    }

    if (btnCancel) {
        btnCancel.addEventListener('click', () => {
            candidatEdit.style.display = 'none';
            candidatDisplay.style.display = 'block';
        });
    }

    // Script pour la suppression de la candidature

    const btnDelete = document.getElementById('btn-del');
    const deleteForm = document.getElementById('delete-form');

    if (btnDelete) {
        btnDelete.addEventListener('click', () => {
            if (confirm("ATTENTION: Êtes-vous sûr de vouloir supprimer définitivement votre candidature ? Cette action est irréversible.")) {
                deleteForm.submit();
            }
        });
    }
  </script>
</body>
</html>