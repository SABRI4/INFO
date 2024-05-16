<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "depenses";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fonction pour récupérer le lien vers la photo de profil d'un utilisateur
function getProfilePhoto($userId) {
    global $conn;
    
    // Préparer la requête SQL pour récupérer le lien vers la photo de profil
    $sql = "SELECT photo FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Vérifier si la préparation de la requête a réussi
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($photo);
        
        // Récupérer le résultat de la requête
        $stmt->fetch();
        
        // Retourner le lien vers la photo de profil
        return $photo;
    } else {
        // En cas d'erreur, retourner une chaîne vide
        return "";
    }
}
?>
