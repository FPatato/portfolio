<?php
require_once('../controleurs/AlimentControleur.class.php');
require_once('../controleurs/StatistiqueControleur.class.php');
require_once('../controleurs/ListeAlimentControleur.class.php');
require_once('../controleurs/ListeObjectifControleur.class.php');
require_once('../controleurs/ObjectifEauControler.class.php');
require_once('../controleurs/ObjectifCalorieControleur.class.php');
require_once('../controleurs/ObjectifProteineControleur.class.php');
require_once('../controleurs/InformationsPersonnellesControleur.class.php');
require_once('../controleurs/UtilisateurControleur.class.php');

$action = $_GET['action'] ?? 'lister';

    /// <summary>
    /// Controleur frontale permettant la redirection du formulaire dependament de l'action
    /// </summary>


switch ($action) {
    #Aliment
    case 'ajouterAliment':
        AlimentControleur::ajouterAliment();
        break;
    case 'ajouterListAliment':
        ListeAlimentControleur::AddListAliment($_GET['id_aliment']);
        break;
    case 'ajoutUtilisateur':
        //Vise a serialize les info avant de les traite dans le controlleur aproprie
        $SerializedItemInfo = (serialize([
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'prenom' => $_POST['prenom'],
            'nom' => $_POST['nom'],
            'email' => $_POST['email'],
            'adresse' => $_POST['adresse'],
            'pays' => $_POST['pays'],
            'province' => $_POST['province'],
            'zip' => $_POST['zip'],
            'age' => $_POST['age'],
            'sexe' => $_POST['sexe'],
            'poids' => $_POST['poids'],
            'taille' => $_POST['taille'],
            'activite' => $_POST['activite'],
            'telephone' => $_POST['telephone']
        ]));  
        $_SESSION['SerInfoPerso'] = $SerializedItemInfo;
        InformationsPersonnellesControleur::ajoutUtilisateur();
        break;

    case 'Connexion':
        $SerializedItemInfo = (serialize(['username' => $_POST['username'], 'password' => $_POST['password']]));
        $_SESSION['SerUserInfo'] = $SerializedItemInfo;
        UtilisateurControleur::connexion();
        break;
    case 'ModifierMotDePasse':
        $SerializedItemInfo = (serialize(['username' => $_POST['username'], 'password1' => $_POST['password1'], 'password2' => $_POST['password2'], 'oldpassword' => $_POST['oldpassword']]));
        $_SESSION['SerMdpInfo'] = $SerializedItemInfo;
        UtilisateurControleur::changerPassword();
        break;
    case 'ModifierInfoPerso':
        $SerializedItemInfo = (serialize(
 [
            'prenom' => $_POST['prenom'],
            'nom' => $_POST['nom'],
            'courriel' => $_POST['courriel'],
            'adresse' => $_POST['adresse'],
            'pays' => $_POST['pays'],
            'province' => $_POST['province'],
            'zip' => $_POST['zip'],
            'age' => $_POST['age'],
            'sexe' => $_POST['sexe'],
            'poids' => $_POST['poids'],
            'taille' => $_POST['taille'],
            'niv_activite' => $_POST['niv_activite'],
            'telephone' => $_POST['telephone']
        ]));
        $_SESSION['SerInfoPersoModif'] = $SerializedItemInfo;
        InformationsPersonnellesControleur::modifier();
        break;
    case 'ModifierUsername':
        echo UtilisateurControleur::changerNomUtilisateur($_POST['Ousername'], $_POST['Nusername']);
        break;
    case 'CheckUsername':
        echo  json_encode (UtilisateurControleur::verifierUsername($_POST['Nusername']));
        break;
    case 'CheckEmail':
        echo  json_encode (InformationsPersonnellesControleur::verifyEmail($_POST['Nemail']));
        break;
            //Laisser le echo c'est pour le calendrier 
    case 'CheckTelephone':
        echo json_encode(InformationsPersonnellesControleur::verifyTelephone($_POST['Ntelephone']));
        break;
    case 'GetCalorieMin':
        echo ObjectifCalorieControleur::CalculCaloriesMin();
        break;
 
    case 'GetProteineMin':
       $array = ObjectifProteineControler::getObjProteineforDay();
       echo $array['proteines_min'];
        break;
   
    case 'GetEauConsomme':
         $result =  ObjectifEauControler::GetEauConsommeWithDate($_POST['date']);
         echo $result['eau_consomme'];
        break;
    case 'GetStatsWithDate':
       $array = StatistiqueDAO::GetStatsWithDate($_POST['date']);
       echo $array['calories_total'],":",$array['proteines_total'];
       break;

    case 'DeleteListAliments':
        echo ListeAlimentControleur::DeleteAliment($_POST['id_aliment']);
        break;
    case 'GetListAliments':
        $aliments = ListeAlimentControleur::GetListAliment($_POST['date'],$_POST['name']);
        echo ($aliments['id_aliment']);
        break;
    case 'GetAliment':
        $aliment = AlimentControleur::GetAliment($_POST['id_aliment']);
        echo  json_encode ($aliment);      
         break;
}
?>
