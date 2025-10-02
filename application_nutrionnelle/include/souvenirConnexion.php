<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/connexionBD.php');
require_once(__DIR__ . '/../DAO/RememberMeDAO.class.php');
require_once(__DIR__ . '/../controleurs/RememberMeControleur.class.php');

// Suppression des cookies expirés
RememberMeDAO::supprimerTokensExpiires();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {

    // Si un cookie existe reconnexion
    if (isset($_COOKIE['rememberMe'])) {
        $user = RememberMeControleur::verifierToken($_COOKIE['rememberMe']);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
        } else {
            // Token invalide ou expiré : supprimer le cookie
            setcookie('rememberMe', '', time() - 3600, '/');
        }
    }
}
?>
