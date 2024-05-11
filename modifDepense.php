<?php
include 'connect.php';

header('Content-Type: application/json'); // Indiquer que la réponse est en JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $categorie = $_POST['categorie'];
    $montant = $_POST['montant'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    $sql = "UPDATE depenses SET categorie=?, montant=?, date=?, description=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if (false === $stmt) {
        echo json_encode(['success' => false, 'message' => 'Erreur de préparation SQL.']);
        exit;
    }

    $bind = $stmt->bind_param("sdsdi", $categorie, $montant, $date, $description, $id);

    if (false === $bind) {
        echo json_encode(['success' => false, 'message' => 'Erreur de liaison des paramètres SQL.']);
        exit;
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Dépense modifiée avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification de la dépense.']);
    }

    $stmt->close();
    $conn->close();
}
