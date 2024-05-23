<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $budget = $conn->real_escape_string($_POST['budget']);
    
    // Définir le chemin de l'image par défaut
    $photo_destination = "uploads/avatar.jpg";

    // Vérifier si un fichier de photo a été téléchargé
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
        $photo_name = $_FILES["photo"]["name"];
        $photo_tmp_name = $_FILES["photo"]["tmp_name"];
        $photo_destination = "uploads/" . $photo_name;

        // Déplacer la photo téléchargée vers le dossier de destination
        if (!move_uploaded_file($photo_tmp_name, $photo_destination)) {
            echo "Une erreur s'est produite lors de l'enregistrement de la photo.";
            exit();
        }
    }

    // Préparer et exécuter la requête d'insertion
    $sql = "INSERT INTO users (username, email, password, photo, budget, ACTIVE) VALUES (?, ?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssi", $username, $email, $password, $photo_destination, $budget);
        if ($stmt->execute()) {
            echo "Compte créé avec succès.";
            header("Location: connexion.html"); // Rediriger vers le formulaire de connexion
            exit(); // Terminer le script après la redirection
        } else {
            echo "Erreur: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Erreur: " . $conn->error;
    }
    
    $conn->close();
}