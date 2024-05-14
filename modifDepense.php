<?php
session_start();
include 'connect.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $id = $_POST['id'];
    $categorie = $_POST['categorie'];
    $montant = $_POST['montant'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    $sql = "UPDATE depenses SET categorie=?, montant=?, date=?, description=? WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssii", $categorie, $montant, $date, $description, $id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Dépense modifiée avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification de la dépense.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
}
$conn->close();

