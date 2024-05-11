<?php
include 'connect.php'; // Votre script de connexion à la base de données

// Vérifier si un ID a été fourni
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM depenses WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $depense = $result->fetch_assoc();
    echo json_encode($depense);
} else {
    $query = "SELECT * FROM depenses";
    $result = $conn->query($query);
    $depenses = [];
    while ($row = $result->fetch_assoc()) {
        $depenses[] = $row;
    }
    echo json_encode($depenses);
}
$conn->close();

