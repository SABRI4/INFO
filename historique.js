document.addEventListener("DOMContentLoaded", function() {
  fetch('getDepenses.php')  // Assurez-vous que cette URL est correcte
  .then(response => {
    if (!response.ok) {
      throw new Error('Réponse réseau non OK');
    }
    return response.json();
  })
  .then(data => {
    let listeDepenses = document.getElementById("listeDepenses");
    listeDepenses.innerHTML = ''; // Nettoie la liste avant d'ajouter de nouveaux éléments
    data.forEach(function(depense) {
      let nouvelleDepense = document.createElement("li");
      nouvelleDepense.textContent = `Catégorie: ${depense.categorie}, Montant: ${depense.montant}, Date: ${depense.date}, Description: ${depense.description}`;
      nouvelleDepense.setAttribute("data-id", depense.id);
      nouvelleDepense.setAttribute("data-categorie", depense.categorie);
      nouvelleDepense.setAttribute("data-montant", depense.montant);
      nouvelleDepense.setAttribute("data-date", depense.date);
      nouvelleDepense.setAttribute("data-description", depense.description);

      // Ajouter un bouton "Modifier" pour chaque dépense
      let boutonModifier = document.createElement("button");
      boutonModifier.textContent = "Modifier";
      boutonModifier.addEventListener("click", function() {
        remplirFormulaireDepense(depense.id); // Appeler la fonction pour remplir le formulaire
      });

      // Ajouter un bouton "Supprimer" pour chaque dépense
      let boutonSupprimer = document.createElement("button");
      boutonSupprimer.textContent = "Supprimer";
      boutonSupprimer.addEventListener("click", function() {
        supprimerDepense(depense.id); // Utiliser depense.id pour la suppression
      });

      nouvelleDepense.appendChild(boutonModifier);
      nouvelleDepense.appendChild(boutonSupprimer);
      listeDepenses.appendChild(nouvelleDepense);
    });
  })
  .catch(error => {
    console.error('Erreur lors de la récupération des dépenses:', error);
    alert('Erreur lors de la récupération des données');
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

function remplirFormulaireDepense(depenseId) {
  fetch(`getDepenses.php?id=${depenseId}`)  // Charger les détails de la dépense spécifique pour modification
  .then(response => {
    if (!response.ok) {
      throw new Error('Réponse réseau non OK');
    }
    return response.json();
  })
  .then(depense => {
    let formulaireModification = document.getElementById("formulaireModification");
    if (!formulaireModification) {
      console.error("Le formulaire de modification n'existe pas dans le DOM.");
      return;
    }
    

    // Vérification et initialisation des valeurs
    let categorie = depense.categorie !== undefined ? depense.categorie : '';
    let montant = depense.montant !== undefined ? depense.montant : '';
    let date = depense.date !== undefined ? depense.date : '';
    let description = depense.description !== undefined ? depense.description : '';


    formulaireModification.innerHTML = 
    `<form id="formModification">
      <h2>Modifier la dépense :</h2>
      <label for="categorie">Catégorie :</label>
      <select id="categorieModification" name="categorieModification" required>
        <option value="alimentation" ${depense.categorie === 'alimentation' ? 'selected' : ''}>Alimentation</option>
        <option value="logement" ${depense.categorie === 'logement' ? 'selected' : ''}>Logement</option>
        <option value="transport" ${depense.categorie === 'transport' ? 'selected' : ''}>Transport</option>
        <option value="loisirs" ${depense.categorie === 'loisirs' ? 'selected' : ''}>Loisirs</option>
        <option value="santé" ${depense.categorie === 'santé' ? 'selected' : ''}>Santé</option>
        <option value="voyages" ${depense.categorie === 'voyages' ? 'selected' : ''}>Voyages</option>
        <option value="vêtements" ${depense.categorie === 'vêtements' ? 'selected' : ''}>Vêtements</option>
        <option value="epargne" ${depense.categorie === 'epargne' ? 'selected' : ''}>Épargne/Investissement</option>
        <option value="autres" ${depense.categorie === 'autres' ? 'selected' : ''}>Autres</option>
      </select><br>
      <label for="montantModification">Montant :</label>
      <input type="number" id="montantModification" value="${depense.montant}" step="0.01" min="0" required><br>
      <label for="dateModification">Date :</label>
      <input type="date" id="dateModification" value="${depense.date}" required><br>
      <label for="descriptionModification">Description :</label>
      <textarea id="descriptionModification" rows="4" cols="50" required>${depense.description}</textarea><br>
      <button type="submit">Modifier</button>
    </form>`;

    document.getElementById("formModification").addEventListener("submit", function(event) {
      event.preventDefault();
      modifierDepense(depenseId); // Modifier en utilisant l'ID
    });
  })
  .catch(error => console.error('Erreur lors de la récupération des détails de la dépense:', error));
}


function modifierDepense(depenseId) {
  // Récupérer les valeurs modifiées du formulaire de modification
  let categorieModification = document.getElementById("categorieModification").value;
  let montantModification = document.getElementById("montantModification").value;
  let dateModification = document.getElementById("dateModification").value;
  let descriptionModification = document.getElementById("descriptionModification").value;

  let formData = new URLSearchParams();
  formData.append('id', depenseId);
  formData.append('categorie', categorieModification);
  formData.append('montant', montantModification);
  formData.append('date', dateModification);
  formData.append('description', descriptionModification);

  fetch('modifDepense.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Dépense modifiée avec succès");
      location.reload();  // Rafraîchir la page pour refléter les modifications
    } else {
      alert("Erreur lors de la modification de la dépense: " + data.message);
    }
  })
  .catch(error => {
    console.error('Erreur lors de l’envoi de la requête:', error);
    alert('Erreur lors de la modification de la dépense');
  });
}

function supprimerDepense(depenseId) {
  fetch('suppDepense.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `id=${depenseId}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Dépense supprimée avec succès");
      document.querySelector(`li[data-id="${depenseId}"]`).remove(); // Assurez-vous que les éléments li ont un attribut data-id
    } else {
      alert("Erreur lors de la suppression de la dépense");
    }
  })
  .catch(error => alert('Erreur: ' + error));
}

function rechercherDepenses(termeRecherche) {
  fetch(`rechercheDepenses.php?search=${encodeURIComponent(termeRecherche)}`)
  .then(response => {
    if (!response.ok) {
      throw new Error('Réponse réseau non OK');
    }
    return response.json();
  })
  .then(data => {
    // Afficher les résultats de la recherche dans votre interface utilisateur
    afficherResultatsRecherche(data);
  })
  .catch(error => console.error('Erreur lors de la recherche de dépenses:', error));
}
