<!--PROGRESSION CONTROLEUR 0%-->
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
  <link rel="stylesheet" href="../../../css/statistique.css">
  <title>Statistiques Personnelles</title>
  <link rel="icon" href="../../../images/logo.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body onload="checkLogin()">
  <!--MICHAEL PROULX-->
  <div class="bloc_principal">
    <div class="bloc_haut">
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
                <a id="navlinkactive" class="nav-link dropdown-toggle" href="" data-bs-toggle="dropdown"
                  aria-expanded="false">Statistiques</a>
                <ul class="dropdown-menu bg-dark">
                  <li><a class="dropdown-item" href="" style="color:white;">Statistiques Personnelles</a></li>
                  <li><a class="dropdown-item" href="../page_calendrier/calendrier.php" style="color:white;">Calendrier
                      Hebdomadaire</a></li>
                </ul>
              </li>
            </ul>

            <!--Profil et bouton déconnexion-->
            <h1 class="ligne">
              <span style="display: flex; align-items: center; gap: 10px;">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item dropdown">
                    <a class="navtitle dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"
                      style="color: white; text-decoration: none;">
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
                      <li><a class="dropdown-item" href="../../page_profil/profil.php" style="color:white;">Profil</a>
                      </li>
                      <li><a class="dropdown-item" href="../../page_profil/detail_compte.php"
                          style="color:white;">Paramètres</a></li>
                      <li><a class="dropdown-item">
                          <a href="../../Déconnexion.php" class="btn btn-secondary w-80 btnanime"
                            onclick="return confirmerLogout();" style="margin-left: 20px">
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
    <?php
    include("../../../controleurs/StatistiqueControleur.class.php");
    include("../../../include/connexionBD.php");
    $dataPCG = StatistiqueControleur::PieGraph();
    $dataWeek = StatistiqueControleur::BarGraph();
    $dataMonth = StatistiqueControleur::LineGraph();
    ?>
    <div class="bloc_centre">
      <!--HEADLINE-->
      <div id="imgHL" class="text-center imgheadline">
        <div class="col-md-6 p-lg-5 mx-auto my-5 textheadline">
          <!--TITRE HEADLINE-->
          <h1 class="display-3 fw-bold">Statistiques Personnelles</h1>
          <!--TEXTE HEADLINE-->
          <h3 class="fw-normal mb-3">L'application a gardé un suivi de votre progression!</h3>
        </div>
        <div class="product-device shadow-sm d-none d-md-block"></div>
        <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
      </div>
    </div>

    <!-- BLOC SÉPARÉ POUR LES GRAPHIQUES -->
    <div class="container-fluid blocGraphique"
      style="display: flex; justify-content: center; align-items: start; gap: 30px; padding: 30px; background-color: white;">
      <!-- Infos graphiques -->
      <div id="graphInfo" class="containergraph p-4 rounded graphinfo"
        style="flex: 1; min-width: 300px; max-width: 400px; max-height: 400px;">
        <!-- Le texte sera mis ici dynamiquement -->
      </div>

      <!-- Conteneur du graphique -->
      <div class="containergraph p-4 rounded overflow-hidden" style="flex: 2; min-width: 400px;">
        <div id="carouselGraphs" class="d-flex" style="width: 100%; transition: transform 0.5s ease;">
          <canvas id="chartCanvas1" style="width: 100%; max-height:400px;"></canvas>
          <canvas id="chartCanvas2" style="width:100%; max-height:400px;"></canvas>
          <canvas id="chartCanvas3" style="width:100%; max-height:400px;"></canvas>
        </div>
      </div>
    </div>
  </div>




  <!--SCRIPT GRAPHIQUE LINÉAIRE-->
  <script>
    // Graphique Linéaire
    var ctxLine = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(ctxLine, {
      type: 'line',
      data: {
        labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        datasets: [{
          label: 'Bilan par mois',
          data: [<?php echo $dataMonth['January']; ?>, <?php echo $dataMonth['February']; ?>, <?php echo $dataMonth['March']; ?>, <?php echo $dataMonth['April']; ?>, <?php echo $dataMonth['May']; ?>,
            <?php echo $dataMonth['June']; ?>, <?php echo $dataMonth['July']; ?>, <?php echo $dataMonth['August']; ?>, <?php echo $dataMonth['September']; ?>, <?php echo $dataMonth['October']; ?>, <?php echo $dataMonth['November']; ?>,
            <?php echo $dataMonth['December']; ?>],
          fill: false,
          borderColor: 'rgb(75, 192, 192)', // line color
          tension: 0.1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true // Start the Y-axis at zero
          }
        }
      }
    });
  </script>
  <!--SCRIPT GRAPHIQUE A BANDE-->

  <script>
    // Graphique a Bande
    var xValues = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
    var yValues = [<?php echo $dataWeek['Sunday']; ?>, <?php echo $dataWeek['Monday']; ?>, <?php echo $dataWeek['Tuesday']; ?>, <?php echo $dataWeek['Wednesday']; ?>,
      <?php echo $dataWeek['Thursday']; ?>, <?php echo $dataWeek['Friday']; ?>, <?php echo $dataWeek['Saturday']; ?>];
    var barColors = ["red", "green", "blue", "orange", "brown"];

    new Chart("barChart", {
      type: "bar",
      data: {
        labels: xValues,
        datasets: [{
          backgroundColor: barColors,
          data: yValues
        }]
      },
      options: {
        legend: { display: false },
        title: {
          display: true,
          text: "Bilan par jour de semaine"
        }
      }
    });
  </script>
  <!--SCRIPT GRAPHIQUE EN SECTEURS-->
  <script>
    // Graphique en secteurs

    var xValues = ["Proteine", "Glucide", "Gras"];
    var yValues = [<?php echo $dataPCG['protein']; ?>, <?php echo $dataPCG['carbs']; ?>, <?php echo $dataPCG['fat']; ?>];
    var barColors = [
      "#b91d47",
      "#2b5797",
      "#D5B60A",

    ];

    new Chart("pieChart", {
      type: "pie",
      data: {
        labels: xValues,
        datasets: [{
          backgroundColor: barColors,
          data: yValues
        }]
      },
      options: {
        title: {

          display: true,
          text: "Macro Nutriment"
        }
      }
    });
  </script>
  <!--SCRIPT DE DÉCONNEXION-->
  <script>
    // Louca Cugnet Cointe
    function logout() {
      localStorage.removeItem('username');
      alert('Vous êtes déconnecté.');
      window.location.href = "../../page_connexion/connexion.php";
    }
  </script>
  <!--FIN BLOC CENTRE-->
  </div>
  <?php require_once '../../../include/footer.php' ?>
  </div>
  <!--FIN BLOC PRINCIPAL-->
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>
<script>
  function confirmerLogout() {
    const confirmer = confirm("Vous allez être déconnecté. Continuer ?");
    return confirmer;
  }
</script>



<script>
  const chartInfos = [
    {
      type: "line",
      data: {
        labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        datasets: [{
          label: 'Bilan par mois',
          data: [<?php echo $dataMonth['January']; ?>, <?php echo $dataMonth['February']; ?>, <?php echo $dataMonth['March']; ?>,
            <?php echo $dataMonth['April']; ?>, <?php echo $dataMonth['May']; ?>, <?php echo $dataMonth['June']; ?>,
            <?php echo $dataMonth['July']; ?>, <?php echo $dataMonth['August']; ?>, <?php echo $dataMonth['September']; ?>,
            <?php echo $dataMonth['October']; ?>, <?php echo $dataMonth['November']; ?>, <?php echo $dataMonth['December']; ?>],
          borderColor: "rgb(75, 192, 192)",
          tension: 0.3,
          fill: false
        }]
      },
      text: `<p style="font-size:24px;"><b>Graphique linéaire :</b></p>
           Janvier : <b><?php echo $dataMonth['January']; ?></b> cal<br> Février : <b><?php echo $dataMonth['February']; ?></b> cal<br> Mars : <b><?php echo $dataMonth['March']; ?></b> cal<br>
           Avril : <b><?php echo $dataMonth['April']; ?></b> cal<br> Mai : <b><?php echo $dataMonth['May']; ?></b> cal<br> Juin : <b><?php echo $dataMonth['June']; ?></b> cal<br>
           Juillet : <b><?php echo $dataMonth['July']; ?></b> cal<br> Août : <b><?php echo $dataMonth['August']; ?></b> cal<br> Sept : <b><?php echo $dataMonth['September']; ?></b> cal<br>
           Oct : <b><?php echo $dataMonth['October']; ?></b> cal<br> Nov : <b><?php echo $dataMonth['November']; ?></b> cal<br> Déc : <b><?php echo $dataMonth['December']; ?></b> cal`
    },
    {
      type: "bar",
      data: {
        labels: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
        datasets: [{
          label: "Calories",
          data: [<?php echo $dataWeek['Sunday']; ?>, <?php echo $dataWeek['Monday']; ?>, <?php echo $dataWeek['Tuesday']; ?>,
            <?php echo $dataWeek['Wednesday']; ?>, <?php echo $dataWeek['Thursday']; ?>,
            <?php echo $dataWeek['Friday']; ?>, <?php echo $dataWeek['Saturday']; ?>],
          backgroundColor: ["red", "green", "blue", "orange", "purple", "cyan", "gray"]
        }]
      },
      text: `<p style="font-size:24px;"><b>Graphique à bandes :</b></p>
           Dimanche : <b><?php echo $dataWeek['Sunday']; ?></b> cal<br><br> Lundi : <b><?php echo $dataWeek['Monday']; ?></b> cal<br><br> Mardi : <b><?php echo $dataWeek['Tuesday']; ?></b> cal<br><br>
           Mercredi : <b><?php echo $dataWeek['Wednesday']; ?></b> cal<br><br> Jeudi : <b><?php echo $dataWeek['Thursday']; ?></b> cal<br><br>
           Vendredi : <b><?php echo $dataWeek['Friday']; ?></b> cal<br><br> Samedi : <b><?php echo $dataWeek['Saturday']; ?></b> cal`
    },
    {
      type: "pie",
      data: {
        labels: ["Protéines", "Glucides", "Gras"],
        datasets: [{
          data: [<?php echo $dataPCG['protein']; ?>, <?php echo $dataPCG['carbs']; ?>, <?php echo $dataPCG['fat']; ?>],
          backgroundColor: ["#b91d47", "#2b5797", "#D5B60A"]
        }]
      },
      text: `<p style="font-size:24px;"><b>Graphique circulaire :</b></p>
           Protéines : <b><?php echo $dataPCG['protein']; ?>%</b><br><br><br><br><br>
           Glucides : <b><?php echo $dataPCG['carbs']; ?>%</b><br><br><br><br><br>
           Gras : <b><?php echo $dataPCG['fat']; ?>%</b>`
    }
  ];

  let currentIndex = 0;
  let chartInstances = [];

  function updateGraph() {
    const canvasIds = ["chartCanvas1", "chartCanvas2", "chartCanvas3"];
    const ctx = document.getElementById(canvasIds[currentIndex]).getContext("2d");

    if (chartInstances[currentIndex]) chartInstances[currentIndex].destroy();

    chartInstances[currentIndex] = new Chart(ctx, {
      type: chartInfos[currentIndex].type,
      data: chartInfos[currentIndex].data,
      options: { responsive: true }
    });

    document.getElementById("graphInfo").innerHTML = chartInfos[currentIndex].text;

    // Faire défiler le carousel
    const carousel = document.getElementById("carouselGraphs");
    carousel.style.transform = `translateX(-${currentIndex * 100}%)`;

    currentIndex = (currentIndex + 1) % chartInfos.length;
  }

  updateGraph(); // initial
  setInterval(updateGraph, 6000); // changement auto toutes les 6 secondes
</script>