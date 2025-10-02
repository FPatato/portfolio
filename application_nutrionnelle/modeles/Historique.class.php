<?php
    class Historique{
        private $id_hist;
        private $utilisateur_id;
        private $date;
        private $id_ali;
        private $id_obj;
           

        // Constructeur
        public function __construct($id_hist, $utilisateur_id, $date, $id_ali, $id_obj) {
            $this->id_hist = $id_hist;
            $this->utilisateur_id = $utilisateur_id;
            $this->date = $date;
            $this->id_ali = $id_ali;
            $this->id_obj = $id_obj;
        }


        // Getters 
        public function getIdHist() {return $this->id_hist;}
        public function getUserId() {return $this->utilisateur_id;}
        public function getDate() {return $this->date;}
        public function getListeAliment() {return $this->id_ali;}
        public function getListeObjectif() {return $this->id_obj;}


        // Setters
        public function setUserId($utilisateur_id) {$this->utilisateur_id = $utilisateur_id;}
        public function setDate($date) {$this->date = $date;}
        public function setListeAliment($id_ali) {$this->id_ali = $id_ali;}
        public function setListeObjectif($id_obj) {$this->id_obj = $id_obj;}


        //Méthode
        public function afficherObjectif() {
            return [
                "Id historique"=> $this->id_hist,
                "Id utilisateur"=> $this->utilisateur_id,
                "Date"=> $this->date,
                "Id ListeAliment"=> $this->id_ali,
                "Id ListeObjectif"=> $this->id_obj,
            ];
        }
    }
?>