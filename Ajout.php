<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajout de dépenses</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
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
   
  <h1>Ajouter une dépense</h1>
  <form id="formDepense" action="ajouterDepense.php" method="post">
    <label for="categorie">Choisissez une catégorie :</label>
    <select id="categorie" name="categorie" required>
      <option value="alimentation">Alimentation</option>
      <option value="logement">Logement</option>
      <option value="transport">Transport</option>
      <option value="loisirs">Loisirs</option>
      <option value="santé">Santé</option>
      <option value="voyages">Voyages</option>
      <option value="vêtements">Vêtements</option>
      <option value="epargne">Epargne/Investissement</option>
      <option value="autres">Autres</option>
    </select><br>

    <label for="montant">Donner un montant :</label>
    <input type="number" id="montant" name="montant" step="0.01" min="0" required><br>

    <label for="date">Donner une date :</label>
    <input type="date" id="date" name="date" required><br>

    <label for="description">Donner une description :</label>
    <textarea id="description" name="description" rows="4" cols="50"></textarea><br>

    <button type="submit">Ajouter la dépense</button>
  </form>
  <a href="Historique.php">Voir votre historique</a>
</body>
</html>
