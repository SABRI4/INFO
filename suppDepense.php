<?php
session_start();
include 'connect.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $id = $_POST['id'];

    $sql = "DELETE FROM depenses WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Dépense supprimée avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression de la dépense.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
}
$conn->close();

