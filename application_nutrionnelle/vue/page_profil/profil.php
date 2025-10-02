<!--PROGRESSION CONTROLEUR 0%-->
<?php
require_once '../Page_connexion/connexion_base_de_donnée.php';
require_once '../../include/souvenirConnexion.php';
require_once(__DIR__. "/../../include/connexionBD.php");
require_once(__DIR__. "/../../controleurs/HistoriqueControleur.class.php");
require_once(__DIR__. "/../../modeles/Historique.class.php");
if (!isset($_SESSION['user_id'])) {
    header('Location: ../page_de_connexion_ou_inscription.php');
    exit;
}

$Historique = HistoriqueControleur::GetLastAction();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/profil.css">
    <title>Profil</title>
    <link rel="icon" href="../../images/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body onload="checkLogin()">
    <!--MICHAEL PROULX-->
    <div class="bloc_principal">
      <div class="bloc_haut">
        <!--NAVBAR-->
        <nav class="navbar navbar-expand-md bg-dark" aria-label="Eleventh navbar example">
          <div class="container-fluid">
            <!--Titre du nav-->
            <a id="navtitle" class="navbar-brand" href=""><img src="../../images/logo.png" style="height: 60px;"></a>
            <div class="collapse navbar-collapse">

              <!--Liste des différents liens du nav-->
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link" href="../page_accueil/accueil.php">Accueil</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="../page_aliment/aliment.php">Aliments</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="../page_objectifs/mesobjectifs.php">Objectifs</a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="" data-bs-toggle="dropdown" aria-expanded="false">Statistiques</a>
                  <ul class="dropdown-menu bg-dark">
                    <li><a class="dropdown-item" href="../pages_statistiques/page_statistiques_personnelles/statistique.php" style="color:white;">Statistiques Personnelles</a></li>
                    <li><a class="dropdown-item" href="../pages_statistiques/page_calendrier/calendrier.php" style="color:white;">Calendrier Hebdomadaire</a></li>
                  </ul>
                </li>
              </ul>

                <!--Profil et bouton déconnexion-->
                <h1 class="ligne">
                  <span style="display: flex; align-items: center; gap: 10px;">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      <li class="nav-item dropdown">
                        <a class="navprofil navtitle dropdown-toggle" href="" data-bs-toggle="dropdown" aria-expanded="false" style="color: gray; text-decoration:none" id="welcomeMessage">
                        <?php
                          // Afficher la valeur du session si elle existe
                          if (isset($_SESSION['username'])) {
                            echo htmlspecialchars($_SESSION['username']);
                          } else {
                            echo "Nom d'utilisateur non défini.";
                          }
                        ?>
                        </a>
                        <ul class="dropdown-menu bg-dark">
                          <li><a class="dropdown-item" href="../page_profil/profil.php" style="color:white;">Profil</a></li>
                          <li><a class="dropdown-item" href="../page_profil/detail_compte.php" style="color:white;">Paramètres</a></li>
                          <li><a class="dropdown-item">
                          <a href="../Déconnexion.php" class="btn btn-secondary w-80 btnanime" onclick="return confirmerLogout();" style="margin-left: 20px">
                            Déconnexion
                          </a>
                        </a>
                        </li>
                        </ul>
                      </li>
                    </ul>
                  </span>        
                </h1>
                
            </div>
          </div>
        </nav>
        <!--FIN BLOC HAUT-->
        </div> 
        <!--BLOC CENTRE-->
        <div class="bloc_centre page_parametre">
          <!--BLOC GAUCHE-->
          <div class="bloc_gauche bg-dark ">
            <?php require_once '../../include/menuprofil.php'?>
          </div>
          <div class="bloc_historique d-flex justify-content-center">
            <?php require_once '../../include/menuhistorique.php'?>
          </div>
          
        </div>
      </div>
      <!--FIN BLOC CENTRE-->
      </div>
      <?php require_once '../../include/footer.php'?>
      </div> 
      <!--FIN BLOC PRINCIPAL-->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<script>
function confirmerLogout() {
  const confirmer = confirm("Vous allez être déconnecté. Continuer ?");
  return confirmer;
}
</script>
