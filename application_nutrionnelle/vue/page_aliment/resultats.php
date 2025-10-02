<!--PROGRESSION CONTROLEUR 100%-->
<?php     
error_reporting(0);
include("../../controleurs/ListeAlimentControleur.class.php");
include ("../../controleurs/AlimentControleur.class.php");

AlimentControleur::ajouterAliment();
ListeAlimentControleur::AddListAliment($_GET['id_aliment']);
?>


