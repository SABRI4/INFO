<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Graphiques</title>
  <link href="style.css" rel="stylesheet" type="text/css" media="screen" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

                    // Vérifier si l'utilisateur est un administrateur
                    if ($_SESSION['role'] === 'admin') {
                        echo '<li><a href="manage_users.php">Gérer Utilisateurs</a></li>';
                    }

                    // Afficher la photo de profil et le nom d'utilisateur
                    echo '<li class="user-profile">';
                    echo '<img src="' . $_SESSION['photo'] . '" alt="Photo de profil">';
                    echo '<span class="username">' . $_SESSION['username'] . '</span>';
                    echo '</li>';
                    echo '<div id="totalDepenses"><h2> | Total Dépenses: 0 <h2></div>';
                } else {
                    echo '<li><a href="connexion.html">Connexion</a></li><li><a href="compte.html">Inscription</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</header>
  <h1>Graphiques</h1>
  <h3>Quel est votre budget pour chacune de ces catégories ?</h3>
  <form id="budgetForm">
    <label for="alimentation">Alimentation :</label>
    <input type="number" id="alimentation" name="alimentation" min="0" value="100"><br>

    <label for="logement">Logement :</label>
    <input type="number" id="logement" name="logement" min="0" value="100"><br>

    <label for="transport">Transport :</label>
    <input type="number" id="transport" name="transport" min="0" value="100"><br>

    <label for="loisirs">Loisirs :</label>
    <input type="number" id="loisirs" name="loisirs" min="0" value="100"><br>

    <label for="sante">Santé :</label>
    <input type="number" id="sante" name="sante" min="0" value="100"><br>

    <label for="voyages">Voyages :</label>
    <input type="number" id="voyages" name="voyages" min="0" value="100"><br>

    <label for="vetements">Vêtements :</label>
    <input type="number" id="vetements" name="vetements" min="0" value="100"><br>

    <label for="epargne">Épargne/Investissement :</label>
    <input type="number" id="epargne" name="epargne" min="0" value="100"><br>

    <label for="autres">Autres :</label>
    <input type="number" id="autres" name="autres" min="0" value="100"><br>

    <label for="mois">Mois :</label>
    <input type="month" id="mois" name="mois" required><br>

    <button type="submit">Mettre à jour le budget</button>
  </form>
  <canvas id="graphiqueDepenses" width="400" height="400"></canvas>
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    let budgetForm = document.getElementById("budgetForm");
    let canvas = document.getElementById("graphiqueDepenses");
    let ctx = canvas.getContext("2d");

    let categories = ["alimentation", "logement", "transport", "loisirs", "sante", "voyages", "vetements", "epargne", "autres"];

    let budget = categories.map(function() { return 100; });

    let depenses = categories.map(function() { return 0; });

    let graphique = new Chart(ctx, {
        type: "bar",
        data: {
            labels: categories,
            datasets: [
                {
                    label: "Dépenses",
                    data: depenses,
                    backgroundColor: "rgba(255, 99, 132, 0.5)",
                    borderColor: "rgba(255, 99, 132, 1)",
                    borderWidth: 1
                },
                {
                    label: "Budget",
                    data: budget,
                    backgroundColor: "rgba(54, 162, 235, 0.5)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function updateGraph(month) {
        fetch(`getDepenses_2.php?mois=${month}`)
          .then(response => {
            if (!response.ok) {
                throw new Error('Réponse réseau non OK');
            }
            return response.json();
          })
          .then(data => {
              console.log('Données reçues:', data);
              if (data.success) {
                  categories.forEach(function(categorie, index) {
                      if (data.depenses[categorie]) {
                          console.log(`Dépense pour ${categorie}: ${data.depenses[categorie]}`);
                          graphique.data.datasets[0].data[index] = parseFloat(data.depenses[categorie]);
                      } else {
                          graphique.data.datasets[0].data[index] = 0;
                      }
                  });
                  graphique.update();
              } else {
                  console.error('Erreur lors de la récupération des dépenses:', data.message);
              }
          })
          .catch(error => {
              console.error('Erreur lors de la récupération des dépenses:', error);
          });
    }

    // Ajouter un gestionnaire d'événements pour soumettre le formulaire
    budgetForm.addEventListener("submit", function(event) {
        event.preventDefault();

        let month = document.getElementById("mois").value;
        if (month) {
            updateGraph(month);

            categories.forEach(function(categorie, index) {
                let inputElement = document.getElementById(categorie);
                if (inputElement) {
                    let budgetCategorie = parseInt(inputElement.value);
                    graphique.data.datasets[1].data[index] = budgetCategorie;
                }
            });

            graphique.update();
        } else {
            alert("Veuillez sélectionner un mois.");
        }
    });
});
  </script>
</body>
</html>
