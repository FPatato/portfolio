<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../controleurs/RememberMeControleur.class.php');
session_start();

// Supprimer le token de la base de données si le cookie existe
if (isset($_COOKIE['rememberMe'])) {
    RememberMeControleur::supprimerToken($_COOKIE['rememberMe']);
    
    // Supprimer le cookie côté client
    setcookie('rememberMe', '', time() - 3600, "/");
    error_log("Cookie 'rememberMe' supprimé.");
}
// Fonction qui supprime tout les cookies
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}
$_SESSION = array();
session_destroy();
error_log("Session détruite.");

header('Location: Page_connexion/Connexion.php');
exit;
?>