<?php
session_start();
include 'connect.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo'<script type="text/javascript"> alert("Vous devez être connecté pour effectuer cette action."); window.location = "Ajout.php"; </script>';
    exit;  
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $categorie = $conn->real_escape_string($_POST['categorie']);
    $montant = $conn->real_escape_string($_POST['montant']);
    $date = $conn->real_escape_string($_POST['date']);
    $description = $conn->real_escape_string($_POST['description']);

    //Requete SQL
    $sql = "INSERT INTO depenses (user_id, categorie, montant, date, description) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Erreur lors de la préparation de la requête : " . $conn->error;
        exit;
    }

    // Lier les paramètres et exécuter la requête
    $stmt->bind_param("isdss", $user_id, $categorie, $montant, $date, $description);
    if ($stmt->execute()) {
        echo "Dépense ajoutée avec succès.";
        
    } else {
        echo "Erreur lors de l'ajout de la dépense : " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}

