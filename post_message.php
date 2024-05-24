<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $subject = $conn->real_escape_string($_POST['subject']);
    $body = $conn->real_escape_string($_POST['body']);
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    $sql = "INSERT INTO public_messages (user_id, username, subject, body) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $username, $subject, $body);

    if ($stmt->execute()) {
        header("Location: centre_aide.php");
        exit();
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Accès non autorisé ou données de formulaire manquantes.";
}

$conn->close();
?>
