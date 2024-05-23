<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Accès non autorisé']);
    exit;
}
$user_id = $_SESSION['user_id'];
$depense_id = isset($_GET['id']) ? $_GET['id'] : null;
$month = isset($_GET['month']) ? $_GET['month'] : null;

try {
    if ($depense_id) {
        // Requête pour une dépense spécifique
        $query = "SELECT * FROM depenses WHERE user_id = ? AND id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $depense_id);
    } elseif ($month) {
        // Requête pour les dépenses d'un mois spécifique
        $query = "SELECT * FROM depenses WHERE user_id = ? AND MONTH(date) = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $month);
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
} catch (Exception $e) {
    echo json_encode(['error' => 'Erreur lors de la récupération des dépenses : ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
