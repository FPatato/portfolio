<?php
    class ObjectifProteine {
        private $objPro_id;
        private $proteines_min;
        private $proteines_consomme;
        private $date;
           
        // Constructeur
        public function __construct($objPro_id, $proteines_min, $proteines_consomme, $date) {
            $this->objPro_id = $objPro_id;
            $this->proteines_min = $proteines_min;
            $this->proteines_consomme = $proteines_consomme;
            $this->date = $date;
        }

        // Getters 
        public function getObjProId(){return $this->objPro_id;}
        public function getProteinesMin(){return $this->proteines_min;}
        public function getProteinesConsomme(){return $this->proteines_consomme;}
        public function getDate(){return $this->date;}
        
        // Setters
        public function setProteinesMin($proteines_min){$this->proteines_min = $proteines_min;}
        public function setProteinesConsomme($proteines_consomme){$this->proteines_consomme = $proteines_consomme;}
        public function setDate($date){$this->date = $date;}

        // Méthodes
        public function afficherObjectif() {
            return [
                "Objectif Protéine ID : "=> $this->objPro_id,
                "Protéines minimum : "=> $this->proteines_min,
                "Protéines consommés : "=> $this->proteines_consomme,
                "Date : "=> $this->date,
            ];
        }
    }
?>