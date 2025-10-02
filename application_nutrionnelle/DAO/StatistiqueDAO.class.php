<?php

class StatistiqueDAO {

    /// <summary>
    /// DAO permettant d'avoir des donne statistique sur le current user connecter
    /// </summary>





    private $pdo;
        public function __construct() {
            $this->pdo = connexionBD::getInstance();
        }

        //Fonction permettant d'avoir les donne renter aujourdhui par le user
        public static function GetData(bool $CurDayOrAllTime)
        {
            $conn =  connexionBD::getInstance();
           
            $id =  $_SESSION['user_id'];
            $date = date("Y/m/d");

            $sql1 = "SELECT id_ali FROM historique WHERE utilisateur_id = :utilisateur_id";
            $stmt1 = $conn->prepare($sql1);
            $stmt1 ->execute([':utilisateur_id' => $id]);
            $id_aliment = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            if($CurDayOrAllTime == true)
        {

            
            $sql = "SELECT id_aliment FROM listealiment WHERE date = :date and id_ali = :id_ali";
            $stmt = $conn->prepare($sql);
            $ids = [];
            foreach($id_aliment as $row)
            {
                $stmt->execute([':date' => $date , ':id_ali' => $row['id_ali']]);
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($res as $row)
                {
                    $ids[] = $row;
                }

            }
        }

        if($CurDayOrAllTime == FALSE)
        {
             
            $sql = "SELECT id_aliment FROM listealiment WHERE id_ali = :id_ali";
            $stmt = $conn->prepare($sql);
            $ids = [];
            foreach($id_aliment as $row)
            {
                $stmt->execute([':id_ali' => $row['id_ali']]);
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($res as $row)
                {
                    $ids[] = $row;
                }

            }
        }
            
            return $ids;
        }

        //Fonction permettant d'avoir les calorie rentrer pour aujoudhui ou alltime
        public static function GetCalories(bool $CurDayOrAllTime)
        {
            $conn =  connexionBD::getInstance();
            $IdCurDay = StatistiqueDAO::GetData(true);
            $IdAllTime = StatistiqueDAO::GetData(false);

            $sql = "SELECT calories FROM aliment WHERE id = :id";
            $total = 0;
            $stmt = $conn->prepare($sql);
            if($CurDayOrAllTime == true)
            {
                foreach($IdCurDay as $row)
                {
                    $stmt->execute([':id'=> $row['id_aliment']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total += $result['calories'];
                }
            }
            if($CurDayOrAllTime == false)
            {
                foreach($IdAllTime as $row)
                {
                    $stmt->execute([':id'=> $row['id_aliment']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total += $result['calories'];
                }
            }
       
            return $total;

        }

        //Fonction permettant d'avoir les proteine rentrer pour aujoudhui ou alltime
        public static function GetProtein(bool $CurDayOrAllTime)
        {
            $conn =  connexionBD::getInstance();
            $IdCurDay = StatistiqueDAO::GetData(true);
            $IdAllTime = StatistiqueDAO::GetData(false);

            $sql = "SELECT protein_g FROM aliment WHERE id = :id";
            $total = 0;
            $stmt = $conn->prepare($sql);
            if($CurDayOrAllTime == true)
            {
                 foreach($IdCurDay as $row)
                {
                    $stmt->execute([':id'=> $row['id_aliment']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total += $result['protein_g'];
                }
            }
            if($CurDayOrAllTime == false)
            {
                foreach($IdAllTime as $row)
                {
                    $stmt->execute([':id'=> $row['id_aliment']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total += $result['protein_g'];
                }
            }
            return $total;
        }

        //Fonction permettant d'avoir les Carbs aka Glucide rentrer pour aujoudhui ou alltime
        public static function GetCarbs(bool $CurDayOrAllTime)
        {
            $conn =  connexionBD::getInstance();
            $IdCurDay = StatistiqueDAO::GetData(true);
            $IdAllTime = StatistiqueDAO::GetData(false);

            $sql = "SELECT carbohydrates_total_g FROM aliment WHERE id = :id";
            $total = 0;
            $stmt = $conn->prepare($sql);
            if($CurDayOrAllTime == true)
            {
                foreach($IdCurDay as $row)
                {
                    $stmt->execute([':id'=> $row['id_aliment']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total += $result['carbohydrates_total_g'];
                }
            }
            if($CurDayOrAllTime == false)
            {
                foreach($IdAllTime as $row)
                {
                    $stmt->execute([':id'=> $row['id_aliment']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total += $result['carbohydrates_total_g'];
                }
            }
            return $total;
        

        }

        //Fonction permettant d'avoir le fat aka Gras rentrer pour aujoudhui ou alltime
        public static function GetFat(bool $CurDayOrAllTime)
        {
            $conn =  connexionBD::getInstance();
            $IdCurDay = StatistiqueDAO::GetData(true);
            $IdAllTime = StatistiqueDAO::GetData(false);
            
            $sql = "SELECT fat_total_g FROM aliment WHERE id = :id";
            $total = 0;
            $stmt = $conn->prepare($sql);
            if($CurDayOrAllTime == true)
            {
                foreach($IdCurDay as $row)
                {
                    $stmt->execute([':id'=> $row['id_aliment']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total += $result['fat_total_g'];
                }
            }
            if($CurDayOrAllTime == false)
            {
                foreach($IdAllTime as $row)
                {
                    $stmt->execute([':id'=> $row['id_aliment']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total += $result['fat_total_g'];
                }
            }
       
            
            return $total;
        }


        //Fonction qui sauvegarde les donne statistique de l'utilisateur
        public static function SaveStats()
        {
            $conn =  connexionBD::getInstance();
            $utilisateur_id = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);
            $currentUserInfo = InformationsPersonnellesDAO::getInformationsPersonnelles($_SESSION['user_id']);
            
            $id_hist = HistoriqueDAO::getIdhistbyUsernameid($utilisateur_id);
            $date = date("Y/m/d");
            $poids = $currentUserInfo->getPoids();
            $imc = InformationsPersonnellesDAO::calculIMC($poids, $currentUserInfo->getTaille());

            $totalCalories = self::GetCalories(false);
            $totalProteins = self::GetProtein(false);
            $totalCarbs = self::GetCarbs(false);
            $totalFats = self::GetFat(false);

            $SqlCheckDay = "SELECT id_stat from statistique where id_hist = :id_hist and date = :date";
            $stmt = $conn->prepare($SqlCheckDay);
            $stmt->execute([':id_hist'=>$id_hist,':date' => $date]);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $size = count($res);

            if($size > 0)
            {
                $sql = "UPDATE statistique SET poids = :poids, imc = :imc,calories_total = :calories,proteines_total = :proteines,carbs_total = :carbs,
            fat_total = :fat,
            date = :date
             WHERE id_hist = :id_hist";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':poids' => $poids,
                ':imc' => $imc,
                ':calories' => $totalCalories,
                ':proteines' => $totalProteins,
                ':carbs' => $totalCarbs,
                ':fat' => $totalFats,
                ':date' => $date,
                ':id_hist' => $id_hist
            ]);

            }
            else

        {

            $sql = "INSERT INTO statistique (
                id_hist, poids, imc, calories_total, proteines_total, carbs_total, fat_total, date
            ) VALUES (
                :id_hist, :poids, :imc, :calories, :proteines, :carbs, :fat, :date
            )";   

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id_hist' => $id_hist,
                ':poids' => $poids,
                ':imc' => $imc,
                ':calories' => $totalCalories,
                ':proteines' => $totalProteins,
                ':carbs' => $totalCarbs,
                ':fat' => $totalFats,
                ':date' => $date
            ]);

        }
            
        }

        public static function GetStatsWithDate($date)
        {
            $conn = connexionBD::getInstance();
            $utilisateur_id = UtilisateurDAO::getIdByUsername($_COOKIE['username']);
            $id_hist = HistoriqueDAO::getIdhistbyUsernameid($utilisateur_id);

            $query = "SELECT calories_total, proteines_total FROM statistique WHERE date = :date AND id_hist = :id_hist";
            $stmt = $conn->prepare($query);
            $stmt->execute([':date' => $date, ':id_hist' => $id_hist]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row || $row['calories_total'] == NULL or $row['calories_total'] == 0) 
            {
                $row = ['calories_total' => 0, 'proteines_total' => 0];
            }
            return $row; 
        }


        /// <summary>
        /// Les 3 fonction qui suivent permette d'avoir les donne necessaire pour creer leur graphique 
        /// </summary>
        

        //Graphique en tarte
        public static function PieGraph()
        {
           
            $protein = StatistiqueDAO::GetProtein(true);
            $carbs = StatistiqueDAO::GetCarbs(true);
            $fat = StatistiqueDAO::GetFat(true);
            
            $total = $fat+$carbs+$protein;
            if ($total == 0) {
                $percentages = [
                    'protein' => 0,
                    'carbs' => 0,
                    'fat' => 0
                ];
            } else {
            $percentages = [
                'protein' => round(($protein / $total) * 100, 2),
                'carbs' => round(($carbs / $total) * 100, 2),
                'fat' => round(($fat / $total) * 100, 2)
            ];
        }
            return $percentages;
            
        }

        //Graphique a bar
        public static function BarGraph()
        {
            $conn = connexionBD::getInstance();

            $jours = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
            $resultats = [];

            $id =  $_SESSION['user_id'];
            $sql1 = "SELECT id_ali FROM historique WHERE utilisateur_id = :utilisateur_id";
            $stmt1 = $conn->prepare($sql1);
            $stmt1 ->execute([':utilisateur_id' => $id]);
            $id_aliment = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            $lenght = count($id_aliment);
     
            foreach ($jours as $jour)
             {
                $totalCalories = 0;
                $idali_tdy = [];
               
                for($i = 0 ; $i < $lenght; $i++)
                {
                    $sql = "SELECT id_aliment FROM listealiment WHERE DAYNAME(date) = :dayname and id_ali = :id_ali";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':dayname' => $jour , ':id_ali' => $id_aliment[$i]['id_ali']]);
                    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach($res as $row)
                    {
                        $idali_tdy[] = $row['id_aliment'];
                    }
                }

            
                $sql2 = "SELECT calories FROM aliment WHERE id = :id";
                $stmt2 = $conn->prepare($sql2);

                foreach ($idali_tdy as $row) 
                {
                    $stmt2->execute([':id' => $row]);
                    $res = $stmt2->fetch(PDO::FETCH_ASSOC);
                    if ($res) 
                    {
                        $totalCalories += $res['calories'];
                    }
                }

                
                $resultats[$jour] = $totalCalories;
            }

            return $resultats; 
        }

        //Graphique Lineaire
        public static function LineGraph()
        {
            $conn = connexionBD::getInstance();
            $mois = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            $resultats = [];

            $id =  $_SESSION['user_id'];
            $sql1 = "SELECT id_ali FROM historique WHERE utilisateur_id = :utilisateur_id";
            $stmt1 = $conn->prepare($sql1);
            $stmt1 ->execute([':utilisateur_id' => $id]);
            $id_aliment = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            $lenght = count($id_aliment);
     
            foreach ($mois as $mois)
             {
                $totalCalories = 0;
                $idali_month = [];
               
                for($i = 0 ; $i < $lenght; $i++)
                {
                    $sql = "SELECT id_aliment FROM listealiment WHERE MONTHNAME(date) = :mois and id_ali = :id_ali";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':mois' => $mois, ':id_ali' => $id_aliment[$i]['id_ali']]);
                    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach($res as $row)
                    {
                        $idali_month[] = $row['id_aliment'];
                    }
                }

            
                $sql2 = "SELECT calories FROM aliment WHERE id = :id";
                $stmt2 = $conn->prepare($sql2);

                foreach ($idali_month as $row) 
                {
                    $stmt2->execute([':id' => $row]);
                    $res = $stmt2->fetch(PDO::FETCH_ASSOC);
                    if ($res) 
                    {
                        $totalCalories += $res['calories'];
                    }
                }

                
                $resultats[$mois] = $totalCalories;
            }

            return $resultats; 
        
        }

}

?>