<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Gestionnaire de dépenses</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
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
  <div>
  <h1>Gestionnaire de dépenses</h1>
  
  <p>Bienvenue sur le gestionnaire de dépenses. Que souhaitez-vous faire ? </p>
  <a href="Ajout.php">Ajouter une dépense</a>
  <a href="Historique.php">Voir votre historique</a>

  
  
</body>

</html>
