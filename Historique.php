<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Historique</title>
  <link href="style.css" rel="stylesheet" type="text/css" media="screen"/>
  <script src="historique.js" defer></script>
</head>
<body>
<div>
<?php
    session_start();
    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['user_id'])) {
        // L'utilisateur est connecté, afficher le bouton de déconnexion
        echo '<form action="logout.php" method="post">
                  <button type="submit">Déconnexion</button>
              </form>';
    } else {
        // L'utilisateur n'est pas connecté, afficher un lien vers la page de connexion
        echo '<a href="connexion.html">Connexion</a>';
    }
    ?>
</div>
<h2>Historique de vos dépenses :</h2>
<label for="tri">Choisissez un ordre de tri :</label>
<select id="tri" onchange="trierDepenses()">
  <option value="date">Date</option>
  <option value="montant">Montant</option>
  <option value="categorie">Catégorie</option>
</select>

<ul id="listeDepenses">
  <!-- Les dépenses seront affichées ici -->
  <?php
    include 'afficherDepense.php'; // Ce script charge les dépenses de la base de données et les affiche
    ?>
</ul>
<div id="formulaireModification">
    <!-- Le formulaire de modification sera injecté ici par JavaScript -->
</div>

<a href="Ajout.php">Ajouter une dépense</a>
</body>
</html>
