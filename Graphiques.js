document.addEventListener("DOMContentLoaded", function() {
    // Récupérer les éléments du formulaire et le canvas
    let budgetForm = document.getElementById("budgetForm");
    let canvas = document.getElementById("graphiqueDepenses");
    let ctx = canvas.getContext("2d");

    // Définir les catégories de budget
    let categories = ["Alimentation", "Logement", "Transport", "Loisirs", "Santé", "Voyages", "Vêtements", "Épargne/Investissement", "Autres"];

    // Créer un tableau pour stocker les budgets par catégorie et initialiser avec des valeurs par défaut (100€)
    let budget = categories.map(function() { return 100; });

    // Créer le graphique initial avec les budgets par défaut
    let graphique = new Chart(ctx, {
        type: "bar",
        data: {
            labels: categories,
            datasets: [{
                label: "Budget",
                data: budget,
                backgroundColor: "rgba(54, 162, 235, 0.5)",
                borderColor: "rgba(54, 162, 235, 1)",
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Ajouter un gestionnaire d'événements pour soumettre le formulaire
    budgetForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Empêcher le formulaire de se soumettre normalement

        // Récupérer les valeurs saisies par l'utilisateur pour chaque catégorie de budget
        categories.forEach(function(categorie, index) {
            let budgetCategorie = parseInt(document.getElementById(categorie.toLowerCase()).value);
            graphique.data.datasets[0].data[index] = budgetCategorie;
        });

        // Mettre à jour le graphique
        graphique.update();
    });
});
