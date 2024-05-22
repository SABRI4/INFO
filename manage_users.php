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
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
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
</body>
</html>

<?php
$conn->close();
?>
