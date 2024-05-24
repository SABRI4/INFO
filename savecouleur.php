<?php
session_start();
include_once 'connect.php';

if (isset($_POST['couleur']) && isset($_SESSION['user_id'])) {
    $couleur = $_POST['couleur'];
    $userId = $_SESSION['user_id'];

    $query = "UPDATE users SET couleur = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $couleur, $userId);

    if ($stmt->execute()) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Erreur lors de la mise à jour de la couleur.'));
    }
    $stmt->close();
} else {
    echo json_encode(array('success' => false, 'message' => 'Aucune couleur fournie ou utilisateur non connecté.'));
}
$conn->close();
