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

    <button type="submit">Mettre à jour le budget</button>
  </form>
  <canvas id="graphiqueDepenses" width="400" height="400"></canvas>
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    // Récupérer les éléments du formulaire et le canvas
    let budgetForm = document.getElementById("budgetForm");
    let canvas = document.getElementById("graphiqueDepenses");
    let ctx = canvas.getContext("2d");

    let categories = ["alimentation", "logement", "transport", "loisirs", "sante", "voyages", "vetements", "epargne", "autres"];

    let budget = categories.map(function() { return 100; });

    let depenses = categories.map(function() { return 0; });

    // Créer le graphique initial avec les données des dépenses et les budgets par défaut
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

    // Récupérer les données des dépenses depuis le serveur
    fetch('getDepenses_2.php')
      .then(response => {
        if (!response.ok) {
            throw new Error('Réponse réseau non OK');
        }
        return response.json();
      })
      .then(data => {
          console.log('Données reçues:', data); // Ajoutez ceci pour voir les données renvoyées
          if (data.success) {
              categories.forEach(function(categorie, index) {
                  if (data.depenses[categorie]) {
                      console.log(`Dépense pour ${categorie}: ${data.depenses[categorie]}`); // Log chaque dépense
                      graphique.data.datasets[0].data[index] = parseFloat(data.depenses[categorie]);
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

    // Ajouter un gestionnaire d'événements pour soumettre le formulaire
    budgetForm.addEventListener("submit", function(event) {
        event.preventDefault(); 

        categories.forEach(function(categorie, index) {
            let inputElement = document.getElementById(categorie);
            if (inputElement) {
                let budgetCategorie = parseInt(inputElement.value);
                graphique.data.datasets[1].data[index] = budgetCategorie;
            }
        });

        
        graphique.update();
    });
});
  </script>
</body>
</html>
