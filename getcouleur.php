<?php
session_start();
include_once 'connect.php';

header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $query = "SELECT couleur FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(array('success' => true, 'couleur' => $row['couleur']));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Aucune couleur trouvée pour cet utilisateur.'));
    }
    $stmt->close();
} else {
    echo json_encode(array('success' => false, 'message' => 'Utilisateur non connecté.'));
}
$conn->close();
