<?php
require_once(__DIR__ . '/../DAO/ListeObjectifDAO.class.php');
require_once(__DIR__ . '/../modeles/ListeObjectifs.class.php');
require_once(__DIR__ . '/ObjectifCalorieControleur.class.php');
require_once(__DIR__ . '/ObjectifProteineControleur.class.php');
require_once(__DIR__ . '/ObjectifEauControler.class.php');
class ListeObjectifControleur
{
  // Crée une nouvelle ListeObjectif pour l'utilisateur s'il n'en a pas une aujourd'hui
  public static function creerNouvelleListeObjectif()
  {
    $utilisateur_id = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);
    ListeObjectifDAO::creerNouvelleListeObjectif($utilisateur_id);
    header("Location: ../vue/page_accueil/accueil.php");
  }
}
?>