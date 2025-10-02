<?php
    class Statistique {
        private $id_stat;
        private $id_hist;
        private $poids;
        private $imc;
        private $calories_total;
        private $proteines_total;
        private $aliment_total;
        private $date;

        public function __construct($id_stat, $id_hist, $poids, $imc, $calories_total, $proteines_total, $aliment_total, $date)
        {
            $this->id_stat = $id_stat;
            $this->id_hist = $id_hist;
            $this->poids = $poids;
            $this->imc = $imc;
            $this->calories_total = $calories_total;
            $this->proteines_total = $proteines_total;
            $this->aliment_total = $aliment_total;
            $this->date = $date;
        }

        // Getters
        public function getIdStat(){return $this->id_stat;}
        public function getIdHist(){return $this->id_hist;}
        public function getPoids(){return $this->poids;}
        public function getImc(){return $this->imc;}
        public function getCaloriesTotal(){return $this->calories_total;}
        public function getProteinesTotal(){return $this->proteines_total;}
        public function getAlimentTotal(){return $this->aliment_total;}
        public function getDate(){return $this->date;}

        // Setters
        public function setPoids($poids){$this->poids = $poids;}
        public function setCaloriesTotal($calories_total){$this->calories_total = $calories_total;}
        public function setProteinesTotal($proteines_total){$this->proteines_total = $proteines_total;}
        public function setAlimentTotal($aliment_total){$this->aliment_total = $aliment_total;}
        public function setDate($date){$this->date = $date;}

        

        // Méthodes
        public function afficherStatistique()
        {
            return [
                "Id date" => $this->id_stat,
                "Id historique" => $this->id_hist,
                "Poids" => $this->poids,
                "IMC" => $this->imc,
                "Calories total" => $this->calories_total,
                "Proteines total" => $this->proteines_total,
                "Aliment total" => $this->aliment_total,
                "Date" => $this->date,
            ];
        }
    }
?>