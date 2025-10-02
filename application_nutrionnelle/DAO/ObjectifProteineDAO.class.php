<?php
require_once(__DIR__ . "/../modeles/ObjectifProteine.class.php");
require_once(__DIR__ . "/../include/connexionBD.php");
require_once 'ListeObjectifDAO.class.php';
class ObjectifProteineDAO
{
    /*-- Calculer le nombre de protéines de l'utilisateur --*/
    public static function calculProteine($age, $poids, $niv_activite)
    {
        if ($age <= 6) {
            return 2 * $poids;
        }
        if ($age >= 7 && $age <= 18) {
            return 1.2 * $poids;
        }
        if ($age >= 19 && $age <= 60) {
            if ($niv_activite === "Actif") {
                return 1.2 * $poids;
            }
            if ($niv_activite === "Modéré") {
                return $poids;
            }
            if ($niv_activite === "Sédentaire") {
                return 0.8 * $poids;
            }
        }
        if ($age >= 61) {
            return $poids;
        }
    }

    //Update les proteines consommés a chaque fois que le user rajoute un aliment contenant des proteine
    public static function updateProteineConsomme($idUser, $proteines)
    {
        $pdo = ConnexionBD::getInstance();
        $objPro_id = ListeObjectifDAO::getObjProid($idUser);
        $currentProteinesConsomme = self::getProteineConsomme();
        $proteines += $currentProteinesConsomme;
        $sql = "UPDATE ObjectifProteine SET proteines_consomme = :proteines WHERE objPro_id = :objPro_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':proteines' => $proteines, ':objPro_id' => $objPro_id]);
    }

    //Retourne les protéines consommés de l'utilisateur pour aujourd'hui
    public static function getProteineConsomme()
    {
        $pdo = ConnexionBD::getInstance();
        $idUser = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);
        $id_obj = ListeObjectifDAO::getListObjCurDay($idUser);
        $objPro_id = ListeObjectifDAO::getObjCalid($idUser, $id_obj);
        $start = $pdo->prepare("SELECT proteines_consomme FROM ObjectifProteine WHERE objPro_id = :objPro_id");
        $start->execute(['objPro_id' => $objPro_id]);
        $row = $start->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row['proteines_consomme'];
        }
        return null;
    }
    // Retourne COMPLÉTÉ si l'objectif est atteint et EN COURS s'il n'est pas encore atteint
    public static function etatObjProteine($proteines_min, $proteines_consomme)
    {
        if ($proteines_consomme >= $proteines_min) {
            return "COMPLÉTÉ";
        }
        return "EN COURS";
    }

    //Retourne l'objectif protéine de l'utilisateur
    public static function getObjProteine()
    {
        $idUser = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);
        $pdo = ConnexionBD::getInstance();
        $sql = "SELECT proteines_min, proteines_consomme FROM ObjectifProteine WHERE objPro_id = :objPro_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':objPro_id' => ListeObjectifDAO::getObjProid($idUser)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}

?>