<?php
session_start(); // Démarrer ou reprendre la session

// Vider toutes les variables de session
$_SESSION = array();

// Détruire le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Détruire la session
session_destroy();

// Rediriger l'utilisateur vers la page de connexion
header("Location: connexion.html");
exit();
?>
