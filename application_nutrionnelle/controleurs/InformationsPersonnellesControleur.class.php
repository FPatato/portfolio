<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../DAO/InformationsPersonnellesDAO.class.php');
require_once(__DIR__ . '/../modeles/InformationsPersonnelles.class.php');
require_once(__DIR__ . '/../DAO/UtilisateurDAO.class.php');

class InformationsPersonnellesControleur
{
    // Ajoute les informations personnelles d'un utilisateur dans la base de donnée
    public static function ajoutUtilisateur()
    {
        $infoPerso = unserialize($_SESSION['SerInfoPerso']);
        unset($_SESSION['SerInfoPerso']);
        $email = $infoPerso['email'];
        $username = $infoPerso['username'];
        $password = $infoPerso['password'];
        $utilisateurDAO = new UtilisateurDAO();
        $infoDAO = new InformationsPersonnellesDAO();

        if ($infoDAO->chercherParEmail($email)) {
            $_SESSION['erreur_inscription'] = 2;
            header('Location: ../vue/page_creations/inscription.php');
            exit();
        } else if ($utilisateurDAO->rechercherUser($username)) {
            $_SESSION['erreur_inscription'] = 1;
            header('Location: ../vue/page_creations/inscription.php');
            exit();
        }
        $_SESSION['erreur_inscription'] = 3;
        $date_inscription = date('Y-m-d H:i:s');
        $utilisateur = new Utilisateur(null, $username, $password, $date_inscription);
        $utilisateurDAO->ajouterUser($utilisateur);

        $id_user = UtilisateurDAO::getIdByUsername($username);
        if ($id_user) {
            $infoPeso = [
                'utilisateur_id' => $id_user,
                'prenom' => $infoPerso['prenom'],
                'nom' => $infoPerso['nom'],
                'courriel' => $email,
                'adresse' => $infoPerso['adresse'],
                'pays' => $infoPerso['pays'],
                'province' => $infoPerso['province'],
                'zip' => $infoPerso['zip'],
                'age' => $infoPerso['age'],
                'sexe' => $infoPerso['sexe'],
                'poids' => $infoPerso['poids'],
                'taille' => $infoPerso['taille'],
                'niv_activite' => $infoPerso['activite'],
                'telephone' => $infoPerso['telephone'],
            ];
            $infoDAO = InformationsPersonnellesDAO::ajouterInformationsPersonnelles($infoPeso);

            if ($infoDAO) {
                // Ajout réussi
            }

            header('Location: ../vue/page_creations/inscription.php');
        }
    }

    // Récupérer les informations personnelles de l'utilisateur
    public static function getInformationsPersonnelles()
    {
        $utilisateur_id = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);
        $informations = InformationsPersonnellesDAO::chercher($utilisateur_id);
        return $informations;
    }

    // Calculer l'IMC de l'utilisateur selon son poids et sa taille
    public static function calculIMC()
    {
        $currentUserInfo = InformationsPersonnellesControleur::getInformationsPersonnelles();
        $imc = InformationsPersonnellesDAO::calculIMC($currentUserInfo['poids'], $currentUserInfo['taille']);
        return round($imc, 2);
    }

    // Modifie les informations personnelles de l'utilisateur dans la base de donnée
    public static function modifier()
    {
        $infoPerso = unserialize($_SESSION['SerInfoPersoModif']);
        $utilisateur_id = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);
        $info = [
            'prenom' => $infoPerso['prenom'],
            'nom' => $infoPerso['nom'],
            'courriel' => $infoPerso['courriel'],
            'adresse' => $infoPerso['adresse'],
            'pays' => $infoPerso['pays'],
            'province' => $infoPerso['province'],
            'zip' => $infoPerso['zip'],
            'age' => $infoPerso['age'],
            'sexe' => $infoPerso['sexe'],
            'poids' => $infoPerso['poids'],
            'taille' => $infoPerso['taille'],
            'niv_activite' => $infoPerso['niv_activite'],
            'telephone' => $infoPerso['telephone'],
        ];
        $success = InformationsPersonnellesDAO::modifier($utilisateur_id, $info);
        if ($success) {
            $_SESSION['messageInfoPerso'] = "Information modifiée avec succès !";
            header("Location: ../vue/page_profil/info_perso.php");
            exit;

        } else {
            $_SESSION['messageInfoPerso'] = "Échec de la modification.";
        }
    }

    // Vérifie le courriel de l'utilisateur lors de l'inscription et de la connexion
    public static function verifierEmail($email)
    {
        return InformationsPersonnellesDAO::chercherParEmail($email);
    }
     public static function verifyEmail($email)
    {
        return InformationsPersonnellesDAO::verifyEmail($email);
    }
    public static function verifyTelephone($telephone)
    {
        return InformationsPersonnellesDAO::verifyTelephone($telephone);
    }
}
?>