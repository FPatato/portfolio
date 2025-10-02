<!--PROGRESSION CONTROLEUR 100%-->
<?php
session_start();
require_once '../Page_connexion/connexion_base_de_donnée.php';
require_once '../../DAO/ListeObjectifDAO.class.php';
require_once '../../include/souvenirConnexion.php';



//  Vérifier automatiquement le cookie si la session est vide
if (!isset($_SESSION['user_id']) && isset($_COOKIE['rememberMe'])) {
  $user = RememberMeControleur::verifierToken($_COOKIE['rememberMe']);
  if ($user) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
  }
}

// Vérifie si l'utilisateur est connecté via la session
if (isset($_SESSION['username'])) {
    $message_bienvenue = "Bienvenue, " . htmlspecialchars($_SESSION['username']) . "!";
    $message_nom_seul = htmlspecialchars($_SESSION['username']);
} else {
    $message_bienvenue = "Vous n'êtes pas connecté.";
    $message_nom_seul = "Vous n'êtes pas connecté";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/accueil.css">
    <title>Accueil</title>
    <link rel="icon" href="../../images/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body onload="checkLogin()">
    <!--MICHAEL PROULX-->
    <!--BLOC PRINCIPAL-->
    <div class="bloc_principal">
      <!--BLOC HAUT-->
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
                  <a class="nav-link active navlinkactive" aria-current="page" href="accueil.php">Accueil</a>
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
                        <a class="navtitle dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false" style="color: white; text-decoration:none;">
                            <?php
                            echo $message_nom_seul;
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
      <div class="bloc_centre">
        <div class="container-fluid">
          <!--HEADLINE-->
          <div id="imgHL" class="position-relative overflow-hidden text-center imgheadline">
            <div class="col-md-6 p-lg-5 mx-auto my-5 textheadline">
              <!--TITRE HEADLINE-->
              <h1 class="display-3 fw-bold">Application Nutritionnelle <br></h1>
              <h1 class="display-5 fw-bold"><?php echo $message_bienvenue;?></h1>
              <!--TEXTE HEADLINE-->
              <h3 class="fw-normal mb-3">Traquez votre consommation, vos objectifs et bien plus encore!</h3>
              <div class="d-flex gap-3 justify-content-center lead fw-normal div-des-bouton">
                <!--BOUTON DÉBUTER HEADLINE-->
                <a class="btnanime btn btn-primary mt-3" href="../page_aliment/aliment.php">
                  Débuter
                  <i class="bi bi-chevron-right btnanime"></i>
                </a>
                <!--BOUTON PROFIL HEADLINE-->
                <a class="btn btn-secondary mt-3 btnanime" href="../page_profil/profil.php">
                  Consulter votre profil
                  <i class="bi bi-chevron-right"></i>
                </a>
              </div>
            </div>
            <div class="product-device shadow-sm d-none d-md-block"></div>
            <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
          </div>
          <!--HEROES-->
          <div class="px-4 pt-5 my-5 text-center">
            <!--TITRE HEROES-->
            <h1 class="display-4 fw-bold text-body-emphasis">Créer des objectifs quotidiens!</h1>
            <div class="col-lg-6 mx-auto">
               <!--TEXTE HEROES-->
              <p class="lead mb-4">Avec notre application nutritionnelle, vous pouvez concevoir vos propres objectifs quotidiens! Traquer vos calories quotidiennement est une tâche difficle ? Les objectifs quotidiens vous aident a garder un progrès sur votre consommation tout au long de la journée et les objectifs se mettent a jour régulièrement afin de vous aider dans votre progression.</p>
             
            
            <div class="overflow-hidden">
              <div class="container px-5">
                <img src="../../images/objectif.jpg" class="img-fluid border rounded-3 shadow-lg mb-4" alt="image objectif" width="700" height="500" loading="lazy">
              </div>
            </div> 
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
                <a href="../page_objectifs/mesobjectifs.php"><br><button type="button" class="btn btn-dark btn-lg px-4 me-sm-3 btnanime">Accédez aux objectifs</button></a>
              </div>
          </div>
          </div>

          <!--COLONE D'ICONS-->
          <div class="container px-4 py-5" id="featured-3">
            <h2 class="pb-2 border-bottom"></h2>
            <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
              <!--ICON 1-->
              <div class="feature col">
                <!--TITRE ICON 1-->
                <h3 class="fs-2 text-body-emphasis">Statistiques Personnelles</h3>
                <!--TEXTE ICON 1-->
                <p>L'application garde un suivi de vos calories et génère un graphiques pour votre consommation quotidienne, hebdomadaire et mensuelle!</p>
              </div>
              <!--ICON 2-->
              <div class="feature col">
                <!--TITRE ICON 2-->
                <h3 class="fs-2 text-body-emphasis">Calcul de l'IMC</h3>
                <!--TEXTE ICON 2-->
                <p>Entrez votre poids et votre taille quotidiennement afin d'obtenir des résultats précis. L'application calcul quotidiennement votre Indice de Masse Corporelle.</p>
              </div>
              <!--ICON 3-->
              <div class="feature col">
                <!--TITRE ICON 3-->
                <h3 class="fs-2 text-body-emphasis">Historique de repas</h3>
                <!--TEXTE ICON 3-->
                <p>Entrez vos repas consommés dans la journée afin d'obtenir des résultat plus précis. L'application vous aide à garder un oeil sur votre consommation quotidienne.</p>
              </div>
            </div>
            <!--FIN COLONNE D'ICON-->
          </div>
          <!--FIN CONTAINER-->
        </div>
        <!--FIN BLOC_CENTRE-->
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