<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $message_id = $_POST['message_id'];
    $body = $conn->real_escape_string($_POST['body']);
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    $sql = "INSERT INTO message_replies (message_id, user_id, username, body) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $message_id, $user_id, $username, $body);

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
