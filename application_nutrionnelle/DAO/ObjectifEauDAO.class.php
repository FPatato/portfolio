<?php
require_once ( __DIR__ . "/../modeles/ObjectifEau.class.php");
require_once ( __DIR__ . "/../Include/connexionBD.php");
require_once 'ListeObjectifDAO.class.php';
class ObjectifEauDAO {

        //Update le nombre d'eau consommé en fonction des paramètres
        public static function updateEauConsomme($idUser, $eau)
        {
            $pdo = ConnexionBD::getInstance();
            $objEau_id = ListeObjectifDAO::getObjEauid($idUser);
            $sql = "UPDATE ObjectifEau SET eau_consomme = :eau WHERE objEau_id = :objEau_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':eau' => $eau, ':objEau_id' => $objEau_id]);
        }

        //Retourne le nombre d'eau consommé pour l'utilisateur
        public static function getEauConsomme($idUser){
            $pdo = ConnexionBD::getInstance();
            $objEau_id = ListeObjectifDAO::getObjEauid($idUser);
            $start = $pdo->prepare("SELECT eau_consomme FROM ObjectifEau WHERE objEau_id = :objEau_id");
            $start->execute(['objEau_id' => $objEau_id]);
            $row = $start->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['eau_consomme'];
            }
            return null;
        }
        //Retourne le nombre d'eau consommé pour l'utilisateur pour la date précise
          public static function GetEauConsommeWithDate($date){
            $conn = connexionBD::getInstance();
            $utilisateur_id = UtilisateurDAO::getIdByUsername($_COOKIE['username']);
            $objEau_id = ListeObjectifDAO::getObjEauid($utilisateur_id);
            $start = $conn->prepare("SELECT eau_consomme FROM ObjectifEau WHERE objEau_id = :objEau_id and date = :date");
            $start->execute(['objEau_id' => $objEau_id, 'date'=>$date]);
            $row = $start->fetch(PDO::FETCH_ASSOC);
           if (!$row || $row['eau_consomme'] == NULL or $row['eau_consomme'] == 0) 
            {
                $row = ['eau_consomme' => 0];
            }
            
            return $row; 
        }
        //Ajoute l'eau consommé dans la base de donnée
        public static function AjouterEauConsomme($idUser, $eau_consomme_ajout){
            $eau_consomme = ObjectifEauDAO::getEauConsomme($idUser);
            $eau_consomme_total = $eau_consomme + $eau_consomme_ajout;
            self::updateEauConsomme($idUser,$eau_consomme_total);
        }

        // Retourne COMPLÉTÉ si l'objectif est atteint et EN COURS s'il n'est pas encore atteint
        public static function etatObjCalorie($ml_min, $eau_consomme)
        {
            if($eau_consomme >= $ml_min) {
                return "COMPLÉTÉ";
            }
            return "EN COURS";
        }
    }

?>