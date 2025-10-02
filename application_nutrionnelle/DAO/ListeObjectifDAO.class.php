<?php
require_once(__DIR__ . "/../vue/page_aliment/itemNutrition.php");
require_once(__DIR__ . "/../modeles/ListeObjectifs.class.php");
require_once(__DIR__ . "/../include/connexionBD.php");
require_once(__DIR__ . "/../modeles/ObjectifEau.class.php");
require_once(__DIR__ . "/../modeles/ObjectifCalorie.class.php");
require_once(__DIR__ . "/../modeles/ObjectifProteine.class.php");
require_once(__DIR__ . "/../DAO/ObjectifEauDAO.class.php");
require_once(__DIR__ . "/../DAO/ObjectifCalorieDAO.class.php");
require_once(__DIR__ . "/../DAO/ObjectifProteineDAO.class.php");
require_once(__DIR__ . "/../DAO/StatistiqueDAO.class.php");
require_once(__DIR__ . "/../DAO/ListeAlimentDAO.class.php");
require_once(__DIR__ . "/../DAO/HistoriqueDAO.class.php");
require_once(__DIR__ . "/../DAO/InformationsPersonnellesDAO.class.php");
class ListeObjectifDAO
{
    // AJOUTER UNE LISTE D'OBJECTIF
    public static function AjouterListeObjectif(ListeObjectif $objectif)
    {
        $pdo = ConnexionBD::getInstance();
        $start = $pdo->prepare("INSERT INTO ListeObjectif 
            ( objCal_id, objPro_id, objEau_id, date)
            VALUES ( ?, ?, ?, ?)");

        $start->execute([
            $objectif->getObjectifCalorie(),
            $objectif->getObjectifProteine(),
            $objectif->getObjectifEau(),
            $objectif->getDate()
        ]);
        return $pdo->lastInsertId();
    }
    // OBTENIR LE DERNIER ID DE LA LISTE D'OBJECTIF
    public static function getLastId()
    {
        $pdo = ConnexionBD::getInstance();
        $query = $pdo->query("SELECT id_obj FROM ListeObjectif ORDER BY id_obj DESC LIMIT 1");
        $result = $query->fetchColumn();
        return $result;
    }

    // OBTENIR LE ID_OBJ DE L'UTILISATEUR POUR AUJOURD'HUI
    public static function getListObjCurDay($idUser)
    {
        $pdo = ConnexionBD::getInstance();
        $date = date("Y/m/d");
        // Sélectionne le id_obj dans l'historique pour l'utilisateur_id et la date d'aujourd'hui
        $stmt = $pdo->prepare("SELECT id_obj FROM historique WHERE utilisateur_id = :utilisateur_id AND date = :date");
        $stmt->execute([':utilisateur_id' => $idUser, ':date' => $date]);
        // Retourne le id_obj de l'utilisateur pour aujourd'hui
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /// <summary>
    /// les 3 fonction getObj permette d'avoir l'objectif d'aujoudhui ou en creer un
    /// </summary>
    // OBTENIR LE objCal_id D'UNE LISTE D'OBJECTIF PRÉCISE
    public static function getObjCalid($idUser, $id_obj)
    {
        $pdo = ConnexionBD::getInstance();

        // Créations des variables
        $date = date("Y/m/d");
        $objCal_ids = [];

        // Variable pour les calculs
        $id_obj = self::getListObjCurDay($idUser);
        $currentUserInfo = InformationsPersonnellesDAO::getInformationsPersonnelles($idUser);
        $sexe = $currentUserInfo->getSexe();
        $age = $currentUserInfo->getAge();
        $niv_activite = $currentUserInfo->getNivActivite();
        $calories_min = ObjectifCalorieDAO::calculCaloriesMin($sexe, $age, $niv_activite);
        $calories_consomme = StatistiqueDAO::GetCalories(true);

        // Parcourir la liste d'objectif et ajoute les objCal_id dans le tableau s'il y en a
        foreach ($id_obj as $row) {
            // Parcourir la liste d'objectif
            $stmt = $pdo->prepare("SELECT objCal_id FROM ListeObjectif WHERE id_obj = :id_obj and date = :date");
            $stmt->execute([':id_obj' => $row['id_obj'], ':date' => $date]);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($res as $row) {
                // Ajouter les objCal_id dans le tableau s'il y en a
                $objCal_ids[] = $row['objCal_id'];
            }
        }
        $size = count($objCal_ids);

        //S'il y a un objCal_id, fin d'opération
        if ($size > 0) {
            $_SESSION['calories_min'] = $calories_min;
            //Retourne le objCal_id qu'il a trouvé pour le id_obj en question
            return $row['objCal_id'];
        } else {
            //S'il n'y a pas d'objCal_id, crée un objectif Calorie et retourne son objCal_id
            //Requête pour créer un objectif calorie
            $stmt2 = $pdo->prepare(
                "INSERT INTO ObjectifCalorie (calories_min, calories_consomme, date)

                        VALUES (:calories_min, :calories_consomme, :date)

            "
            );
            $stmt2->execute([':calories_min' => $calories_min, ':calories_consomme' => $calories_consomme, ':date' => $date,]);
            $objCal_id = $pdo->lastInsertId();
            $_SESSION['calories_min'] = $calories_min;
            // Retourne le objCal_id
            return $objCal_id;
        }
    }

    //Obtenir le ObjPro_id de l'utilisateur pour aujourd'hui
    public static function getObjProid($idUser)
    {
        $pdo = ConnexionBD::getInstance();

        //Création des variables pour calculs
        $date = date("Y/m/d");
        $id_obj = self::getListObjCurDay($idUser);

        $currentUserInfo = InformationsPersonnellesDAO::getInformationsPersonnelles($_SESSION['user_id']);
        $age = $currentUserInfo->getAge();
        $niv_activite = $currentUserInfo->getNivActivite();
        $poids = $currentUserInfo->getPoids();

        $proteine_min = ObjectifProteineDAO::calculProteine($age, $poids, $niv_activite);
        $objPro_ids = [];

        // Parcourir la liste d'objectif et ajoute les objPro_id dans le tableau s'il y en a
        foreach ($id_obj as $row) {
            // Parcourir la liste d'objectif
            $stmt = $pdo->prepare("SELECT objPro_id FROM ListeObjectif WHERE id_obj = :id_obj and date = :date");
            $stmt->execute([':id_obj' => $row['id_obj'], ':date' => $date]);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($res as $row) {
                // Ajouter les objPro_id dans le tableau s'il y en a
                $objPro_ids[] = $row['objPro_id'];
            }
        }
        $size = count($objPro_ids);

        //S'il y a un objPro_id, fin d'opération
        if ($size > 0) {
            $_SESSION['proteines_min'] = $proteine_min;
            //Retourne le objPro_id qu'il a trouvé pour le id_obj en question
            return $row['objPro_id'];
        } else {
            //S'il n'y a pas d'objPro_id, crée un objectif Protéine et retourne son objEau_id
            //Requête pour créer un objectif calorie
            $currentUserInfo = InformationsPersonnellesDAO::chercher($_SESSION['user_id']);

            $proteine_consomme = StatistiqueDAO::GetProtein(true);
            $stmt2 = $pdo->prepare(
                "INSERT INTO ObjectifProteine (proteines_min, proteines_consomme, date)

                        VALUES (:proteines_min, :proteines_consomme, :date)

            "
            );
            $stmt2->execute([':proteines_min' => $proteine_min, ':proteines_consomme' => $proteine_consomme, ':date' => $date,]);
            $id_pro = $pdo->lastInsertId();
            $_SESSION['proteines_min'] = $proteine_min;

        }
        return $id_pro;
    }

    //Obtenir le ObjEau_id de l'utilisateur pour aujourd'hui
    public static function getObjEauid($idUser)
    {
        $pdo = ConnexionBD::getInstance();
        $date = date("Y/m/d");
        $id_obj = self::getListObjCurDay($idUser);

        $ml_min = 2000;
        $objEau_ids = [];
        foreach ($id_obj as $row) {
            $pdo = ConnexionBD::getInstance();
            $stmt = $pdo->prepare("SELECT objEau_id FROM ListeObjectif WHERE id_obj = :id_obj and date = :date");
            $stmt->execute([':id_obj' => $id_obj[0]['id_obj'], ':date' => $date]);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($res as $row) {
                $objEau_ids[] = $row['objEau_id'];
            }
        }
        $size = count($objEau_ids);
        if ($size > 0) {
            $_SESSION['ml_min'] = $ml_min;
            # echo "Objectif trouvé pour aujourd'hui.";
            return $row['objEau_id'];
        } else {
            #echo "Aucun Objectif trouvé pour aujourd'hui.";
            $stmt2 = $pdo->prepare(
                "INSERT INTO ObjectifEau (ml_min, eau_consomme, date)

                        VALUES (:ml_min, :eau_consomme, :date)

            "
            );
            $stmt2->execute([':ml_min' => $ml_min, ':eau_consomme' => 0, ':date' => $date,]);
            $id_eau = $pdo->lastInsertId();

            $_SESSION['ml_min'] = $ml_min;
        }
        return $id_eau;
    }


    //Creer une nouvelle listeObjectif si le user en a pas pour aujoudhui ou recupere elle deja creer
    public static function creerNouvelleListeObjectif($idUser = null)
    {
        // Si aucun idUser passé en paramètre, on essaye de récupérer dans la session
        if ($idUser === null) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['user_id'])) {
                $idUser = $_SESSION['user_id'];
            } else {
                throw new Exception("Aucun utilisateur connecté. Impossible de créer une nouvelle liste d'objectif.");
            }
        }

        // Récupérer la liste des objectifs du jour pour cet utilisateur
        $id_obj = self::getListObjCurDay($idUser);

        if (!empty($id_obj) && isset($id_obj[0]['id_obj'])) {
            // Objectif existant : récupérer les IDs liés
            $objCal_id = self::getObjCalid($idUser, $id_obj[0]['id_obj']);
            $objPro_id = self::getObjProid($idUser);
            $objEau_id = self::getObjEauid($idUser);
            return; // On a déjà les objectifs pour aujourd'hui
        } else {
            // Pas d'objectif existant : on crée tout
            $objCal_id = self::getObjCalid($idUser, null); // Le paramètre id_obj est ignoré dans la fonction
            $objPro_id = self::getObjProid($idUser);
            $objEau_id = self::getObjEauid($idUser);
            $date = date("Y/m/d");

            // Créer la nouvelle liste d'objectif
            $id_obj_new = self::AjouterListeObjectif(new ListeObjectif(null, $objCal_id, $objPro_id, $objEau_id, $date));

            // Ajouter à l'historique
            HistoriqueDAO::AddHistorique($idUser, null, $id_obj_new);
        }
    }


}
?>