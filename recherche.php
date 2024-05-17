<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire de dépenses</title>
    <link rel="stylesheet" href="style.css">
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

                    // Afficher la photo de profil et le nom d'utilisateur
                    echo '<li class="user-profile">';
                    echo '<img src="' . $_SESSION['photo'] . '" alt="Photo de profil">';
                    echo '<span class="username">' . $_SESSION['username'] . '</span>';
                    echo '</li>';
                }
                else{
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
