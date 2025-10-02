<?php 
require_once(__DIR__. "/../DAO/HistoriqueDAO.class.php"); 
require_once __DIR__ . '/../controleurs/ObjectifCalorieControleur.class.php';
require_once __DIR__ . '/../controleurs/ObjectifProteineControleur.class.php';
require_once __DIR__ . '/../controleurs/ObjectifEauControler.class.php';

$aliments = HistoriqueDAO::GetRepas();
$dates = [];
foreach ($aliments as $row) {
    $dates[] = $row['date'];
}
$uniqueDates = array_unique($dates);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/accueil.css">
    <title>footer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
    
<script>
  let ids_aliments = [];
  let infoAliments = [];
              let totalCalories = 0;
              let totalServingSize = 0;
              let totalFat = 0;
              let totalSaturatedFat = 0;
              let totalCarbs = 0;
              let totalFiber = 0;
              let totalCholesterol = 0;
              let totalPotassium = 0;
              let totalSodium = 0;
              let totalSugar = 0;
              let totalProtein = 0;
  let index;

    async function HttpRequestGetAliment(id) {
            try {
          let response = await fetch("../../index/index.php?action=GetAliment", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
              body: "id_aliment=" +id ,

          });

          if (!response.ok) throw new Error("Erreur HTTP " + response.status);

          let data = await response.text();
          return data;

        } catch (err) {
          console.error("Erreur:", err);
        }

    }

    async function HttpRequestGetAlimentID(date,repas) {
  try {
    let response = await fetch("../../index/index.php?action=GetListAliments", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
        body: "date=" +date + "&name=" +repas,

    });

    if (!response.ok) throw new Error("Erreur HTTP " + response.status);

    let data = await response.text();
    return data;

  } catch (err) {
    console.error("Erreur:", err);
  }

}
    async function HttpRequestDeleteAliment(id_aliment) {
  try {
    let response = await fetch("../../index/index.php?action=DeleteListAliments", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
        body: "id_aliment=" +id_aliment,

    });

    if (!response.ok) throw new Error("Erreur HTTP " + response.status);

    let data = await response.text();
    return data;

  } catch (err) {
    console.error("Erreur:", err);
  }

}
async function myFunction(e) {
    e.preventDefault();
    var target = e.target;

        target.style.border = "2px solid #198754";
        target.style.borderRadius = "5px";

        if (target.dataset.repas && target.dataset.date) 
        {      
            const repas = target.dataset.repas;
            const date = target.dataset.date;

            let id_aliment = await HttpRequestGetAlimentID(date, repas);
            if (ids_aliments.includes(id_aliment)) {
                target.style.border = "none";
                target.style.borderRadius = "0px";
                ids_aliments = ids_aliments.filter(id => id !== id_aliment);
                 totalCalories -= parseFloat(infoAliments[infoAliments.length - 1].calories);
                totalServingSize -= parseFloat(infoAliments[infoAliments.length - 1].serving_size_g);
                totalFat -= parseFloat(infoAliments[infoAliments.length - 1].fat_total_g);
                totalSaturatedFat -= parseFloat(infoAliments[infoAliments.length - 1].fat_saturated_g) || 0;
                totalCarbs -= parseFloat(infoAliments[infoAliments.length - 1].carbohydrates_total_g);
                totalFiber -= parseFloat(infoAliments[infoAliments.length - 1].fiber_g);
                totalCholesterol -= parseFloat(infoAliments[infoAliments.length - 1].cholesterol_mg);
                totalPotassium -= parseFloat(infoAliments[infoAliments.length - 1].potassium_mg);
                totalSodium -= parseFloat(infoAliments[infoAliments.length - 1].sodium_mg);
                totalSugar -= parseFloat(infoAliments[infoAliments.length - 1].sugar_g);
                totalProtein -= parseFloat(infoAliments[infoAliments.length - 1].protein_g);
                
                 document.querySelector("#T-food-calories").innerText = "Total Calories: " + totalCalories.toFixed(2) + " kcal";
                  document.querySelector("#T-food-serving-size").innerText = "Total Serving Size: " + totalServingSize.toFixed(2) + " g";
                  document.querySelector("#T-food-fat-total").innerText = "Total Fat: " + totalFat.toFixed(2) + " g";
                  document.querySelector("#T-food-fat-saturated").innerText = "Total Saturated Fat: " + totalSaturatedFat.toFixed(2) + " g";
                  document.querySelector("#T-food-carbs").innerText = "Total Carbs: " + totalCarbs.toFixed(2) + " g";
                  document.querySelector("#T-food-fiber").innerText = "Total Fiber: " + totalFiber.toFixed(2) + " g";
                  document.querySelector("#T-food-cholesterol").innerText = "Total Cholesterol: " + totalCholesterol.toFixed(2) + " mg";
                  document.querySelector("#T-food-potassium").innerText = "Total Potassium: " + totalPotassium.toFixed(2) + " mg";
                  document.querySelector("#T-food-sodium").innerText = "Total Sodium: " + totalSodium.toFixed(2) + " mg";
                  document.querySelector("#T-food-sugar").innerText = "Total Sugar: " + totalSugar.toFixed(2) + " g";
                  document.querySelector("#T-food-protein").innerText = "Total Protein: " + totalProtein.toFixed(2) + " g";
            }
            else
            {
                ids_aliments.push(id_aliment);
                id_aliment = await HttpRequestGetAliment(id_aliment);
                infoAliments.push(JSON.parse(id_aliment));
                
                totalCalories += parseFloat(infoAliments[infoAliments.length - 1].calories);
                totalServingSize += parseFloat(infoAliments[infoAliments.length - 1].serving_size_g);
                totalFat += parseFloat(infoAliments[infoAliments.length - 1].fat_total_g);
                totalSaturatedFat += parseFloat(infoAliments[infoAliments.length - 1].fat_saturated_g) || 0;
                totalCarbs += parseFloat(infoAliments[infoAliments.length - 1].carbohydrates_total_g);
                totalFiber += parseFloat(infoAliments[infoAliments.length - 1].fiber_g);
                totalCholesterol += parseFloat(infoAliments[infoAliments.length - 1].cholesterol_mg);
                totalPotassium += parseFloat(infoAliments[infoAliments.length - 1].potassium_mg);
                totalSodium += parseFloat(infoAliments[infoAliments.length - 1].sodium_mg);
                totalSugar += parseFloat(infoAliments[infoAliments.length - 1].sugar_g);
                totalProtein += parseFloat(infoAliments[infoAliments.length - 1].protein_g);

                console.log(infoAliments[infoAliments.length - 1].fat_saturated_g);
                  document.querySelector("#T-food-calories").innerText = "Total Calories: " + totalCalories.toFixed(2) + " kcal";
                  document.querySelector("#T-food-serving-size").innerText = "Total Serving Size: " + totalServingSize.toFixed(2) + " g";
                  document.querySelector("#T-food-fat-total").innerText = "Total Fat: " + totalFat.toFixed(2) + " g";
                  document.querySelector("#T-food-fat-saturated").innerText = "Total Saturated Fat: " + totalSaturatedFat.toFixed(2) + " g";
                  document.querySelector("#T-food-carbs").innerText = "Total Carbs: " + totalCarbs.toFixed(2) + " g";
                  document.querySelector("#T-food-fiber").innerText = "Total Fiber: " + totalFiber.toFixed(2) + " g";
                  document.querySelector("#T-food-cholesterol").innerText = "Total Cholesterol: " + totalCholesterol.toFixed(2) + " mg";
                  document.querySelector("#T-food-potassium").innerText = "Total Potassium: " + totalPotassium.toFixed(2) + " mg";
                  document.querySelector("#T-food-sodium").innerText = "Total Sodium: " + totalSodium.toFixed(2) + " mg";
                  document.querySelector("#T-food-sugar").innerText = "Total Sugar: " + totalSugar.toFixed(2) + " g";
                  document.querySelector("#T-food-protein").innerText = "Total Protein: " + totalProtein.toFixed(2) + " g";
             
            }
        if (ids_aliments.length > 0) 
        {
            button=document.getElementById("supprimerButton");
            button.style.display = "block";
            card = document.getElementById("totalinfoCard");
            card.classList.remove("hidden");
            card.classList.add("visible");

            button.onclick = async function () {
            let a = 0;
            for (let i = 0; i < ids_aliments.length; i++) {
                a = await HttpRequestDeleteAliment(ids_aliments[i]);
            }            
            if (a == 1) {
                alert("Aliment supprimé avec succès");
                window.location.reload();
            } else {
                alert("Erreur lors de la suppression de l'aliment");
            }
          }
        }
        else {
            button=document.getElementById("supprimerButton");
            button.style.display = "none";
            card = document.getElementById("totalinfoCard");
            card.classList.remove("visible");
            card.classList.add("hidden");
            
        }
    }
}
</script>
            <div class="container_historique flex-shrink-0 p-3 " style="width: 600px;">
              <a class="d-flex justify-content-center pb-3 mb-3 text-decoration-none border-bottom" style="color:black">
                <span class="fs-5 fw-semibold">Historique de <?php echo $_SESSION['username']; ?></span>
              </a>
                  <?php foreach ($uniqueDates as $date): ?>
                        <div class="d-flex justify-content-center dropdown mb-3">
                            <button class="btnanime btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="false">
                                Repas du <?php echo ($date); ?>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($aliments as $row): ?>
                                    <?php if ($row['date'] == $date): ?>
                                        <li>
                                            <div class="dropdown-item d-flex justify-content-between align-items-center">
                                            <a class="dropdown-item" href="#" onclick="myFunction(event)"         
                                            data-repas="<?php echo htmlspecialchars($row['aliment']); ?>" 
                                            data-date="<?php echo htmlspecialchars($date); ?>">
                                                Repas : <?php echo ($row['aliment']);  ?> 
                                            </a>
                                          </div>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                        <div class="d-flex justify-content-center">
                          <button type="button" class="btn btn-danger" style="display: none;" id="supprimerButton">Supprimer</button>
                        </div>
                        
                  <ul class="list-unstyled ps-0">
                  <div class="container_hist">
                      <div>Objectif Calorique: <?php echo  $_SESSION['calories_min'], "calories"; echo " "; echo ObjectifCalorieControleur::calculEtatObjectif();?></div>
                      <div class="divdate"><?php echo $_SESSION['date']; ?></div>
                  </div>
                  </ul>
            

              <ul class="list-unstyled ps-0">
              <div class="container_hist">
                  <div>Objectif Proteine: <?php echo  $_SESSION['proteines_min'], "g"; echo " "; echo ObjectifProteineControler::calculEtatObjectif(); ?></div>
                  <div class="divdate"><?php echo $_SESSION['date']; ?></div>
              </div>
              </ul>

            
              <ul class="list-unstyled ps-0">
                <div class="container_hist">
                  <div>Objectif d'Eau: <?php echo   $_SESSION['ml_min'], "ml"; echo " "; echo ObjectifEauControler::calculEtatObjectif(); ?></div>
                  <div class="divdate"><?php echo $_SESSION['date']; ?></div>
              </div>
              </div>
              </ul>

                <div class="card hidden" id="totalinfoCard" style="border: 1px solid black; border-radius: 5px;" >
                <div class="container">
                      <h4><b>Valeurs nutritive totale:</b></h4>
                    <!-- <p class="card-text" id="T-food-name">Loading...</p>-->
                      <p class="card-text" id="T-food-calories"></p>
                      <p class="card-text" id="T-food-serving-size"></p>
                      <p class="card-text" id="T-food-fat-total"></p>
                      <p class="card-text" id="T-food-fat-saturated"></p>
                      <p class="card-text" id="T-food-carbs"></p>
                      <p class="card-text" id="T-food-fiber"></p>
                      <p class="card-text" id="T-food-cholesterol"></p>
                      <p class="card-text" id="T-food-potassium"></p>
                      <p class="card-text" id="T-food-sodium"></p>
                      <p class="card-text" id="T-food-sugar"></p>
                      <p class="card-text" id="T-food-protein"></p>
                    </div>
                  </div>
              </div>
            </div>
</body>
</html>