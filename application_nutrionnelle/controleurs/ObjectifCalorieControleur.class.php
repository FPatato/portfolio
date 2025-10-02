<?php
require_once(__DIR__ . '/../DAO/ObjectifCalorieDAO.class.php');
require_once(__DIR__ . '/../DAO/UtilisateurDAO.class.php');
require_once(__DIR__ . '/../DAO/InformationsPersonnellesDAO.class.php');
require_once(__DIR__ . '/../DAO/StatistiqueDAO.class.php');
//Controleur permettant le calcul et l'ajout d'objectif calories
class ObjectifCalorieControleur
{
    // Calcule le nombre de calories minimum à consommé pour l'utilisateur
    public static function CalculCaloriesMin()
    {
        $utilisateur_id = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);
        $currentUserInfo = InformationsPersonnellesDAO::chercher($utilisateur_id);

        $calories_min = ObjectifCalorieDAO::calculCaloriesMin($currentUserInfo['sexe'], $currentUserInfo['age'], $currentUserInfo['niv_activite']);
        $_SESSION['calories_min'] = $calories_min;
        return $calories_min;
    }

    // Calcul du nombre de calories consommé pour l'utilisateur
    public static function CalculCaloriesConsomme()
    {
        $calories_consomme = ObjectifCalorieDAO::getCalorieConsomme();
        return $calories_consomme;
    }

    // Retourne COMPLÉTÉ si l'objectif est atteint et EN COURS s'il n'est pas encore
    public static function calculEtatObjectif()
    {
        $calories_min = self::CalculCaloriesMin();
        $calories_consomme = self::CalculCaloriesConsomme();
        return ObjectifCalorieDAO::etatObjCalorie($calories_min, $calories_consomme);
    }
}
?>