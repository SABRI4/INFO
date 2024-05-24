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
                $mail->SMTPSecure = 'PHPMailer::ENCRYPTION_STARTTLS';
                $mail->Port = 587;

                # ENCODAGE UTF8
                $mail->CharSet = 'UTF-8';
                // Destinataires
                $mail->setFrom('comptedepense205@gmail.com', 'Gestionnaire de Dépenses');
                $mail->addAddress($user['email']);

                $mail->isHTML(false);
                $mail->Subject = 'Alerte Budget bientôt dépassé';
                $mail->Body    = "Bonjour,\n\nVous avez bientôt dépassé votre budget de dépenses (80% ou plus alloué) fixé à " . $user['budget'] . "€.\nVotre total actuel des dépenses du mois est de " . $total_depenses . "€.\n\nCordialement,\nGestionnaire de Dépenses";

                $mail->send();
                echo 'Le message a été envoyé avec succès.';
            } catch (Exception $e) {
                echo "Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
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
                $mail->SMTPSecure = 'PHPMailer::ENCRYPTION_STARTTLS';
                $mail->Port = 587;

                # ENCODAGE UTF8
                $mail->CharSet = 'UTF-8';
                // Destinataires
                $mail->setFrom('comptedepense205@gmail.com', 'Gestionnaire de Dépenses');
                $mail->addAddress($user['email']);

                $mail->isHTML(false);
                $mail->Subject = 'Alerte Budget Dépassé';
                $mail->Body    = "Bonjour,\n\nVous avez dépassé votre budget de dépenses fixé à " . $user['budget'] . "€.\nVotre total actuel des dépenses est de " . $total_depenses . "€.\n\nCordialement,\nGestionnaire de Dépenses";

                $mail->send();
                echo 'Le message a été envoyé avec succès.';
            } catch (Exception $e) {
                echo "Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
            }
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
