<?php
session_start();
require_once 'connect.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.html");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $budget = $conn->real_escape_string($_POST['budget']);
    $photo = $_FILES['photo'];

    if ($photo['error'] === UPLOAD_ERR_OK) {
        $photoPath = 'uploads/' . basename($photo['name']);
        move_uploaded_file($photo['tmp_name'], $photoPath);
        $_SESSION['photo'] = $photoPath;

        $query = "UPDATE users SET username = ?, email = ?, budget = ?, photo = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdsi", $username, $email, $budget, $photoPath, $user_id);
    } else {
        $query = "UPDATE users SET username = ?, email = ?, budget = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdi", $username, $email, $budget, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['budget'] = $budget;
        echo "Profil mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour du profil : " . $stmt->error;
    }
    $stmt->close();
}

// Récupérer les informations actuelles de l'utilisateur
$query = "SELECT username, email, budget FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil</title>
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
<div class="container">
    <h2>Modifier votre profil</h2>
    <form action="profil.php" method="post" enctype="multipart/form-data">
        <label for="username">Nom d'utilisateur </label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

        <label for="email">Email </label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <label for="budget">Budget </label>
        <input type="number" id="budget" name="budget" value="<?php echo htmlspecialchars($user['budget']); ?>" required><br>

        <label for="photo">Photo de profil </label>
        <input type="file" id="photo" name="photo"><br>

        <button type="submit">Mettre à jour</button>
    </form>
</div>
</body>
</html>
