<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Dancing+Script:wght@400..700&family=Playwrite+CA:wght@100..400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/candidature.css">
  <title>Essaie Page Candidature</title>
</head>
<body>

  <section id="cont">
    <div class="navbar">
    </div>
    <div class="cd">
      <h1>
        &lt;Coder's Dinner/&gt;
      </h1>
    </div>
    
      <button onclick="" id="vote">Votes</button>
    
      <button onclick="" id="cand">Candidature</button>
    
      <button onclick="" id="rest">Resultats</button>
    
  </section>

  <section id="container">

    <div class="form">

        <h1 id="h1">Tu veux être Roi ou Reine des codeurs ?</h1>
        <h2 id="h2">Dépose ta candidature!</h2>

      <form action="">

        <fieldset id="haut">
          <legend>Informations du Candidat </legend>

          <div class="infos-input">
            
            <input type="text" id="prenom" name="prénom" size="40" placeholder="" required><br>
            <label class="lab-prenom" for="prenom">Prénom</label>

          </div>

          <div class="infos-input">

            <input type="text" id="nom" name="nom" size="40" placeholder="" required><br>
            <label class="lab-nom" for="nom">Nom</label>

          </div>
          
        </fieldset>

        <fieldset id="middle">
              <legend>Niveau d'étude</legend>
              <input type="radio" id="Promo"name="niveau d'étude" value="dut1" checked ><label for="dut1">DUT 1</label>
              <input type="radio" id="Parrain" name="niveau d'étude" value="dut2"><label for="Parrain">DUT 2</label>
              <input type="radio" id="Mame"name="niveau d'étude" value="dutMame"><label for="Mame">DUT Mame</label>
        </fieldset>

        <fieldset id="last">
            <legend>Importer une photo</legend>
            <input type="file"  name="photo" accept="image/*" required>
        </fieldset><br>

        <fieldset id="titre">
            <legend>Titre</legend>
            <input type="radio" id="roi" name="titre" value="roi" checked >
            <label for="roi">Roi</label>
            <input type="radio" id="reine" name="titre" value="reine"><label for="reine">Reine</label>
        </fieldset><br>
        
        <button type="submit">Valider ma candidature</button>

      </form>

    </div>

  </section>

  <script src="candidature.js"></script>
</body>
</html>


<?php

include("db"); 

?>