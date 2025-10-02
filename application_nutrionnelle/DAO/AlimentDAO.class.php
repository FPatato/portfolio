<?php
require_once(__DIR__ . "/../include/connexionBD.php");
require_once(__DIR__ . "/../vue/page_aliment/itemNutrition.php");
require_once(__DIR__ . "/../modeles/Aliment.class.php");
class AlimentDAO
{
    // Fonction qui ajoute un aliment dans la base de donnée
    public static function AddAliment($serializedItemInfo)
    {
        // Connexion a la base de donnée
        $pdo = connexionBD::getInstance();

        // Si la variable $_GET['item] n'est pas NULL, crée l'objet itemInfo
        if (isset($_GET['item'])) {
            $serializedItemInfo = $_GET['item'];
            $itemInfo = unserialize(urldecode($serializedItemInfo));
            if ($itemInfo) {
                // Redirection sans headers
                $URL = "../page_aliment/aliment.php";
                echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
            }
        }
        // Requête SQL qui insère les info de l'aliment de la base de donnée
        $sql = "INSERT INTO aliment (
                aliment, calories, serving_size_g, fat_total_g, fat_satured_g, 
                protein_g, sodium_mg, potassium_mg, cholesterol_mg, carbohydrates_total_g, 
                fiber_g, sugar_g) 

                VALUES (
                :aliment, :calories, :serving_size_g, :fat_total_g, :fat_satured_g, 
                :protein_g, :sodium_mg, :potassium_mg, :cholesterol_mg, :carbohydrates_total_g, 
                :fiber_g, :sugar_g)";

        // Insère les valeurs de la variable itemInfo
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':aliment' => $itemInfo->name,
            ':calories' => $itemInfo->calories,
            ':serving_size_g' => $itemInfo->serving_size_g,
            ':fat_total_g' => $itemInfo->fat_total_g,
            ':fat_satured_g' => $itemInfo->fat_saturated_g,
            ':protein_g' => $itemInfo->protein_g,
            ':sodium_mg' => $itemInfo->sodium_mg,
            ':potassium_mg' => $itemInfo->potassium_mg,
            ':cholesterol_mg' => $itemInfo->cholesterol_mg,
            ':carbohydrates_total_g' => $itemInfo->carbohydrates_total_g,
            ':fiber_g' => $itemInfo->fiber_g,
            ':sugar_g' => $itemInfo->sugar_g
        ]);

        $id_ali = $pdo->lastInsertId();

        return $id_ali;
    }

    //Retourne le nombre de calories ajoutés
    public static function returnCalAdded($id_ali)
    {
        $pdo = connexionBD::getInstance();
        $sql = "SELECT calories FROM aliment WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id_ali]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['calories'];
    }

    //Retourne le nombre de protéines ajoutés
    public static function returnProAdded($id_ali)
    {
        $pdo = connexionBD::getInstance();
        $sql = "SELECT protein_g FROM aliment WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id_ali]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['protein_g'];
    }

    //Retourne le nombre de ml d'eau ajoutés
    public static function returnWaterAdded()
    {
        $pdo = connexionBD::getInstance();
        $sql = "SELECT serving_size_g FROM aliment WHERE aliment = :aliment";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':aliment' => "water"]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['serving_size_g'];
    }

    public static function GetAliment($id)
    {
        $pdo = connexionBD::getInstance();
        $sql = "SELECT * FROM aliment WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }



}
?>