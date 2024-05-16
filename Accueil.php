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


    <br>
    <br>
    <h2 id=center>Bienvenue sur le gestionnaire de dépenses</h2>
    <br>
    <br>

    <section class="about">
    <h2>À Propos de l'Application</h2>
    <p>Notre Gestionnaire de Dépenses est conçu pour vous aider à suivre et à gérer vos dépenses personnelles de manière simple et efficace. Avec des fonctionnalités telles que l'ajout de dépenses, la visualisation de l'historique et la création de graphiques, vous pouvez garder le contrôle total de vos finances.</p>
    <br>
    <h2>Utilisation</h2>
    <p>Pour pouvoir profiter de notre application il faut tout d'abord vous créer un compte, "inscription" dans le menu. Ensuite vous allez pour rentrer vos dépenses et pouvoir voir votre historique et vos graphiques.</p>
  </section>

  <section class="contact">
    <h2>Nous Contacter</h2>
    <p>Nous sommes là pour vous aider ! Si vous avez des questions, des commentaires ou des suggestions, n'hésitez pas à nous contacter.</p>
    <ul>
        <li>Email : contact@gestionnairededepenses.com</li>
        <li>Téléphone : +1 123 456 789</li>
        <li>Adresse : 123 rue de la Gestion, 75000 Paris, France</li>
    </ul>
    <p>Ou utilisez le formulaire ci-dessous :</p>
    <form id="form" action="contact.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required><br>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br>
        <label for="message">Message :</label>
        <textarea id="message" name="message" rows="4" required></textarea><br>
        <button type="submit">Envoyer</button>
    </form>
</section>






</body>

</html>
