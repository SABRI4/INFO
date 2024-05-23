<?php
session_start();
include 'connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['mois'])) {
    echo json_encode(['success' => false, 'message' => 'Mois non spécifié.']);
    exit();
}

$mois = $_GET['mois']; // Format YYYY-MM

$query = "SELECT categorie, SUM(montant) AS total FROM depenses WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ? GROUP BY categorie";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $mois);
$stmt->execute();
$result = $stmt->get_result();

$depenses = [];

while ($row = $result->fetch_assoc()) {
    $depenses[$row['categorie']] = $row['total'];
}

echo json_encode(['success' => true, 'depenses' => $depenses]);

$stmt->close();
$conn->close();
?>
