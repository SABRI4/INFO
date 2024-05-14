<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            echo "Compte créé avec succès.";
            header("Location: connexion.html"); // Redirige vers le formulaire de connexion
        } else {
            echo "Erreur: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Erreur: " . $conn->error;
    }
    $conn->close();
}

