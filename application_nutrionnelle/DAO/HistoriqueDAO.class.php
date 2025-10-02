<?php
require_once(__DIR__ . "/../include/connexionBD.php");
require_once(__DIR__ . "/../vue/page_aliment/itemNutrition.php");
require_once __DIR__ . "/../modeles/Historique.class.php";
require_once __DIR__ . '/../DAO/AlimentDAO.class.php';
require_once __DIR__ . '/../DAO/ListeAlimentDAO.class.php';
require_once __DIR__ . '/../DAO/HistoriqueDAO.class.php';
require_once __DIR__ . '/../DAO/UtilisateurDAO.class.php';
require_once __DIR__ . '/../DAO/ListeObjectifDAO.class.php';
require_once __DIR__ . '/../DAO/ObjectifCalorieDAO.class.php';
require_once __DIR__ . '/../DAO/ObjectifProteineDAO.class.php';
class HistoriqueDAO
{
    // Fonction créer un Historique pour l'utilisateur
    public static function AddHistorique($idUser, $id_ali, $id_obj)
    {
        // Connexion a la base de donnée
        $pdo = connexionBD::getInstance();
        $date = date("Y/m/d");
        $query = $pdo->prepare("SELECT COUNT(*) FROM ListeObjectif WHERE id_obj = :id_obj AND date = :date");
        $query->execute([':id_obj' => $id_obj, ':date' => $date]);
        $count = $query->fetchColumn();
        $id_hist = HistoriqueDAO::getIdhistbyUsernameid($idUser);
        //Si le nombre de colonne dans le résultat ListeObjectif est de 0, update la variable $id_obj pour NULL
        if ($count == 0) {
            $id_obj = null;
        } else {

            $query = $pdo->prepare("SELECT COUNT(*) FROM historique WHERE id_hist = :id_hist AND date = :date");
            $query->execute([':id_hist' => $id_hist, ':date' => $date]);
            $count_historique = $query->fetchColumn();
            //Si le nombre de colonne dans le résultat historique est de 0, insère l'historique
            if ($count_historique == 0) {

                // Requête SQL qui insère l'historique dans la base de donnée
                $sql = "INSERT INTO Historique (id_hist , utilisateur_id, date, id_ali, id_obj)
                    VALUES (:id_hist,:utilisateur_id, :date, :id_ali, :id_obj)";
                $stmt = $pdo->prepare($sql);
                // Insère les valeur de l'historique dans la table historique
                $stmt->execute([':id_hist' => $id_hist, ':utilisateur_id' => $idUser, ':date' => $date, ':id_ali' => $id_ali, ':id_obj' => $id_obj]);

            } else {

                // Si le nombre de colonne dans le résultat historique est plus de 0, update l'historique plutôt que d'en insérer une
                $sql = "UPDATE Historique SET id_ali = :id_ali, id_obj = :id_obj WHERE id_hist = :id_hist AND date = :date";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id_ali' => $id_ali, ':id_obj' => $id_obj, ':id_hist' => $id_hist, ':date' => $date]);

            }
        }
    }


    // Fonction chercher une Liste d'aliment dans l'historique de l'utilisateur avec $utilisateur_id
    public static function chercherListeAliment($utilisateur_id)
    {
        // Connexion a la base de donnée
        $pdo = connexionBD::getInstance();

        // Requête SQL qui sélectionne l'id_ali de la table historique pour l'utilisateur qui correspond a l'utilisateur_id
        $query = $pdo->prepare("SELECT id_ali FROM historique WHERE utilisateur_id = :utilisateur_id");
        $query->execute([':utilisateur_id' => $utilisateur_id]);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        // Si une row a été créé, retourne l'id_ali
        if ($row) {
            return $row['id_ali'];
        } else {
            // Si aucune row n'a été créé, retourne NULL.
            return null; // ou false si tu préfères
        }
    }

    // Retourne le id de l'historique en fonction de l'id de l'utilisateur
    public static function getIdhistbyUsernameid($utilisateur_id)
    {
        $pdo = connexionBD::getInstance();
        $query = $pdo->prepare("SELECT id_hist FROM historique WHERE utilisateur_id = :utilisateur_id");
        $query->execute([':utilisateur_id' => $utilisateur_id]);
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        return $row[0]['id_hist'];
    }

    // Retourne le id_ali et id_obj de l'historique pour afficher dans Profil
    public static function GetLastAction()
    {
        $pdo = connexionBD::getInstance();
        $idUser = $_SESSION['user_id'];
        $id_hist = HistoriqueDAO::getIdhistbyUsernameid($idUser);
        $query = $pdo->prepare("SELECT id_ali,id_obj,date from historique where id_hist = :id_hist");
        $query->execute([':id_hist' => $id_hist]);
        $info = $query->fetchAll(PDO::FETCH_ASSOC);

        return $info;
    }

    //Fonction pour permettre d'afficher les repas dans l'historique
    public static function GetRepas()
    {
        $hist = self::GetLastAction();
        $pdo = connexionBD::getInstance();
        $aliments = [];
        $query = $pdo->prepare("SELECT id_aliment from listealiment where id_ali = :id_ali ");
        $query2 = $pdo->prepare("SELECT aliment from aliment where id = :id");
        foreach ($hist as $row) {
            $query->execute([':id_ali' => $row['id_ali']]);
            $res = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($res) {
                $size = count($res);

                for ($i = 0; $i < $size; $i++) {
                    $query2->execute([':id' => $res[$i]['id_aliment']]);
                    $ser = $query2->fetchAll(PDO::FETCH_ASSOC);
                    // $aliments[] = ['aliment'=>$ser[$i]['aliment'],  'date' => $row['date']];
                    // echo $aliments[$i]['aliment'];
                    foreach ($ser as $r) {

                        $aliments[] = ['aliment' => $r['aliment'], 'date' => $row['date']];
                    }
                }
            }

        }
        return $aliments;
    }




    //LAISSER LES DEUX FONCTION COMMENT
    //NE PAS LES SUPPRIMER POUR DEVENTUEL UTILISATION FUTUR POTENTIELLE

    /*  
    public static function GetLastObj()
      {
          $hist = self::GetLastAction();
          $pdo = connexionBD::getInstance();
          $objectifs = []; 
          $lastObjId = ListeObjectifDAO::getLastId();     

          //Ajouter objEau_id quand la fonction est fini objEau_id
          $query = $pdo->prepare("SELECT objCal_id, objPro_id, objEau_id FROM listeobjectif WHERE id_obj = :id_obj");
          $query2 = $pdo->prepare("SELECT calories_min FROM objectifcalorie WHERE objCal_id = :objCal_id");
          $query3 = $pdo->prepare("SELECT proteines_min FROM objectifproteine WHERE objPro_id = :objPro_id");
          $query4 = $pdo->prepare("SELECT ml_min FROM objectifeau WHERE objEau_id = :objEau_id");
      
          foreach ($hist as $row) 
          {
              
              if ($row['id_obj'] != $lastObjId) 
              {
                  continue;
              }
              $query->execute([':id_obj' => $row['id_obj']]);
              $res = $query->fetch(PDO::FETCH_ASSOC);
      
              if ($res) {
                  $calories = null;
                  $proteines = null;
                  $eau = null;
      
                  if ($res && isset($res['objCal_id'])) {
                      $query2->execute([':objCal_id' => $res['objCal_id']]);
                      $calRes = $query2->fetch(PDO::FETCH_ASSOC);
                      if ($calRes) {
                          $calories = $calRes['calories_min'];
                      }
                  }
      
                  if ($res && isset($res['objPro_id'])) {
                      $query3->execute([':objPro_id' => $res['objPro_id']]);
                      $proRes = $query3->fetch(PDO::FETCH_ASSOC);
                      if ($proRes) {
                          $proteines = $proRes['proteines_min'];
                      }
                  }
      
                 /* if ($res && isset($res['objEau_id'])) {
                      $query4->execute([':objEau_id' => $res['objEau_id']]);
                      $eauRes = $query4->fetch(PDO::FETCH_ASSOC);
                      if ($eauRes) {
                          $eau = $eauRes['ml_min'];
                      }
                  }
      
                  $objectifs[] = [
                      'calories_min' => $calories,
                      'proteines_min' => $proteines,
                      'eau_min' => $eau,
                      'date' => $row['date']
                  ];
              }
          
              
          
          }
      
          return $objectifs;
      }
      public static function checkUpdates()
      {
          $hist = self::GetLastAction();
          $lastAliId = ListeAlimentDAO::getLastId();     
      
          $toUpdate = [
              'aliment' => true,
              'objectif' => true
          ];
      
          if ($hist) {
              $last = end($hist);
      
              if ($last['id_ali'] == $lastAliId) {
                  $toUpdate['aliment'] = false;
              }
      
            /*  if ($last['id_obj'] == $lastObjId) {
                  $toUpdate['objectif'] = false;
              }
          }
      
          return $toUpdate;
      } */



}

?>