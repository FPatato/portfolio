<!--PROGRESSION CONTROLEUR 100%-->
<?php
require_once '../Page_connexion/connexion_base_de_donnée.php';
require_once '../../include/souvenirConnexion.php';
require_once(__DIR__. "/../../include/connexionBD.php");
require_once(__DIR__. "/../../controleurs/ObjectifCalorieControleur.class.php");
require_once(__DIR__. "/../../controleurs/ObjectifProteineControleur.class.php");
require_once(__DIR__ . '/../../controleurs/ObjectifEauControler.class.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../page_de_connexion_ou_inscription.php');
    exit;
}
/*--Calculer les valeurs à afficher à l'utilisateur--*/
$calories_min = ObjectifCalorieControleur::CalculCaloriesMin();
$proteine_min = ObjectifProteineControler::CalculProteinesMin();
$calories_consomme = ObjectifCalorieControleur::CalculCaloriesConsomme();
$proteines_consomme = ObjectifProteineControler::CalculProteinesConsomme();
$eau_consomme = ObjectifEauControler::CalculEauConsomme();
function getProgressColor($percent) {
    if ($percent <= 49) {
        return '#e74c3c'; // rouge
    } elseif ($percent <= 80) {
        return '#f39c12'; // orange
    } else {
        return '#27ae60'; // vert
    }
}

//Calcul de la progression pour la progress bar
$pourcentage_calories = min(100, round(($calories_consomme / $calories_min) * 100));
$pourcentage_proteines = min(100, round(($proteines_consomme / $proteine_min) * 100));
$pourcentage_eau = min(100, round(($eau_consomme / 2000) * 100));

// Cercle SVG (PROGRESS BAR ROND)
$rayon = 60;
$circonference = 2 * pi() * $rayon;

$offset_eau = $circonference * (1 - $pourcentage_eau / 100);
$offset_cal = $circonference * (1 - $pourcentage_calories / 100);
$offset_prot = $circonference * (1 - $pourcentage_proteines / 100);
$stroke_cal = getProgressColor($pourcentage_calories);
$stroke_prot = getProgressColor($pourcentage_proteines);
$stroke_eau = getProgressColor($pourcentage_eau);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/objectifs.css">
        <title>Menu Principal</title>
        <link rel="icon" href="../../images/logo.png" type="image/png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <!--MICHAEL PROULX-->
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
                <a class="nav-link" href="../page_accueil/accueil.php">Accueil</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../page_aliment/aliment.php">Aliments</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active navlinkactive" aria-current="page" href="../page_objectifs/mesobjectifs.php">Objectifs</a>
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
                       <a class="navtitle dropdown-toggle" href="" id="welcomeMessage" data-bs-toggle="dropdown" aria-expanded="false" style="color: white; text-decoration:none;">
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
                        <a href="../Déconnexion.php" class="btn btn-secondary w-80" onclick="return confirmerLogout();" style="margin-left: 20px">
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

      </div>

      <!--BLOC CENTRE-->
      <div class="bloc_centre">
        <div class="container-fluid">
          <!--HEADLINE-->
          <div class="position-relative overflow-hidden text-center imgheadline">
            <div class="divMesObjectif col-md-8 p-lg-5 mx-auto my-5">
               <!--TITRE HEADLINE-->
              <h1 class="objectifTitle display-3 fw-bold">Mes objectifs quotidiens</h1>
              <h3 class="fw-normal mb-3">
                  <!--CONTAINER POUR LES 3 OBJECTIFS-->
                  <div class="container marketing">
                    <div class="MesObjectif row">
                        <!--OBJECTIFS CALORIES-->
                        <div class="divObjectif col-lg-3">
                          <!--CERCLE DE PROGRESSION-->
                          <svg width="140" height="140" viewBox="0 0 140 140" class="progress-circle">
                            <circle cx="70" cy="70" r="60" stroke="#e6e6e6" stroke-width="10" fill="none" />
                            <circle
                              cx="70" cy="70" r="60"
                              stroke="<?php echo $stroke_cal; ?>"
                              stroke-width="10"
                              fill="none"
                              stroke-dasharray="<?php echo $circonference; ?>"
                              stroke-dashoffset="<?php echo $offset_cal; ?>"
                              transform="rotate(-90 70 70)"
                              style="transition: stroke-dashoffset 0.6s ease;"
                            />
                            <text x="70" y="75" text-anchor="middle" font-size="18" fill="#fff">
                              <?php echo $pourcentage_calories; ?>%
                            </text>
                          </svg>
                          <!--TITRE CALORIES + PROGRESSION-->
                          <h2 class="fw-normal"><b>Objectif Calories<br> </b></h2>
                          <h3><?php echo $calories_consomme ?>/<?php echo $calories_min ?>cal</h3>
                          <p>Nombre de calories consommées aujourd'hui.</p>
                        </div>
                        <!--OBJECTIFS PROTÉINES-->
                        <div class="divObjectif col-lg-3">
                          <!--CERCLE DE PROGRESSION-->
                          <svg width="140" height="140" viewBox="0 0 140 140" class="progress-circle">
                            <circle cx="70" cy="70" r="60" stroke="#e6e6e6" stroke-width="10" fill="none" />
                            <circle
                              cx="70" cy="70" r="60"
                              stroke="<?php echo $stroke_prot; ?>"
                              stroke-width="10"
                              fill="none"
                              stroke-dasharray="<?php echo $circonference; ?>"
                              stroke-dashoffset="<?php echo $offset_prot; ?>"
                              transform="rotate(-90 70 70)"
                              style="transition: stroke-dashoffset 0.6s ease;"
                            />
                            <text x="70" y="75" text-anchor="middle" font-size="18" fill="#fff">
                              <?php echo $pourcentage_proteines; ?>%
                            </text>
                          </svg>
                          <!--TITRE PROTÉINES + PROGRESSION-->
                          <h2 class="fw-normal"><b>Objectif Protéines<br> </b></h2>
                          <h3><?php echo $proteines_consomme ?>/<?php echo $proteine_min ?>g</h3>
                          <p>Nombre de protéines consommés aujourd'hui.</p>
                        </div>
                        <!--OBJECTIFS EAU-->
                        <div class="divObjectif col-lg-3">
                          <!--CERCLE DE PROGRESSION-->
                          <svg width="140" height="140" viewBox="0 0 140 140" class="progress-circle">
                            <!-- Cercle de fond -->
                            <circle
                              cx="70"
                              cy="70"
                              r="60"
                              stroke="#e6e6e6"
                              stroke-width="10"
                              fill="none"
                            />
                            <!-- Cercle de progression -->
                            <circle
                              cx="70"
                              cy="70"
                              r="60"
                              stroke="<?php echo $stroke_eau; ?>"
                              stroke-width="10"
                              fill="none"
                              stroke-dasharray="<?php echo $circonference; ?>"
                              stroke-dashoffset="<?php echo $offset_eau; ?>"
                              transform="rotate(-90 70 70)"
                              style="transition: stroke-dashoffset 0.6s ease;"
                            />
                            <!-- Pourcentage au centre -->
                            <text x="70" y="75" text-anchor="middle" font-size="18" fill="#fff">
                              <?php echo $pourcentage_eau; ?>%
                            </text>
                          </svg>
                          <!--TITRE ALIMENTAIRE + PROGRESSION-->
                          <h2 class="fw-normal"><b>Objectif Eau</b></h2>
                          <h3><?php echo $eau_consomme ?>/2000ml</h3>
                          <p>Nombre de ml d'eau consommés aujourd'hui.</p>
                       </div>
                    </div>
                    <!--FIN CONTAINER 3 OBJECTIFS-->
                  </div>
              </h3>
            </div>
              <div class="product-device shadow-sm d-none d-md-block"></div>
              <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
               <!--FIN HEADLINE-->
          </div>
           <!--FIN CONTAINER-->
        </div>
        <!--INFORMATIONS OBJECTIFS (OPTIONNEL POUR L'INSTANT)-->
        
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