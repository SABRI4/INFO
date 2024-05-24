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
  fetch(`getDepenses.php?id=${depenseId}`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Réponse réseau non OK');
      }
      return response.json();
    })
    .then(depense => {
      if (depense.error) {
        console.error(depense.error);
        return;
      }

      if (depense.length === 0) {
        console.error('Aucune dépense trouvée avec cet ID.');
        return;
      }

      let formulaireModification = document.getElementById("formulaireModification");
      if (!formulaireModification) {
        console.error("Le formulaire de modification n'existe pas dans le DOM.");
        return;
      }

      // Préremplir le formulaire avec les données de la dépense
      formulaireModification.innerHTML = `
        <form id="formModification">
          <h2>Modifier la dépense :</h2>
          <input type="hidden" id="depenseId" name="id" value="${depense[0].id}" />
          <label for="categorieModification">Catégorie :</label>
          <select id="categorieModification" name="categorie" required>
            <option value="alimentation" ${depense[0].categorie === 'alimentation' ? 'selected' : ''}>Alimentation</option>
            <option value="logement" ${depense[0].categorie === 'logement' ? 'selected' : ''}>Logement</option>
            <option value="transport" ${depense[0].categorie === 'transport' ? 'selected' : ''}>Transport</option>
            <option value="loisirs" ${depense[0].categorie === 'loisirs' ? 'selected' : ''}>Loisirs</option>
            <option value="santé" ${depense[0].categorie === 'santé' ? 'selected' : ''}>Santé</option>
            <option value="voyages" ${depense[0].categorie === 'voyages' ? 'selected' : ''}>Voyages</option>
            <option value="vêtements" ${depense[0].categorie === 'vêtements' ? 'selected' : ''}>Vêtements</option>
            <option value="epargne" ${depense[0].categorie === 'epargne' ? 'selected' : ''}>Épargne/Investissement</option>
            <option value="autres" ${depense[0].categorie === 'autres' ? 'selected' : ''}>Autres</option>
          </select><br>
          <label for="montantModification">Montant :</label>
          <input type="number" id="montantModification" name="montant" value="${depense[0].montant}" step="0.01" min="0" required><br>
          <label for="dateModification">Date :</label>
          <input type="date" id="dateModification" name="date" value="${depense[0].date}" required><br>
          <label for="descriptionModification">Description :</label>
          <textarea id="descriptionModification" name="description" rows="4" cols="50" required>${depense[0].description}</textarea><br>
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
  const form = document.getElementById("formModification");
  const formData = new FormData(form);
  formData.append('id', depenseId);

  fetch('modifDepense.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          alert(data.message);
          if (data.emailMessages) {
              data.emailMessages.forEach(msg => alert(msg));
          }
          if (data.redirect) {
              window.location.href = data.redirect; // Rediriger vers Historique.php
          }
      } else {
          alert('Erreur : ' + data.message);
      }
  })
  .catch(error => console.error('Erreur lors de la requête:', error));
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
