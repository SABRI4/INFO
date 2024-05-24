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
                    echo '<li><a href="centre_aide.php">Centre d'aide</a></li>';
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
                    echo '<div id="totalDepenses"><h2> | Total Dépenses: 0 </h2></div>';

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

<?php if (isset($_SESSION['user_id'])) : ?>
    <div class="changecouleur">
        <form id="colorForm" action="savecouleur.php" method="post">
            <label for="colorpicker">Choisissez une couleur pour les titres :</label>
            <input type="color" id="colorpicker" name="couleur" value="#0c1b8c">
            <button type="submit">Appliquer</button>
        </form>
    </div>
<?php endif; ?>

<br>
<br>
<h2 id="center">Bienvenue sur le gestionnaire de dépenses</h2>
<br>
<br>

<section class="about">
    <h2>À Propos de l'Application</h2>
    <p>Notre Gestionnaire de Dépenses est conçu pour vous aider à suivre et à gérer vos dépenses personnelles de manière simple et efficace. Avec des fonctionnalités telles que l'ajout de dépenses, la visualisation de l'historique et la création de graphiques, vous pouvez garder le contrôle total de vos finances.</p>
    <br>
    <h2>Utilisation</h2>
    <p>Pour pouvoir profiter de notre application, il faut tout d'abord vous créer un compte en cliquant sur "Inscription" dans le menu. Ensuite, vous allez pouvoir rentrer vos dépenses et voir votre historique et vos graphiques.</p>
</section>

<section class="contact">
    <h2>Nous Contacter</h2>
    <p>Nous sommes là pour vous aider ! Si vous avez des questions, des commentaires ou des suggestions, n'hésitez pas à nous contacter.</p>
    <ul>
        <li>Email : comptedepense205@gmail.com</li>
        <li>Téléphone : +1 123 456 789</li>
        <li>Adresse : 123 rue de la Gestion, 75000 Paris, France</li>
    </ul>
    <p>Ou utilisez le formulaire ci-dessous :</p>
    <form id="contactForm">
        <label for="message">Message :</label>
        <textarea id="message" name="message" rows="4" required></textarea><br>
        <button type="submit">Envoyer</button>
    </form>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
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
    document.getElementById('vipForm').addEventListener('submit', function(event) {
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

    // Ajouter un événement pour gérer le formulaire de contact
    document.getElementById('contactForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

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
});
</script>
</body>
</html>
