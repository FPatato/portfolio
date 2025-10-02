<?php
require_once(__DIR__ . '/../DAO/InformationsPersonnellesDAO.class.php');
require_once(__DIR__ . '/../modeles/InformationsPersonnelles.class.php');
require_once(__DIR__ . '/../DAO/UtilisateurDAO.class.php');


class UtilisateurControleur
{
    // Permet à l'utilisateur de se connecter
    public static function connexion()
    {
        $userInfo = unserialize($_SESSION['SerUserInfo']);
        $username = $userInfo['username'];
        $pass = $userInfo['password'];
        $user = UtilisateurDAO::rechercherUser($username);

        if ($user) {
            $date = date("Y/m/d");
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['date'] = $date;
            $_SESSION['erreur_connexion'] = UtilisateurDAO::connexion($username, $pass, $user);
        } else {
            $_SESSION['erreur_connexion'] = 1;
            error_log("Échec de connexion : utilisateur $username introuvable");
        }
        header('Location: ../vue/Page_connexion/Connexion.php');
        exit();
    }

    // Changer le nom d'utilisateur
    public static function changerNomUtilisateur($username, $newUsername)
    {
        $utilisateurDAO = new UtilisateurDAO();

        $resultat = $utilisateurDAO->changerUsername($username, $newUsername);
        if ($resultat === true) {
            $_SESSION['username'] = $newUsername;
            return "Nom d'utilisateur changé avec succès.";
        } else {
            return $resultat;
        }
    }

    // Permet à l'utilisateur de changer de mot de passe
    public static function changerPassword()
    {
        $mdpInfo = unserialize($_SESSION['SerMdpInfo']);
        $newUsername = htmlspecialchars(trim($mdpInfo['username']));
        $password1 = trim($mdpInfo['password1']);
        $password2 = trim($mdpInfo['password2']);
        $oldPassword = $mdpInfo['oldpassword'];
        $currentUserDetails = UtilisateurDAO::rechercherUser($_SESSION['username']);

        if (password_verify($oldPassword, $currentUserDetails['password'])) {

            // Changer mot de passe si les deux champs correspondent
            if (!empty($password1) && $password1 === $password2) {
                UtilisateurDAO::changerMotDePasse($_SESSION['username'], $password1);
                $_SESSION['message'] = "Mot de passe modifié avec succès.<br>";
            } elseif (!empty($password1)) {
                $_SESSION['message'] = "Les deux mots de passe ne correspondent pas.<br>";
            }

            // Changer nom d'utilisateur si différent
            if ($newUsername !== $_SESSION['username']) {
                $result = UtilisateurDAO::changerUsername($_SESSION['username'], $newUsername);
                if ($result === true) {
                    $_SESSION['username'] = $newUsername;
                    setcookie('username', $newUsername, time() + (10 * 365 * 24 * 60 * 60), "/");
                } else {
                    $_SESSION['messageUser'] = $result;
                }
            }
        } else {
            $_SESSION['message'] = "Ancien mot de passe invalide.";
        }

        header("Location: ../vue/page_profil/detail_compte.php");
    }
    // Vérifie le nom d'utilisateur et retourne true s'il est disponible et false s'il est déja utilisé.
    public static function verifierUsername($username)
    {
        return UtilisateurDAO::verifierUsername($username);
    }
}
