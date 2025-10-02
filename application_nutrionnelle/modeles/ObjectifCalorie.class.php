<?php
    class ObjectifCalorie{
        private $objCal_id;
        private $calories_min;
        private $calories_consomme;
        private $date;
           
        // Constructeur
        public function __construct($objCal_id, $calories_min, $calories_consomme, $date){
            $this->objCal_id = $objCal_id;
            $this->calories_min = $calories_min;
            $this->calories_consomme = $calories_consomme;
            $this->date = $date;
        }

        // Getters 
        public function getObjCalId(){return $this->objCal_id;}
        public function getCaloriesMin(){return $this->calories_min;}
        public function getCaloriesConsomme(){return $this->calories_consomme;}  
        public function getEtat(){return $this->etat;}
        public function getDate(){return $this->date;}

        // Setters
        public function setCaloriesMin($calories_min){$this->calories_min = $calories_min;}
        public function setCaloriesConsomme($calories_consomme){$this->calories_consomme = $calories_consomme;}
        public function setDate($date){$this->date = $date;}

        // Méthodes
        public function afficherObjectif() {
            return [
                "Objectif Calories ID :"=> $this->objCal_id,
                "Calories minimum"=> $this->calories_min,
                "Calories consommés"=> $this->calories_consomme,
                "Date" => $this->date,
            ];
        }
    }
?>