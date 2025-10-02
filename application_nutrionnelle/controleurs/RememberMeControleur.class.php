<?php
require_once(__DIR__ . '/../DAO/RememberMeDAO.class.php');

class RememberMeControleur
{

    // Enregistrer le token dans la base de données
    public static function ajouterToken($utilisateur_id, $token, $date_expiration)
    {
        RememberMeDAO::ajouterToken($utilisateur_id, $token, $date_expiration);
    }

    // Vérifier le token et récupérer l'utilisateur
    public static function verifierToken($token)
    {
        return RememberMeDAO::verifierToken($token);
    }

    // Supprimer le token de la base de données
    public static function supprimerToken($token)
    {
        RememberMeDAO::supprimerToken($token);
    }

    // Supprimer tous les tokens expirés
    public static function supprimerTokensExpiires()
    {
        RememberMeDAO::supprimerTokensExpiires();
    }
}
?>