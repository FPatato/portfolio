<?php
    class ListeAliment{
        private $id_ali;
        private $id_hist;
        private $aliment;
        private $date;
           

        // Constructeur
        public function __construct($id_ali, $id_hist, $aliment, $date){
            $this->id_ali = $id_ali;
            $this->id_hist = $id_hist;
            $this->aliment = $aliment;
            $this->date = $date;
        }


        // Getters 
        public function get_id_ali(){return $this->id_ali;}
        public function get_id_hist(){return $this->id_hist;}
        public function get_aliment(){return $this->aliment;}
        public function get_date(){return $this->date;}
            

        // Setters
        public function set_id_hist($id_hist){$this->id_hist = $id_hist;}
        public function set_aliment($aliment){ $this->aliment = $aliment;}

        // Méthodes
        public function afficherObjectif() {
            return [
                "Id aliment"=> $this->id_ali,
                "Id historique"=> $this->id_hist,
                "Aliment"=> $this->aliment,
                "Date"=> $this->date
            ];
        }
    }
?>