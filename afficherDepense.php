<?php
include 'connect.php';
$sql = "SELECT * FROM depenses ORDER BY date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<li data-id='{$row['id']}' data-date='{$row['date']}' data-montant='{$row['montant']}' data-categorie='{$row['categorie']}'>
              Catégorie: {$row['categorie']}, Montant: {$row['montant']}, Date: {$row['date']}, Description: {$row['description']}
              <button onclick='modifierDepense({$row['id']})'>Modifier</button>
              <button onclick='supprimerDepense({$row['id']})'>Supprimer</button>
              </li>";
    }
} else {
    echo "<li>Pas de dépenses enregistrées</li>";
}
$conn->close();
