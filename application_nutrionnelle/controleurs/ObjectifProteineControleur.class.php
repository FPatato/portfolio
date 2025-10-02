<?php
require_once(__DIR__ . '/../DAO/ObjectifProteineDAO.class.php');
require_once(__DIR__ . '/../DAO/UtilisateurDAO.class.php');
require_once(__DIR__ . '/../DAO/StatistiqueDAO.class.php');

//Controleur pour le calcul ou l'ajout des objectifs protéines
class ObjectifProteineControler
{
    // Calcule le nombre de protéines minimum à consommé pour l'utilisateur
    public static function CalculProteinesMin()
    {
        $utilisateur_id = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);
        $currentUserInfo = InformationsPersonnellesDAO::chercher($utilisateur_id);

        $proteine_min = ObjectifProteineDAO::calculProteine($currentUserInfo['age'], $currentUserInfo['poids'], $currentUserInfo['niv_activite']);
        $_SESSION['proteines_min'] = $proteine_min;
        return $proteine_min;
    }

    // Calcul du nombre de protéines consommé pour l'utilisateur
    public static function CalculProteinesConsomme()
    {
        $proteines_consomme = ObjectifProteineDAO::GetProteineConsomme();
        return $proteines_consomme;
    }

    // Retourne COMPLÉTÉ si l'objectif est atteint et EN COURS s'il n'est pas encore atteint
    public static function calculEtatObjectif()
    {
        $proteines_min = self::CalculProteinesMin();
        $proteines_consomme = self::CalculProteinesConsomme();
        return ObjectifProteineDAO::etatObjProteine($proteines_min, $proteines_consomme);
    }
    // Retourne le nombre de protéines consommés par l'utilisateur aujourd'hui
    public static function getObjProteineforDay()
    {
        $proteines_consomme = ObjectifProteineDAO::getObjProteine();
        return $proteines_consomme;
    }
}
?>