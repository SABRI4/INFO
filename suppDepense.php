<?php
include 'connect.php';

header('Content-Type: application/json'); // Indiquer que la réponse est en JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $sql = "DELETE FROM depenses WHERE id=?";
    $stmt = $conn->prepare($sql);

    if (false === $stmt) {
        echo json_encode(['success' => false, 'message' => 'Erreur de préparation SQL.']);
        exit;
    }

    $bind = $stmt->bind_param("i", $id);

    if (false === $bind) {
        echo json_encode(['success' => false, 'message' => 'Erreur de liaison des paramètres SQL.']);
        exit;
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Dépense supprimée avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression de la dépense.']);
    }

    $stmt->close();
    $conn->close();
}
