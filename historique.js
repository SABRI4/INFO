document.addEventListener("DOMContentLoaded", function() {
  let historiqueDepenses = JSON.parse(localStorage.getItem("historiqueDepenses")) || [];

  let listeDepenses = document.getElementById("listeDepenses");
  historiqueDepenses.forEach(function(depense) {
    let nouvelleDepense = document.createElement("li");
    nouvelleDepense.textContent = `Catégorie: ${depense.categorie}, Montant: ${depense.montant}, Date: ${depense.date}, Description: ${depense.description}`;
    nouvelleDepense.setAttribute("data-categorie", depense.categorie);
    nouvelleDepense.setAttribute("data-montant", depense.montant);
    nouvelleDepense.setAttribute("data-date", depense.date);
    nouvelleDepense.setAttribute("data-description", depense.description);
    listeDepenses.appendChild(nouvelleDepense);
  });
});

function trierDepenses() {
  let critereTri = document.getElementById("tri").value;
  let listeDepenses = document.getElementById("listeDepenses");
  let depensesArray = Array.from(listeDepenses.children);

  depensesArray.sort(function(a, b) {
    let valeurA = a.getAttribute(`data-${critereTri}`);
    let valeurB = b.getAttribute(`data-${critereTri}`);

    if (critereTri === "date") {
      return new Date(valeurA) - new Date(valeurB);
    } else if (critereTri === "montant") {
      return parseFloat(valeurA) - parseFloat(valeurB);
    } else {
      return valeurA.localeCompare(valeurB);
    }
  });

  depensesArray.forEach(function(depense) {
    listeDepenses.appendChild(depense);
  });
}

document.getElementById("tri").addEventListener("change", function() {
  trierDepenses();
});
