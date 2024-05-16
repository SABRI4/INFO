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
<header>
        <div class="container">
            <h1>Gestionnaire de dépenses</h1>
            <nav>
                <ul>
                    <li><a href="Accueil.php">Accueil</a></li>
                    <li><a href="Ajout.php">Ajout Dépense</a></li>
                    <li><a href="Historique.php">Historique</a></li>
                    <li><a href="Graphiques.php">Graphiques</a></li>
                    <li>
            <?php
            session_start();
            if (isset($_SESSION['user_id'])) {
                echo '<a href="logout.php">Déconnexion</a>';
            } else {
                echo '<a href="connexion.html">Connexion</a> | <a href="compte.html">Inscription</a>';
            }
            ?>
        </li>
                </ul>
            </nav>
        </div>
    </header>

<h1>Historique de vos dépenses :</h1>
<label for="tri">Choisissez un ordre de tri :</label>
<select id="tri" onchange="trierDepenses()">
  <option value="date">Date</option>
  <option value="montant">Montant</option>
  <option value="categorie">Catégorie</option>
</select>

<form action="recherche.php" method="post">
  <label for="search">Rechercher une dépense :</label>
  <input type="text" id="search" name="search">
  <button type="submit">Rechercher</button>
</form>

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
