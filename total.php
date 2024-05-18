<?php
session_start();
require_once 'connect.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Accès non autorisé']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Fonction pour calculer le total des dépenses
function calculerTotalDepenses($user_id, $conn) {
    $query = "SELECT SUM(montant) AS total_depenses FROM depenses WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        return $row['total_depenses'];
    } else {
        return 0; // Retourne 0 si aucune dépense n'est trouvée
    }
}

// Calculer le total des dépenses pour l'utilisateur connecté
$total_depenses = calculerTotalDepenses($user_id, $conn);

echo $total_depenses;

$conn->close();
