<?php
    class InformationsPersonnelles{
        private $utilisateur_id;
        private $prenom;
        private $nom;
        private $courriel;
        private $adresse;
        private $pays;
        private $province;
        private $zip;
        private $age;
        private $sexe;
        private $poids;
        private $taille;
        private $niv_activite;
        private $telephone;

        // Constructeur
        public function __construct($utilisateur_id, $prenom, $nom, $courriel, $adresse, $pays, $province, $zip, $age, $sexe, $poids, $taille, $niv_activite, $telephone) {
            $this->utilisateur_id = $utilisateur_id;
            $this->prenom = $prenom;
            $this->nom = $nom;
            $this->courriel = $courriel;
            $this->adresse = $adresse;
            $this->pays = $pays;
            $this->province = $province;
            $this->zip = $zip;
            $this->age = $age;
            $this->sexe = $sexe;
            $this->poids = $poids;
            $this->taille = $taille;
            $this->niv_activite = $niv_activite;
            $this->telephone = $telephone;
        }

        // Getters 
        public function __get($champ){
            if(property_exists($this, $champ)) {
                return $this->$champ;
            }
            return null;
        }
        public function getUtilisateurId(){return $this->utilisateur_id;}
        public function getPrenom(){return $this->prenom;}
        public function getNom(){return $this->nom;}
        public function getCourriel(){return $this->courriel;}
        public function getAdresse(){return $this->adresse;}
        public function getPays(){return $this->pays;}
        public function getProvince(){return $this->province;}
        public function getZip(){return $this->zip;}
        public function getAge(){return $this->age;}
        public function getSexe(){return $this->sexe;}
        public function getPoids(){return $this->poids;}
        public function getTaille(){return $this->taille;}
        public function getNivActivite(){return $this->niv_activite;}
        public function getTelephone(){return $this->telephone;}



        // Setters pour les informations modifiables
        public function setUtilisateurId($utilisateur_id) {$this->utilisateur_id = $utilisateur_id;}
        public function setPrenom($prenom) {$this->prenom = $prenom;}
        public function setNom($nom) {$this->nom = $nom;}
        public function setCourriel($courriel) {$this->courriel = $courriel;}
        public function setAdresse($adresse) {$this->adresse = $adresse;}
        public function setPays($pays) {$this->pays = $pays;}
        public function setProvince($province) {$this->province = $province;}
        public function setZip($zip) {$this->zip = $zip;}
        public function setAge($age) {$this->age = $age;}
        public function setSexe($sexe) {$this->sexe = $sexe;}
        public function setPoids($poids) {$this->poids = $poids;}
        public function setTaille($taille) {$this->taille = $taille;}
        public function setNivActivite($niv_activite) {$this->niv_activite = $niv_activite;}
        public function setTelephone($telephone) {$this->telephone = $telephone;}


        //Méthode 
        //Modifier une information personnelle
        public function modifierInfo($champ, $valeur) {
            if(property_exists($this, $champ)) {
                $this->$champ = $valeur;
            }
        }

            
        public function ajouterListe($nom, $valeur) {
            if(!property_exists($this, $nom)) {
                $this->$nom = $valeur;
            }
        }

        //Afficher les informations personnelles
        public function afficherInfo() {
            return[
                "id de l'utilisateur"=> $this->utilisateur_id,
                "Prenom"=> $this->prenom,
                "Nom"=> $this->nom,
                "Courriel"=> $this->courriel,
                "Adresse"=> $this->adresse,
                "Pays"=> $this->pays,
                "Province"=> $this->province,
                "Zip"=> $this->zip,
                "Age"=> $this->age,
                "Sexe"=> $this->sexe,
                "Poids"=> $this->poids,
                "Taille"=> $this->taille,
                "Niveau d'activité"=> $this->niv_activite,
                "Telephone"=> $this->telephone,
            ];
        }
    }
?>