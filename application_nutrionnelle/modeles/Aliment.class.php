<?php
    class Aliment {
        private $aliment;
        private $calories;
        private $serving_size_g;
        private $fat_satured_g;
        private $fat_total_g;
        private $protein_g;
        private $sodium_mg;
        private $potassium_mg;
        private $cholesterol_mg;
        private $carbohydrates_total_g;
        private $fiber_g;
        private $sugar_g;


        // Constructeur
        public function __construct($aliment, $calories, $serving_size_g, $fat_satured_g, $fat_total_g, $protein_g, $sodium_mg, $potassium_mg, $cholesterol_mg, $carbohydrates_total_g, $fiber_g, $sugar_g)
        {
            $this->aliment = $aliment;
            $this->calories = $calories;
            $this->serving_size_g = $serving_size_g;
            $this->fat_satured_g = $fat_satured_g;
            $this->fat_total_g = $fat_total_g;
            $this->protein_g = $protein_g;
            $this->sodium_mg = $sodium_mg;
            $this->potassium_mg = $potassium_mg;
            $this->cholesterol_mg = $cholesterol_mg;
            $this->carbohydrates_total_g = $carbohydrates_total_g;
            $this->fiber_g = $fiber_g;
            $this->sugar_g = $sugar_g;
        }


        // Getters
        public function getAliment(){return $this->aliment;}
        public function getCalories(){return $this->calories;}
        public function getServingSizeG(){return $this->serving_size_g;}
        public function getFatSaturedG(){return $this->fat_satured_g;}
        public function getFatTotalG(){return $this->fat_total_g;}
        public function getProteinG(){return $this->protein_g;}
        public function getSodiumMg(){return $this->sodium_mg;}
        public function getPotassiumMg(){return $this->potassium_mg;}
        public function getCholesterolMg(){return $this->cholesterol_mg;}
        public function getCarbohydratesTotalG(){return $this->carbohydrates_total_g;}
        public function getFiberG(){return $this->fiber_g;}
        public function getSugarG(){return $this->sugar_g;}


        // Setters
        public function setAliment($aliment){$this->aliment = $aliment;}
        public function setCalories($calories){$this->calories = $calories;}
        public function setServingSizeG($serving_size_g){$this->serving_size_g = $serving_size_g;}
        public function setFatSaturedG($fat_satured_g){$this->fat_satured_g = $fat_satured_g;}
        public function setFatTotalG($fat_total_g){$this->fat_total_g = $fat_total_g;}
        public function setProteinG($protein_g){$this->protein_g = $protein_g;}
        public function setSodiumMg($sodium_mg){$this->sodium_mg = $sodium_mg;}
        public function setPotassiumMg($potassium_mg){$this->potassium_mg = $potassium_mg;}
        public function setCholesterolMg($cholesterol_mg){$this->cholesterol_mg = $cholesterol_mg;}
        public function setCarbohydratesTotalG($carbohydrates_total_g){$this->carbohydrates_total_g = $carbohydrates_total_g;}
        public function setFiberG($fiber_g){$this->fiber_g = $fiber_g;}
        public function setSugarG($sugar_g){$this->sugar_g = $sugar_g;}


        // Méthodes
        public function afficherAliment()
        {
            return [
                "Aliment" => $this->aliment,
                "Calories" => $this->calories,
                "Taille (g)" => $this->serving_size_g,
                "Gras saturées (g)" => $this->fat_satured_g,
                "Gras total (g)" => $this->fat_total_g,
                "Proteines (g)" => $this->protein_g,
                "Sodium (mg)" => $this->sodium_mg,
                "Potassium (mg)" => $this->potassium_mg,
                "Gholestérol (mg)" => $this->cholesterol_mg,
                "Total de glucides (g)" => $this->carbohydrates_total_g,
                "Fibres (g)" => $this->fiber_g,
                "Sucres (g)" => $this->sugar_g,
            ];
        }
    }
?>