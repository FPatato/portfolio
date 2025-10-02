<?php
require_once(__DIR__. "/../include/connexionBD.php");
require_once(__DIR__. "/../vue/page_aliment/itemNutrition.php");
class ListeAlimentDAO 
{
        public static function AddListAliment($id_aliment)
        {
            $pdo =  connexionBD::getInstance();
            //Date d'aujourd'hui
            $date = date("Y-m-d"); 
            $utilisateur_id = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);

            $sql = "SELECT id_ali,date FROM historique WHERE utilisateur_id = :utilisateur_id ORDER BY id_ali DESC LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':utilisateur_id' => $utilisateur_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_ali = $result['id_ali'];
            $lastdate = $result['date'];
            if($id_ali == null or $id_ali == 0)
            {
              $id_ali = rand(1, getrandmax());
            }
            if($date != $lastdate)
            {
              $id_ali = rand(1, getrandmax());
            }
            
            // Insérer dans la table listealiment
            $sql = "INSERT INTO listealiment (id_ali , id_aliment, date)
            VALUES (:id_ali , :id_aliment, :date)";
              $stmt = $pdo->prepare($sql);
              $stmt->execute([
                  ':id_ali' => $id_ali,
                 ':id_aliment' => $id_aliment,
                 ':date' => $date
             ]);
          
            # $id_ali = $pdo->lastInsertId();
          
             return $id_ali;
        }
        //Supprime la liste d'aliment
       public static function DeleteListAliment($id_aliment)
      {
        $pdo = connexionBD::getInstance();

        $iduser = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);
        $id_ali = HistoriqueDAO::chercherListeAliment($iduser);

        $sql = "DELETE FROM listealiment WHERE id_aliment = :id_aliment AND id_ali = :id_ali";
        $sql2 = "DELETE FROM aliment WHERE id = :id_aliment";

        $stmt = $pdo->prepare($sql);
        $stmt2 = $pdo->prepare($sql2);

        $success = $stmt->execute([":id_aliment" => $id_aliment, ":id_ali" => $id_ali]);

          if ($success) {
              $stmt2->execute([":id_aliment" => $id_aliment]);
              echo "1"; 
          } else {
              echo "0"; 
          }
      }


        //Retourne le dernier id dans la base de donnée
        public static function getLastId() 
        {
          $pdo = ConnexionBD::getInstance();
          $query = $pdo->query("SELECT id_ali FROM Listealiment ORDER BY id_ali DESC LIMIT 1");
          $result = $query->fetchColumn();
          return $result;
      }        
          public static function GetListAliment($date,$name)
          {
            $iduser = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);
            $id_ali = HistoriqueDAO::chercherListeAliment($iduser);

            $pdo =  connexionBD::getInstance();
            $query = "SELECT la.id_aliment 
            FROM listealiment la JOIN aliment a ON la.id_aliment = a.id WHERE la.id_ali = :id_ali AND la.date = :date and a.aliment = :name";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id_ali' => $id_ali,':date' => $date, ':name' => $name]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

          
            return $result;
          }
}


 ?>