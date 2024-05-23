<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "Accès non autorisé";
    exit();
}

$user_id = $_SESSION['user_id'];
$mois = isset($_GET['month']) ? $_GET['month'] : null;
$annee = date('Y');

try {
    if ($mois) {
        // Requête pour les dépenses par mois
        $query = "SELECT * FROM depenses WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
        $stmt = $conn->prepare($query);
        $date_param = $annee . '-' . str_pad($mois, 2, '0', STR_PAD_LEFT); // Format YYYY-MM
        $stmt->bind_param("is", $user_id, $date_param);
    } else {
        // Requête pour toutes les dépenses de l'utilisateur
        $query = "SELECT * FROM depenses WHERE user_id = ? ORDER BY date DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li data-id='{$row['id']}' data-date='{$row['date']}' data-montant='{$row['montant']}' data-categorie='{$row['categorie']}'>
                  Catégorie: {$row['categorie']}, Montant: {$row['montant']}, Date: {$row['date']}, Description: {$row['description']}
                  <button onclick='modifierDepense({$row['id']})'>Modifier</button>
                  <button onclick='supprimerDepense({$row['id']})'>Supprimer</button>
                  </li>";
        }
    } else {
        echo "<li>Pas de dépenses enregistrées</li>";
    }
} catch (Exception $e) {
    echo "Erreur lors de la récupération des dépenses : " . $e->getMessage();
}

$stmt->close();
$conn->close();
