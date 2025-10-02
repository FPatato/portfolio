<?php
require_once(__DIR__ . '/../DAO/ObjectifEauDAO.class.php');
require_once(__DIR__ . '/../DAO/UtilisateurDAO.class.php');

//Controleur permettant le calcul et l'ajout d'objectif eau
class ObjectifEauControler
{
    // Calcule le nombre de ml d'eau minimum à consommé pour l'utilisateur
    public static function CalculEauConsomme()
    {
        $utilisateur_id = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);
        $eau_consomme = ObjectifEauDAO::getEauConsomme($utilisateur_id);
        return $eau_consomme;
    }
    // Calcul du nombre de ml d'eau consommé par l'utilisateur aujourd'hui
    public static function GetEauConsommeWithDate($date)
    {
        return ObjectifEauDAO::GetEauConsommeWithDate($date);
    }
    // Ajoute le nombre de ml d'eau consommé pour l'utilisateur dans la base de données
    public static function AjouterEauConsomme()
    {
        if (isset($_POST['eau_consomme'])) {
            $eau_consomme_form = $_POST['eau_consomme'];
            /*--Update le nombre d'eau consomme--*/
            ObjectifEauDAO::AjouterEauConsomme($_SESSION['user_id'], $eau_consomme_form);
            header("Location: aliment.php");
        } else {
            return "Valeur de eau_consomme incorrect.";
        }
    }

    // Retourne COMPLÉTÉ si l'objectif est atteint et EN COURS s'il n'est pas encore atteint
    public static function calculEtatObjectif()
    {
        $ml_min = 2000;
        $eau_consomme = self::CalculEauConsomme();
        return ObjectifProteineDAO::etatObjProteine($ml_min, $eau_consomme);
    }
}
?>