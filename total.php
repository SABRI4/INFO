<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo 'Accès non autorisé';
    exit();
}

$user_id = $_SESSION['user_id'];

// Calculer le total des dépenses pour le mois en cours
$current_month = date('Y-m');
$query = "SELECT SUM(montant) AS total_depenses FROM depenses WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();
$total_depenses = $result->fetch_assoc()['total_depenses'];

echo $total_depenses ? $total_depenses : 0;

$stmt->close();
$conn->close();

