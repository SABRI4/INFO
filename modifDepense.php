<?php
session_start();
include 'connect.php';

header('Content-Type: application/json');

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $id = $_POST['id'];
    $categorie = $_POST['categorie'];
    $montant = $_POST['montant'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    $sql = "UPDATE depenses SET categorie=?, montant=?, date=?, description=? WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssii", $categorie, $montant, $date, $description, $id, $user_id);

    if ($stmt->execute()) {
        // Calculer le total des dépenses après la modification
        $total_depenses = calculerTotalDepenses($user_id, $conn);

        // Vérifier le budget de l'utilisateur
        $query = "SELECT budget, email FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && $total_depenses > $user['budget']) {
            // Envoyer un email si le budget est dépassé
            $to = $user['email'];
            $subject = "Alerte Budget Dépassé";
            $message = "Bonjour,\n\nVous avez dépassé votre budget de dépenses fixé à " . $user['budget'] . "€.\nVotre total actuel des dépenses est de " . $total_depenses . "€.\n\nCordialement,\nGestionnaire de Dépenses";
            $headers = "From: noreply@gestionnairededepenses.com";

            mail($to, $subject, $message, $headers);
        }

        echo json_encode(['success' => true, 'message' => 'Dépense modifiée avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification de la dépense.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
}
$conn->close();
?>
