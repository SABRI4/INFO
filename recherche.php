<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire de dépenses</title>
    <link rel="stylesheet" href="style.css">
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
                    if ($_SESSION['VIP'] == 1) {
                        echo '<li><a href="Graphiques.php">Graphiques</a></li>';
                    }
                    echo '<li><a href="centre_aide.php">Centre d aide</a></li>';
                    echo '<li><a href="profil.php">Modifier Profil</a></li>';
                    echo '<li><a href="logout.php">Déconnexion</a></li>';


                    // Vérifier si l'utilisateur est un administrateur
                    if ($_SESSION['role'] === 'admin') {
                        echo '<li><a href="manage_users.php">Gérer Utilisateurs</a></li>';
                    }

                    // Afficher la photo de profil et le nom d'utilisateur
                    echo '<li class="user-profile">';
                    echo '<img src="' . $_SESSION['photo'] . '" alt="Photo de profil">';
                    echo '<span class="username">' . $_SESSION['username'] . '</span>';
                    echo '</li>';
                    echo '<div id="totalDepenses"></div>';

                    // Formulaire pour devenir VIP
                    if ($_SESSION['VIP'] != 1) {
                        echo '<form id="vipForm" action="become_vip.php" method="post">';
                        echo '<button type="submit">Devenir VIP</button>';
                        echo '</form>';
                    }
                } else {
                    echo '<li><a href="connexion.html">Connexion</a></li><li><a href="compte.html">Inscription</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</header>
<?php
include 'connect.php';

// Vérifiez si le formulaire de recherche a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez le terme de recherche depuis le formulaire
    $searchTerm = mysqli_real_escape_string($conn, $_POST['search']); // Échapper les caractères spéciaux pour éviter les injections SQL

    // ID de l'utilisateur connecté
    $userId = $_SESSION['user_id'];

    // Utilisez le terme de recherche pour filtrer les données de la base de données
    $query = "SELECT * FROM depenses WHERE user_id = '$userId' AND (categorie LIKE '%$searchTerm%' OR montant LIKE '%$searchTerm%' OR date LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%')";
} else {
    // Si aucun terme de recherche n'est spécifié, récupérez toutes les données de l'utilisateur connecté
    $userId = $_SESSION['user_id'];
    $query = "SELECT * FROM depenses WHERE user_id = '$userId'";
}

// Exécutez la requête SQL
$result = mysqli_query($conn, $query);

// Vérifiez si des résultats sont renvoyés
if (mysqli_num_rows($result) > 0) {
    // Affichez les résultats
    while ($row = mysqli_fetch_assoc($result)) {
        // Affichage des données de dépense
        echo "<li class='recherche'>Catégorie: " . $row["categorie"] . ", Montant: " . $row["montant"] . ", Date: " . $row["date"] . ", Description: " . $row["description"] . "</li>";
    }
} else {
    echo "Aucune dépense trouvée pour le terme de recherche '$searchTerm'";
}

// Fermez la connexion à la base de données
mysqli_close($conn);
?>
<script>
document.addEventListener("DOMContentLoaded", function(){
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

    // Ajouter un événement pour gérer le formulaire VIP
    const vipForm = document.getElementById('vipForm');
    if (vipForm) {
        vipForm.addEventListener('submit', function(event) {
            event.preventDefault();
            fetch('become_vip.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Vous êtes maintenant VIP!');
                    location.reload(); // Recharger la page pour mettre à jour l'affichage
                } else {
                    alert('Erreur : ' + data.error);
                }
            })
            .catch(error => {
                console.error('Erreur lors de la requête:', error);
                alert('Erreur lors de la requête');
            });
        });
    }

    // Ajouter un événement pour gérer le formulaire de contact
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(contactForm);

            fetch('contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert('Erreur : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur lors de la requête:', error);
                alert('Erreur lors de la requête');
            });
        });
    }

    // Evenement pour gérer la couleur
    const colorForm = document.getElementById("colorForm");
    if (colorForm) {
        colorForm.addEventListener("submit", function(event) {
            event.preventDefault();
            const formData = new FormData(colorForm);

            fetch('savecouleur.php', {
                method: 'POST',
                body: formData
            })
            .then(() => {
                // Appliquer la nouvelle couleur directement
                const newColor = formData.get('couleur');
                document.documentElement.style.setProperty('--main-title-color', newColor);
                console.log('Couleur mise à jour:', newColor); // Message de débogage
                // Mettre à jour la couleur des titres
                document.querySelectorAll('h1, h2').forEach(element => {
                    element.style.color = newColor;
                });
            })
            .catch(error => {
                console.error('Erreur lors de la requête:', error);
                alert('Erreur lors de la requête.');
            });
        });
    }

});
</script>
