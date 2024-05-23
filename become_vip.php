<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Accès non autorisé']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Mettre à jour le statut VIP de l'utilisateur
    $query = "UPDATE users SET VIP = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['VIP'] = 1; // Mettre à jour le statut VIP dans la session
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Mise à jour du statut VIP échouée']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Erreur lors de la mise à jour du statut VIP : ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
