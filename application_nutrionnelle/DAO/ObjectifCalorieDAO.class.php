<?php
require_once(__DIR__ . "/../modeles/ObjectifCalorie.class.php");
require_once(__DIR__ . "/../include/connexionBD.php");
require_once 'ListeObjectifDAO.class.php';
class ObjectifCalorieDAO
{
    /*-- Calculer le nombre de calories minimum a consommé pour l'utilisateur --*/
    public static function calculCaloriesMin($sexe, $age, $niv_activite)
    {

        if ($sexe === "homme") {

            if ($age <= 6) {

                if ($niv_activite === "Actif") {
                    return 1800;
                }
                if ($niv_activite === "Modéré") {
                    return 1600;
                }
                if ($niv_activite === "Sédentaire") {
                    return 1400;
                }
            }
            if ($age >= 7 && $age <= 18) {

                if ($niv_activite === "Actif") {
                    return 3200;
                }
                if ($niv_activite === "Modéré") {
                    return 2800;
                }
                if ($niv_activite === "Sédentaire") {
                    return 2400;
                }
            }
            if ($age >= 19 && $age <= 60) {

                if ($niv_activite === "Actif") {
                    return 3000;
                }
                if ($niv_activite === "Modéré") {
                    return 2800;
                }
                if ($niv_activite === "Sédentaire") {
                    return 2600;
                }
            }
            if ($age >= 61) {
                return 2600;
            }

        }

        if ($sexe === "femme") {

            if ($age <= 6) {

                if ($niv_activite === "Actif") {
                    return 1600;
                }
                if ($niv_activite === "Modéré") {
                    return 1400;
                }
                if ($niv_activite === "Sédentaire") {
                    return 1200;
                }
            }
            if ($age >= 7 && $age <= 18) {

                if ($niv_activite === "Actif") {
                    return 2400;
                }
                if ($niv_activite === "Modéré") {
                    return 2100;
                }
                if ($niv_activite === "Sédentaire") {
                    return 1800;
                }
            }
            if ($age >= 19 && $age <= 60) {

                if ($niv_activite === "Actif") {
                    return 2400;
                }
                if ($niv_activite === "Modéré") {
                    return 2200;
                }
                if ($niv_activite === "Sédentaire") {
                    return 2000;
                }
            }
            if ($age >= 61) {
                return 2000;
            }
        }
    }

    //Permet de update les calories consomme a chaque fois que l'utilisateur rajoute un aliment
    public static function updateCaloriesConsomme($idUser, $calories)
    {
        $pdo = ConnexionBD::getInstance();
        $id_obj = ListeObjectifDAO::getListObjCurDay($idUser);
        $objCal_id = ListeObjectifDAO::getObjCalid($idUser, $id_obj[0]['id_obj']);
        $currentCaloriesConsomme = self::getCalorieConsomme();
        $calories += $currentCaloriesConsomme;
        $sql = "UPDATE ObjectifCalorie SET calories_consomme = :calories WHERE objCal_id = :objCal_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':calories' => $calories, ':objCal_id' => $objCal_id]);
    }

    //Retourne les calories consommées de l'utilisateur pour aujourd'hui
    public static function getCalorieConsomme()
    {
        $pdo = ConnexionBD::getInstance();
        $idUser = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);
        $id_obj = ListeObjectifDAO::getListObjCurDay($idUser);
        $objCal_id = ListeObjectifDAO::getObjCalid($idUser, $id_obj);
        $start = $pdo->prepare("SELECT calories_consomme FROM ObjectifCalorie WHERE objCal_id = :objCal_id");
        $start->execute(['objCal_id' => $objCal_id]);
        $row = $start->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row['calories_consomme'];
        }
        return null;
    }

    // Retourne COMPLÉTÉ si l'objectif est atteint et EN COURS s'il n'est pas encore atteint
    public static function etatObjCalorie($calories_min, $calories_consomme)
    {
        if ($calories_consomme >= $calories_min) {
            return "COMPLÉTÉ";
        }
        return "EN COURS";
    }
}

?>