<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez les données du formulaire
    $message = $_POST["message"];

    // Traitez les données (par exemple, envoyez un e-mail, enregistrez-les dans une base de données, etc.)

    // Répondez avec un message de confirmation
    echo "Votre message a été envoyé avec succès !";
} else {
    // Redirection ou autre traitement si le formulaire n'a pas été soumis via POST
    echo "Une erreur s'est produite lors de l'envoi du formulaire.";
}
?>
