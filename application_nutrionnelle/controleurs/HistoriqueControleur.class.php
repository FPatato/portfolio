<?php
require_once(__DIR__ . '/../DAO/HistoriqueDAO.class.php');

class HistoriqueControleur
{
    //Fonction permettant l'affichage de l'historique dans la section Profil de l'utilisateur
    public static function GetLastAction()
    {
        return HistoriqueDAO::GetLastAction();
    }
}
?>