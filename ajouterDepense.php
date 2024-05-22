<?php
session_start();
require 'vendor/autoload.php'; // Inclure le chargeur automatique de Composer
include 'connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo '<script type="text/javascript"> alert("Vous devez être connecté pour effectuer cette action."); window.location = "Ajout.php"; </script>';
    exit;  
}

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $categorie = $conn->real_escape_string($_POST['categorie']);
    $montant = $conn->real_escape_string($_POST['montant']);
    $date = $conn->real_escape_string($_POST['date']);
    $description = $conn->real_escape_string($_POST['description']);

    // Requete SQL
    $sql = "INSERT INTO depenses (user_id, categorie, montant, date, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Affichage des erreurs de préparation
    if ($stmt === false) {
        echo "Erreur lors de la préparation de la requête : " . $conn->error;
        exit;
    }

    // Lier les paramètres et exécuter la requête
    $stmt->bind_param("isdss", $user_id, $categorie, $montant, $date, $description);

    // Afficher les paramètres après liaison
    if ($stmt->execute()) {
        // Calculer le total des dépenses après l'ajout
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
            $mail = new PHPMailer(true);
            $mail->CharSet = "UTF-8";
            try {

                // Paramètres du serveur SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Remplacez par votre hôte SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'comptedepense205@gmail.com'; // Remplacez par votre adresse email
                $mail->Password = 'inwf odmx dywi rohj'; // Remplacez par votre mot de passe email
                $mail->SMTPSecure = 'PHPMailer::ENCRYPTION_STARTTLS';
                $mail->Port = 587;

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

        echo "Dépense ajoutée avec succès.";
    } else {
        echo "Erreur lors de l'ajout de la dépense : " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
