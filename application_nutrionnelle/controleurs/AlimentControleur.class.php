<?php
ob_start();
require_once __DIR__ . '/../DAO/AlimentDAO.class.php';
require_once __DIR__ . '/../DAO/ListeAlimentDAO.class.php';
require_once __DIR__ . '/../DAO/HistoriqueDAO.class.php';
require_once __DIR__ . '/../DAO/UtilisateurDAO.class.php';
require_once __DIR__ . '/../DAO/ListeObjectifDAO.class.php';
require_once __DIR__ . '/../DAO/ObjectifCalorieDAO.class.php';
require_once __DIR__ . '/../DAO/ObjectifProteineDAO.class.php';
require_once __DIR__ . '/../DAO/StatistiqueDAO.class.php';
//erreur dans aliment.php si on uncomment la ligne suivante
//require_once __DIR__ . '/../DAO/ObjectifEauDAO.class.php';
class AlimentControleur
{
    // Fonction qui ajoute un aliment pour l'utilisateur et met à jour ses calories et protéines consommés dans ses objectifs
    public static function ajouterAliment()
    {
        $utilisateur_id = UtilisateurDAO::getIdByUsername(username: $_COOKIE['username']);

        if (isset($_GET['item'])) {

            $serializedItemInfo = $_GET['item'];
            //Ajoute l'aliment et retourne le id de l'aliment
            $id = AlimentDAO::AddAliment($serializedItemInfo);

            //Retourne le nombre de calories et protéines consommés en fonction des aliments ajoutés
            $calories = AlimentDAO::returnCalAdded($id);
            $proteine = AlimentDAO::returnProAdded($id);

            //Met à jour les caories et protéines consommés de l'utilisateur dans ses objectif.
            ObjectifCalorieDAO::updateCaloriesConsomme($utilisateur_id, $calories);
            ObjectifProteineDAO::updateProteineConsomme($utilisateur_id, $proteine);

            $id_obj = ListeObjectifDAO::getLastId();
            $id_ali = ListeAlimentDAO::AddListAliment($id);
            HistoriqueDAO::AddHistorique($utilisateur_id, $id_ali, $id_obj);
            StatistiqueDAO::SaveStats();

        }
    }
    // Fonction qui retourne les informations d'un aliment selon son ID
    public static function GetAliment($id)
    {
        return AlimentDAO::GetAliment($id);
    }

}

ob_end_flush();
?>