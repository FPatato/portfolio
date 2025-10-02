<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once '../../Page_connexion/connexion_base_de_donnée.php';
require_once '../../../include/souvenirConnexion.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../page_de_connexion_ou_inscription.php');
    exit;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../../css/calendrier.css">
        <title>Calendrier Hebdomadaire</title>
        <link rel="icon" href="../../../images/logo.png" type="image/png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script
          src="https://code.jquery.com/jquery-3.7.1.js"
          integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
          crossorigin="anonymous"></script>
          <script src="calendar-04/js/main.js?v=<?= time() ?>"></script>
        	<script src="calendar-04/js/jquery.min.js"></script>
          <script src="calendar-04/js/popper.js"></script>
          <script src="calendar-04/js/bootstrap.min.js"></script>
        	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
	        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
        <link rel="stylesheet" href="calendar-04/css/style.css?v=<?= time() ?>">




    </head>
    <body onload="checkLogin()">
      <div class="bloc_principal">
        <div class="bloc_haut">
          <!--MICHAEL PROULX-->
          <!--NAVBAR-->
          <nav class="navbar navbar-expand-md bg-dark" aria-label="Eleventh navbar example">
            <div class="container-fluid">
              <!--Titre du nav-->
              <a id="navtitle" class="navbar-brand" href=""><img src="../../../images/logo.png" style="height: 60px;"></a>
              <div class="collapse navbar-collapse">

                <!--Liste des différents liens du nav-->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                    <a class="nav-link" href="../../page_accueil/accueil.php">Accueil</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="../../page_aliment/aliment.php">Aliments</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="../../page_objectifs/mesobjectifs.php">Objectifs</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a id="navlinkactive" class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Statistiques</a>
                    <ul class="dropdown-menu bg-dark">
                      <li><a class="dropdown-item" href="../page_statistiques_personnelles/statistique.php" style="color:white;">Statistiques Personnelles</a></li>
                      <li><a class="dropdown-item" href="" style="color:white;">Calendrier Hebdomadaire</a></li>
                    </ul>
                  </li>
                </ul>

                  <!--Profil et bouton déconnexion-->
                  <h1 class="ligne">
                    <span style="display: flex; align-items: center; gap: 10px;">
                      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                        <a class="navtitle dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false" style="color: white; text-decoration: none;">
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
                            <li><a class="dropdown-item" href="../../page_profil/profil.php" style="color:white;">Profil</a></li>
                            <li><a class="dropdown-item" href="../../page_profil/detail_compte.php" style="color:white;">Paramètres</a></li>
                            <li><a class="dropdown-item">
                              <a href="../../Déconnexion.php" class="btn btn-secondary w-80 btnanime" onclick="return confirmerLogout();" style="margin-left: 20px">
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
        <div class="bloc_centre ">
          <!--HEADLINE-->
          <div class="position-relative overflow-hidden text-center imgheadline">
            <div class="col-md-8 p-lg-10 mx-auto my-3 textheadline"style="min-height: 650px " >
              <!--TITRE HEADLINE-->
               <h1 class="display-3 fw-bold titlecalendrier">Calendrier d'objectifs</h1> 
              <!--TEXTE HEADLINE-->
             <!-- <h2 class="fw-normal mb-3 fw-bold">Calendrier Objectifs</h2>-->
              <!--BOUTON CHOISIR DATE-->
              <div class="justify-content-center">

                        
                                <div class="container">
                                  <div class="row justify-content-center">
                                  </div>
                                  <div class="row">
                                    <div class="col-md-12"  style="margin-bottom: 100px;">
                                      <div class="content w-100">
                                        <div class="calendar-container" >
                                          <div class="calendar"> 
                                            <div class="year-header"> 
                                              <span class="left-button fa fa-chevron-left" id="prev"> </span> 
                                              <span class="year" id="label"></span> 
                                              <span class="right-button fa fa-chevron-right" id="next"> </span>
                                            </div> 
                                            <table class="months-table w-100"> 
                                              <tbody>
                                                <tr class="months-row">
                                                  <td class="month">Jan</td> 
                                                  <td class="month">Feb</td> 
                                                  <td class="month">Mar</td> 
                                                  <td class="month">Apr</td> 
                                                  <td class="month">May</td> 
                                                  <td class="month">Jun</td> 
                                                  <td class="month">Jul</td>
                                                  <td class="month">Aug</td> 
                                                  <td class="month">Sep</td> 
                                                  <td class="month">Oct</td>          
                                                  <td class="month">Nov</td>
                                                  <td class="month">Dec</td>
                                                </tr>
                                              </tbody>
                                            </table> 
                                            
                                            <table class="days-table w-100"> 
                                              <td class="day">Sun</td> 
                                              <td class="day">Mon</td> 
                                              <td class="day">Tue</td> 
                                              <td class="day">Wed</td> 
                                              <td class="day">Thu</td> 
                                              <td class="day">Fri</td> 
                                              <td class="day">Sat</td>
                                            </table> 
                                            <div class="frame"> 
                                              <table class="dates-table w-100"> 
                                                <tbody class="tbody">             
                                                </tbody> 
                                              </table>
                                            </div> 
                                          </div>
                                        </div>
                                        <div class="events-container">
                                          <div class="event-card" style="border-left: 10px solid rgb(255, 23, 68);"><div class="event-name">Yessir</div></div>
                                        </div>
                                        <div class="dialog" id="dialog">
                                            <h2 class="dialog-header"> Add New Event </h2>
                                            <form class="form" id="form">
                                              <div class="form-container" align="center">
                                                <label class="form-label" id="valueFromMyButton" for="name">Event name</label>
                                                <input class="input" type="text" id="name" maxlength="36">
                                                <label class="form-label" id="valueFromMyButton" for="count">Number of people to invite</label>
                                                <input class="input" type="number" id="count" min="0" max="1000000" maxlength="7">
                                                <input type="button" value="Cancel" class="button" id="cancel-button">
                                                <input type="button" value="OK" class="button button-white" id="ok-button">
                                              </div>
                                            </form>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                   

              </div>
            </div>
            <div class="product-device shadow-sm d-none d-md-block"></div>
            <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
            <!--*CALENDRIER GÉNÉRÉ*-->
            <!--FIN HEADLINE-->
          </div>
          <!--FIN BLOC CENTRE-->
        </div>
        <?php require_once '../../../include/footer.php'?>
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