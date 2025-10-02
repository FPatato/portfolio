<?php
    class ListeObjectif{
        private $id_obj;
        private $objCal_id;
        private $objPro_id;
        private $objEau_id;
        private $date;

        // Constructeur
        public function __construct($id_obj, $objCal_id, $objPro_id, $objEau_id, $date) {
           
            $this->id_obj = $id_obj;
            $this->objCal_id = $objCal_id;
            $this->objPro_id = $objPro_id;
            $this->objEau_id = $objEau_id;
            $this->date = $date;
        }
            

        // Getters
        public function getObjId() {return $this->id_obj;}
        public function getObjectifCalorie() {return $this->objCal_id;}
        public function getObjectifProteine() {return $this->objPro_id;}
        public function getObjectifEau() {return $this->objEau_id;}
        public function getDate() {return $this->date;}


        // Setters
        public function setObjectifProteine($objectifProteine) {$this->objPro_id = $objectifProteine;}
        public function setObjectifEau($objectifEau) {$this->objEau_id = $objectifEau;}
        public function setObjectifCalorie($objCal_id) {$this->objCal_id = $objCal_id;}


        // Méthodes
        public function afficherObjectif() {
            return [
                "Liste objectif ID :" => $this->id_obj,
                "Objectif calorie"=> $this->objCal_id,
                "Objectif proteine"=> $this->objPro_id,
                "Objectif eau"=> $this->objEau_id,
                "Date"=> $this->date,
            ];
        }
    }
?>