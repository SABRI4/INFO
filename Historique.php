<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Historique</title>
  <link href="style.css" rel="stylesheet" type="text/css" media="screen"/>
  <script src="historique.js" defer></script>
  <script src="couleur.js"></script>
</head>
<body>
<header>
    <div class="container">
        <h1>Gestionnaire de dépenses</h1>
        <nav>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <?php
                session_start();
                if (isset($_SESSION['user_id'])) {
                    echo '<li><a href="Ajout.php">Ajout Dépense</a></li><li><a href="Historique.php">Historique</a></li>';
                    if ($_SESSION['role'] === 'admin' || $_SESSION['VIP'] == 1) {
                        echo '<li><a href="Graphiques.php">Graphiques</a></li>';
                    }
                    echo '<li><a href="logout.php">Déconnexion</a></li>';
                    echo '<li><a href="profil.php">Modifier Profil</a></li>';
                    // Afficher la photo de profil et le nom d'utilisateur
                    echo '<li class="user-profile">';
                    echo '<img src="' . $_SESSION['photo'] . '" alt="Photo de profil">';
                    echo '<span class="username">' . $_SESSION['username'] . '</span>';
                    echo '</li>';
                    echo '<div id="totalDepenses"><h2> |Total Dépenses: 0 <h2></div>';
                }
                else{
                    echo '<li><a href="connexion.html">Connexion</a></li><li><a href="compte.html">Inscription</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</header>

<h1>Historique de vos dépenses :</h1>
<label id="form" for="tri">Choisissez un ordre de tri :</label>
<select id="form" id="tri" onchange="trierDepenses()">
  <option value="date">Date</option>
  <option value="montant">Montant</option>
  <option value="categorie">Catégorie</option>
</select>


<form id="form" action="recherche.php" method="post">
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    function getCurrentMonthYear() {
        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth() + 1; // Les mois commencent à 0
        return `${year}-${month.toString().padStart(2, '0')}`;
    }

    function updateTotalDepenses() {
        fetch('total.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Réponse réseau non OK');
                }
                return response.text();
            })
            .then(data => {
                const currentMonthYear = getCurrentMonthYear();
                document.getElementById('totalDepenses').innerHTML = `Total Dépenses du mois de ${currentMonthYear}: ${data}`;
            })
            .catch(error => {
                console.error('Erreur lors de la récupération du total des dépenses:', error);
            });
    }

  // Appeler updateTotalDepenses pour initialiser le total des dépenses au chargement de la page
  updateTotalDepenses();
});
</script>

</body>
</html>