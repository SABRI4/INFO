<?php
session_start();
include 'connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Requête pour toutes les dépenses de l'utilisateur, groupées par catégorie
$query = "SELECT categorie, SUM(montant) AS total FROM depenses WHERE user_id = ? GROUP BY categorie";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$depenses = [];
while ($row = $result->fetch_assoc()) {
    $depenses[$row['categorie']] = $row['total'];
}

echo json_encode(['success' => true, 'depenses' => $depenses]);

$stmt->close();
$conn->close();
