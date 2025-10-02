<?php
    class ObjectifEau {
        private $objEau_id;
        private $ml_min;
        private $eau_consomme;
        private $date;
           
        // Constructeur
        public function __construct($objEau_id, $ml_min, $eau_consomme, $date) {
            $this->objEau_id = $objEau_id;
            $this->ml_min = $ml_min;
            $this->eau_consomme = $eau_consomme;
            $this->date = $date;
        }


        // Getters 
        public function getObjEauId(){return $this->objEau_id;}
        public function getMlMin(){return $this->ml_min;}
        public function getEauConsomme(){return $this->eau_consomme;}
        public function getDate(){return $this->date;}
            

        //Setters
        public function setMlMin($ml_min) {$this->ml_min = $ml_min;}
        public function setEau_Consomme($eau_consomme) {$this->eau_consomme = $eau_consomme;}
        public function setDate($date){$this->date = $date;}


        // Méthodes
        public function afficherObjectif() {
            return [
                "Objectif Eau ID : "=> $this->objEau_id,
                "Ml minimum : "=> $this->ml_min,
                "Eau consommé : "=> $this->eau_consomme,
                "Date : "=> $this->date,
            ];
        }

    }
?>
