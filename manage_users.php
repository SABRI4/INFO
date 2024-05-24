<?php
session_start();
include 'connect.php';

// Vérifiez si l'utilisateur est connecté et s'il est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Récupérer la liste des utilisateurs
$query = "SELECT id, username, email, ACTIVE, VIP, budget, role FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer Utilisateurs</title>
    <link rel="stylesheet" href="style.css">
    <script src="couleur.js"></script>
</head>
<header>
    <div class="container">
        <h1>Gestionnaire de dépenses</h1>
        <nav>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<li><a href="Ajout.php">Ajout Dépense</a></li><li><a href="Historique.php">Historique</a></li>';
                    if ($_SESSION['VIP'] == 1) {
                        echo '<li><a href="Graphiques.php">Graphiques</a></li>';
                    }
                    echo '<li><a href="logout.php">Déconnexion</a></li>';
                    echo '<li><a href="profil.php">Modifier Profil</a></li>';

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
<body>
    <h1>Gérer les Utilisateurs</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Actif</th>
                <th>VIP</th>
                <th>Budget</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo $user['ACTIVE'] ? 'Oui' : 'Non'; ?></td>
                    <td><?php echo $user['VIP'] ? 'Oui' : 'Non'; ?></td>
                    <td><?php echo htmlspecialchars($user['budget']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>">Modifier</a> |
                        <a href="delete_users.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

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
});
</script>

</body>
</html>

<?php
$conn->close();
?>
