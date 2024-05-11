<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categorie = $conn->real_escape_string($_POST['categorie']);
    $montant = $conn->real_escape_string($_POST['montant']);
    $date = $conn->real_escape_string($_POST['date']);
    $description = $conn->real_escape_string($_POST['description']);

    $sql = "INSERT INTO depenses (categorie, montant, date, description)
    VALUES ('$categorie', '$montant', '$date', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "Dépense ajoutée avec succès.";
        // Rediriger vers une autre page ou afficher un message de succès
    } else {
        echo "Erreur lors de l'ajout de la dépense : " . $conn->error;
    }

    $conn->close();
}

