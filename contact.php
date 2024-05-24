<?php
session_start();
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    // Récupérez les données du formulaire
    $message = $_POST["message"];

    // Récupérez les informations de l'utilisateur connecté
    $nom = $_SESSION['username'];
    $email = $_SESSION['email'];  // Assurez-vous que l'email de l'utilisateur est stocké dans la session

    $mail = new PHPMailer(true);
    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'comptedepense205@gmail.com';
        $mail->Password = 'inwf odmx dywi rohj';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // ENCODAGE UTF8
        $mail->CharSet = 'UTF-8';
        
        // Destinataires
        $mail->setFrom($email, $nom);
        $mail->addAddress('comptedepense205@gmail.com'); // Adresse de destination
        
        // Contenu de l'email
        $mail->isHTML(false);
        $mail->Subject = 'Message de Contact';
        $mail->Body    = "Nom: $nom\nEmail: $email\nMessage:\n$message";

        $mail->send();
       
        // Répondre avec un succès et rediriger l'utilisateur
        $response = ['success' => true, 'message' => 'Votre message a été envoyé avec succès !'];
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => "Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}"];
    }
} else {
    $response = ['success' => false, 'message' => "Une erreur s'est produite lors de l'envoi du formulaire."];
}

echo json_encode($response);

?>
