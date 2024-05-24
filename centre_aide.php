<?php
session_start();
include 'connect.php';

// Récupérer les messages publics
$sql = "SELECT * FROM public_messages ORDER BY posted_at DESC";
$result = $conn->query($sql);
$messages = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    $result->close();
} else {
    echo "Erreur: " . $conn->error;
}

// Récupérer les réponses associées
$replies = [];
$sql_replies = "SELECT * FROM message_replies ORDER BY posted_at ASC";
$result_replies = $conn->query($sql_replies);
if ($result_replies) {
    while ($row = $result_replies->fetch_assoc()) {
        $replies[$row['message_id']][] = $row;
    }
    $result_replies->close();
} else {
    echo "Erreur: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre d'Aide</title>
    <link href="style.css" rel="stylesheet" type="text/css" media="screen" />
    <script>
        function showReplyForm(messageId) {
            var formId = 'replyForm_' + messageId;
            var formElement = document.getElementById(formId);
            formElement.style.display = 'block';
        }
    </script>
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
    <main>
        <h1>Centre d'Aide</h1>
        
        <h2>Poster un Message</h2>
        <form id="postMessageForm" class="form" action="post_message.php" method="post">
            <label for="subject">Sujet:</label>
            <input type="text" id="subject" name="subject" required><br>
            <label for="body">Message:</label>
            <textarea id="body" name="body" rows="5" required></textarea><br>
            <button type="submit">Poster</button>
        </form>
        
        <h2>Messages Publics</h2>
        <div id="messagesList">
            <?php if (!empty($messages)): ?>
                <ul>
                    <?php foreach ($messages as $message): ?>
                        <li>
                            <strong>De:</strong> <?php echo htmlspecialchars($message['username']); ?><br>
                            <strong>Sujet:</strong> <?php echo htmlspecialchars($message['subject']); ?><br>
                            <strong>Message:</strong> <?php echo nl2br(htmlspecialchars($message["body"])); ?><br>
                            <strong>Posté le:</strong> <?php echo $message['posted_at']; ?><br>
                            <button onclick="showReplyForm(<?php echo $message['id']; ?>)">Répondre</button>
                            
                            <div id="replyForm_<?php echo $message['id']; ?>" class="form" style="display:none;">
                                <form action="reponses.php" method="post">
                                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                    <label for="replyBody">Réponse:</label>
                                    <textarea id="replyBody" name="body" rows="3" required></textarea><br>
                                    <button type="submit">Poster la Réponse</button>
                                </form>
                            </div>
                            
                            <?php if (isset($replies[$message['id']])): ?>
                                <ul>
                                    <?php foreach ($replies[$message['id']] as $reply): ?>
                                        <li>
                                            <strong>De:</strong> <?php echo htmlspecialchars($reply['username']); ?><br>
                                            <strong>Réponse:</strong> <?php echo nl2br(htmlspecialchars($reply['body'])); ?><br>
                                            <strong>Posté le:</strong> <?php echo $reply['posted_at']; ?><br>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucun message public pour le moment.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
