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
                <?php
                session_start();
                if (isset($_SESSION['user_id'])) {
                    echo '<li><a href="Ajout.php">Ajout Dépense</a></li><li><a href="Historique.php">Historique</a></li>
                    <li><a href="Graphiques.php">Graphiques</a></li>';
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
