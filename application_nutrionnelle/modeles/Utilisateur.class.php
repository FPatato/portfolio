<?php
    class Utilisateur{
        public $id;
        public $username;
        public $password;
        public $date_inscription;

        // Constructeur
        public function __construct($id, $username, $password, $date_inscription) {
            $this->id = $id;
            $this->username = $username;
            $this->password = $password;
            $this->date_inscription = $date_inscription;
        }


        // Getters 
        public function getId() {return $this->id;}
        public function getUsername(){return $this->username;}
        public function getPassword(){return $this->password;}
        public function getDateInscription(){return $this->date_inscription;}


        // Méthodes
        public function afficherUtilisateur() {
            return [
                "id : "=> $this->id,
                "Nom d'utilisateur : "=> $this->username,
                "Date d'inscription : "=> $this->date_inscription,
            ];
        }
    }
?>
