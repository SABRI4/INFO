<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Accès non autorisé']);
    exit;
}

$user_id = $_SESSION['user_id'];
$depense_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($depense_id) {
    // Requête pour une dépense spécifique
    $query = "SELECT * FROM depenses WHERE user_id = ? AND id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $depense_id);
} else {
    // Requête pour toutes les dépenses de l'utilisateur
    $query = "SELECT * FROM depenses WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
$depenses = [];
while ($row = $result->fetch_assoc()) {
    $depenses[] = $row;
}

echo json_encode($depenses);
$conn->close();
