<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre d'Aide</title>
    <link href="style.css" rel="stylesheet" type="text/css" media="screen" />
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
                    echo '<li><a href="centre_aide.php">Centre Aide</a></li>';

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
                    echo '<div id="totalDepenses"><h2> | Total Dépenses: 0 <h2></div>';
                } else {
                    echo '<li><a href="connexion.html">Connexion</a></li><li><a href="compte.html">Inscription</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</header>
    <main>
        <h1>Centre d'Aide</h1>
        <h2>Poster un Message</h2>
        <form id="postMessageForm" action="post_message.php" method="post">
            <label for="subject">Sujet:</label>
            <input type="text" id="subject" name="subject" required><br>
            <label for="body">Message:</label>
            <textarea id="body" name="body" rows="5" required></textarea><br>
            <button type="submit">Poster</button>
        </form>
        
        <h2>Messages Publics</h2>
        <div id="messagesList">
            <!-- Les messages seront affichés ici -->
        </div>
    </main>
</body>
</html>
