<?php
session_start();
require 'vendor/autoload.php'; // Inclure le chargeur automatique de Composer
include 'connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

function calculerTotalDepenses($user_id, $conn) {
    $current_month = date('Y-m');
    $query = "SELECT SUM(montant) AS total_depenses FROM depenses WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $user_id, $current_month);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        return $row['total_depenses'];
    } else {
        return 0; // Retourne 0 si aucune dépense n'est trouvée
    }
}

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $id = $_POST['id'] ?? null;
    $categorie = $_POST['categorie'] ?? null;
    $montant = $_POST['montant'] ?? null;
    $date = $_POST['date'] ?? null;
    $description = $_POST['description'] ?? null;

    if (!$id || !$categorie || !$montant || !$date || !$description) {
        $response = ['success' => false, 'message' => 'Des données sont manquantes.'];
        echo json_encode($response);
        exit;
    }

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

        $messages = [];

        if ($user && ($total_depenses > $user['budget']*0.8)) {
            // Envoyer un email si le budget est dépassé de 80 % avec PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Paramètres du serveur SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Remplacez par votre hôte SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'comptedepense205@gmail.com'; // Remplacez par votre adresse email
                $mail->Password = 'inwf odmx dywi rohj'; // Remplacez par votre mot de passe email
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // ENCODAGE UTF8
                $mail->CharSet = 'UTF-8';
                // Destinataires
                $mail->setFrom('comptedepense205@gmail.com', 'Gestionnaire de Dépenses');
                $mail->addAddress($user['email']);

                $mail->isHTML(false);
                $mail->Subject = 'Alerte Budget bientôt dépassé';
                $mail->Body    = "Bonjour,\n\nVous avez bientôt dépassé votre budget de dépenses (80% ou plus alloué) fixé à " . $user['budget'] . "€.\nVotre total actuel des dépenses du mois est de " . $total_depenses . "€.\n\nCordialement,\nGestionnaire de Dépenses";

                $mail->send();
                $messages[] = 'Alerte budget bientôt dépassé envoyée avec succès.';
            } catch (Exception $e) {
                $messages[] = "Alerte budget bientôt dépassé n'a pas pu être envoyée. Mailer Error: {$mail->ErrorInfo}";
            }
        }

        if ($user && $total_depenses > $user['budget']) {
            // Envoyer un email si le budget est dépassé avec PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Paramètres du serveur SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Remplacez par votre hôte SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'comptedepense205@gmail.com'; // Remplacez par votre adresse email
                $mail->Password = 'inwf odmx dywi rohj'; // Remplacez par votre mot de passe email
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // ENCODAGE UTF8
                $mail->CharSet = 'UTF-8';
                // Destinataires
                $mail->setFrom('comptedepense205@gmail.com', 'Gestionnaire de Dépenses');
                $mail->addAddress($user['email']);

                $mail->isHTML(false);
                $mail->Subject = 'Alerte Budget Dépassé';
                $mail->Body    = "Bonjour,\n\nVous avez dépassé votre budget de dépenses fixé à " . $user['budget'] . "€.\nVotre total actuel des dépenses est de " . $total_depenses . "€.\n\nCordialement,\nGestionnaire de Dépenses";

                $mail->send();
                $messages[] = 'Alerte budget dépassé envoyée avec succès.';
            } catch (Exception $e) {
                $messages[] = "Alerte budget dépassé n'a pas pu être envoyée. Mailer Error: {$mail->ErrorInfo}";
            }
        }

        $response = ['success' => true, 'message' => 'Dépense modifiée avec succès.', 'redirect' => 'Historique.php', 'emailMessages' => $messages];
    } else {
        $response = ['success' => false, 'message' => 'Erreur lors de la modification de la dépense.'];
    }

    $stmt->close();
} else {
    $response = ['success' => false, 'message' => 'Accès non autorisé.'];
}

echo json_encode($response);
$conn->close();
