<?php
session_start();
include 'connect.php';

// Vérifiez si l'utilisateur est connecté et s'il est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Récupérer les informations de l'utilisateur à modifier
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $query = "SELECT username, email, ACTIVE, VIP, budget, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Mettre à jour les informations de l'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $active = isset($_POST['active']) ? 1 : 0;
    $vip = isset($_POST['vip']) ? 1 : 0;
    $budget = $_POST['budget'];
    $role = $_POST['role'];

    $query = "UPDATE users SET username = ?, email = ?, ACTIVE = ?, VIP = ?, budget = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiiisi", $username, $email, $active, $vip, $budget, $role, $user_id);
    $stmt->execute();

    header('Location: manage_users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <h1>Modifier Utilisateur</h1>
    <form method="post">
        <label>Nom d'utilisateur:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <br>
        <label>Actif:</label>
        <input type="checkbox" name="active" <?php if ($user['ACTIVE']) echo 'checked'; ?>>
        <br>
        <label>VIP:</label>
        <input type="checkbox" name="vip" <?php if ($user['VIP']) echo 'checked'; ?>>
        <br>
        <label>Budget:</label>
        <input type="number" name="budget" value="<?php echo htmlspecialchars($user['budget']); ?>" required>
        <br>
        <label>Rôle:</label>
        <select name="role">
            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>Utilisateur</option>
            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Administrateur</option>
        </select>
        <br>
        <input type="submit" value="Mettre à jour">
    </form>
</body>
</html>

<?php
$conn->close();
?>
