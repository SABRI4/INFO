document.addEventListener("DOMContentLoaded", function() {
    fetch('getcouleur.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const couleur = data.couleur || "#0c1b8c"; // Couleur par défaut si aucune n'est définie
                const titres = document.querySelectorAll("h1, h2"); // Appliquer la couleur aux éléments HTML appropriés
                titres.forEach(titre => {
                    titre.style.color = couleur;
                });
            } else {
                console.error('Erreur lors de la récupération de la couleur:', data.message);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération de la couleur:', error);
        });
});
