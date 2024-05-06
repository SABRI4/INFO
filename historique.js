document.addEventListener("DOMContentLoaded", function() {
  let historiqueDepenses = JSON.parse(localStorage.getItem("historiqueDepenses")) || [];

  let listeDepenses = document.getElementById("listeDepenses");
  historiqueDepenses.forEach(function(depense, index) {
    let nouvelleDepense = document.createElement("li");
    nouvelleDepense.textContent = `Catégorie: ${depense.categorie}, Montant: ${depense.montant}, Date: ${depense.date}, Description: ${depense.description}`;
    nouvelleDepense.setAttribute("data-categorie", depense.categorie);
    nouvelleDepense.setAttribute("data-montant", depense.montant);
    nouvelleDepense.setAttribute("data-date", depense.date);
    nouvelleDepense.setAttribute("data-description", depense.description);

    // Ajouter un bouton "Modifier" pour chaque dépense
    let boutonModifier = document.createElement("button");
    boutonModifier.textContent = "Modifier";
    boutonModifier.addEventListener("click", function() {
      remplirFormulaireDepense(depense, index); // Appeler la fonction pour remplir le formulaire avec les valeurs de la dépense sélectionnée
    });

    // Ajouter un bouton "Supprimer" pour chaque dépense
    let boutonSupprimer = document.createElement("button");
    boutonSupprimer.textContent = "Supprimer";
    boutonSupprimer.addEventListener("click", function() {
      supprimerDepense(index); // Appeler la fonction pour supprimer la dépense sélectionnée
    });

    nouvelleDepense.appendChild(boutonModifier);
    nouvelleDepense.appendChild(boutonSupprimer);
    listeDepenses.appendChild(nouvelleDepense);
  });
});

function supprimerDepense(index) {
  let historiqueDepenses = JSON.parse(localStorage.getItem("historiqueDepenses")) || [];
  historiqueDepenses.splice(index, 1); // Supprimer la dépense à l'index spécifié
  localStorage.setItem("historiqueDepenses", JSON.stringify(historiqueDepenses)); // Mettre à jour le localStorage

  // Rafraîchir la page pour refléter les modifications
  location.reload();
}

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

function remplirFormulaireDepense(depense, index) {
  let formulaireModification = document.getElementById("formulaireModification");

  // Créer et remplir le formulaire prérempli
  formulaireModification.innerHTML = `
    <form id="formModification">
      <h2>Modifier la dépense :</h2>
      <label for="categorie">Catégorie :</label>
      <select id="categorie" name="categorie" required>
        <option value="alimentation" ${depense.categorie === 'alimentation' ? 'selected' : ''}>Alimentation</option>
        <option value="logement" ${depense.categorie === 'logement' ? 'selected' : ''}>Logement</option>
        <option value="transport" ${depense.categorie === 'transport' ? 'selected' : ''}>Transport</option>
        <option value="loisirs" ${depense.categorie === 'loisirs' ? 'selected' : ''}>Loisirs</option>
        <option value="santé" ${depense.categorie === 'santé' ? 'selected' : ''}>Santé</option>
        <option value="voyages" ${depense.categorie === 'voyages' ? 'selected' : ''}>Voyages</option>
        <option value="vêtements" ${depense.categorie === 'vêtements' ? 'selected' : ''}>Vêtements</option>
        <option value="epargne" ${depense.categorie === 'epargne' ? 'selected' : ''}>Epargne/Investissemnt</option>
        <option value="autres" ${depense.categorie === 'autres' ? 'selected' : ''}>Autres</option>
      </select><br>

      <label for="montant">Montant :</label>
      <input type="number" id="montantModification" value="${depense.montant}" step="0.01" min="0" required><br>
      <label for="date">Date :</label>
      <input type="date" id="dateModification" value="${depense.date}" required><br>
      <label for="description">Description :</label>
      <textarea id="descriptionModification" rows="4" cols="50" required>${depense.description}</textarea><br>
      <button type="submit">Modifier</button>
    </form>
  `;
  
  // Ajouter un gestionnaire d'événements pour le formulaire de modification
  document.getElementById("formModification").addEventListener("submit", function(event) {
    event.preventDefault(); // Empêcher le formulaire de se soumettre normalement
    modifierDepense(index); // Appeler la fonction de modification avec l'index de la dépense
  });
}

function modifierDepense(index) {
  // Récupérer les valeurs modifiées du formulaire de modification
  let categorieModification = document.getElementById("categorie").value;
  let montantModification = document.getElementById("montantModification").value;
  let dateModification = document.getElementById("dateModification").value;
  let descriptionModification = document.getElementById("descriptionModification").value;

  // Récupérer les dépenses depuis le localStorage
  let historiqueDepenses = JSON.parse(localStorage.getItem("historiqueDepenses")) || [];

  // Mettre à jour la dépense dans l'array des dépenses
  historiqueDepenses[index] = {
    categorie: categorieModification,
    montant: montantModification,
    date: dateModification,
    description: descriptionModification
  };

  // Mettre à jour le localStorage avec les dépenses modifiées
  localStorage.setItem("historiqueDepenses", JSON.stringify(historiqueDepenses));

  // Rafraîchir la page pour refléter les modifications
  location.reload();
}

