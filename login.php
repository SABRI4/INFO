<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, username, password, photo, role, ACTIVE, VIP FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if ($user['ACTIVE'] == 1) { // Vérifie si l'utilisateur est actif
            if (password_verify($password, $user['password'])) {
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['photo'] = $user['photo'];
                $_SESSION['role'] = $user['role']; // Stocker le rôle de l'utilisateur
                $_SESSION['VIP'] = $user['VIP']; // Stocker le statut VIP

                echo "Connexion réussie.";
                header("Location: Accueil.php"); // Rediriger l'utilisateur vers l'accueil
                exit();
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Votre compte est inactif. Veuillez contacter l'administrateur.";
        }
    } else {
        echo "Utilisateur non trouvé.";
    }

    $stmt->close();
    $conn->close();
}
