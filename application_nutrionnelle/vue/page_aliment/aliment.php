<!--PROGRESSION CONTROLEUR 100%-->
<?php
session_start();
require_once '../Page_connexion/connexion_base_de_donnée.php';
require_once '../../include/souvenirConnexion.php';
require_once(__DIR__ . '/../../controleurs/ObjectifEauControler.class.php');


if (!isset($_SESSION['user_id'])) {
    header('Location: ../page_de_connexion_ou_inscription.php');
    exit;
}
/*--Vérifier si le formulaire a été soumis--*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Créer un nouvel objet InformationsPersonnelles
  ObjectifEauControler::AjouterEauConsomme();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/aliment.css">
        <title>Menu Principal</title>
        <link rel="icon" href="../../images/logo.png" type="image/png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
          <!--SCRIPT D'AMINE-->
            <script>
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
              let fullName = "";

             function addFoodField() {
            

              let input = document.getElementById("foodenter");
              let foodList = document.getElementById("foodList"); 

              let foodInputContainer = document.getElementById("foodEnterContainer"); 
              let listItem = document.createElement("li");
              let getListItem  = foodList.getElementsByTagName("li");
              let info = document.getElementById("infoCard");
              let infoTotal = document.getElementById("totalinfoCard");
              let js_data;
              
              let buttonAjouter = document.getElementById("ajouter");

              listItem.className = "list-group-item slide-in";
              listItem.id = "food";
              listItem.textContent = input.value;

              let hiddenInput = document.createElement("input");
              hiddenInput.type = "hidden";   
              hiddenInput.name = "foodenter[]"; 
              hiddenInput.value = input.value.trim(); 

              if(getListItem != 0 || getListItem > 0)
             {
                infoTotal.classList.remove("hidden");
             }
           
           
              listItem.onclick = function() 
              {
              listItem.remove(); 
              hiddenInput.remove();
              info.classList.add("hidden");

              messageAliment.textContent = "Repas supprimé.";
              messageAliment.classList.remove("hidden");
              messageAliment.style.opacity = "1";

              setTimeout(() => {
                messageAliment.style.opacity = "0";
                setTimeout(() => {
                  messageAliment.classList.add("hidden");
                }, 500);
              }, 700);
              
              if(getListItem.length == 0)
              {
               
                info.classList.add("hidden");
                infoTotal.classList.add("hidden");
              }
              js_data.forEach(item=>{
                if (item.name.toLowerCase() === hiddenInput.value.toLowerCase()) {

                  totalCalories = totalCalories - item.calories;
                  totalServingSize = totalServingSize - item.serving_size_g;
                  totalFat = totalFat - item.fat_total_g;
                  totalSaturatedFat = totalSaturatedFat - item.fat_saturated_g;
                  totalCarbs = totalCarbs - item.carbohydrates_total_g;
                  totalFiber = totalFiber - item.fiber_g;
                  totalCholesterol = totalCholesterol - item.cholesterol_mg;
                  totalPotassium = totalPotassium - item.potassium_mg;
                  totalSodium = totalSodium - item.sodium_mg;
                  totalSugar = totalSugar - item.sugar_g;
                  totalProtein = totalProtein - item.protein_g;
                  
                  let lengthName = item.name.length;
           
                  

                  //fullName = fullName.substr(0, lengthName);
                  //fullName.replace(/,/g," ");
               
                  console.log("test:"+fullName);

                  

                  //document.querySelector("#T-food-name").innerText = "Nom: " + fullName;
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
              })
          };
              listItem.style.transition = "all 0.2s ease";
              
              listItem.onmouseover = function()
               {
              listItem.style.fontSize = "1.2rem";
              listItem.style.transform = "scale(1.1)";
              listItem.style.backgroundColor = "AliceBlue";
              listItem.style.border = "1px solid #000000";
              listItem.style.borderRadius = "10px";
              info.classList.remove("hidden");
            
              if (js_data && js_data.length > 0) {
               
                js_data.forEach(item => {
                    if (item.name.toLowerCase() === hiddenInput.value.toLowerCase()) {
                      document.querySelector("#food-name").innerText = "Nom: " + item.name;
                      document.querySelector("#food-calories").innerText = "Calories: " + item.calories.toFixed(2) + " kcal";
                      document.querySelector("#food-serving-size").innerText = "Portion: " + item.serving_size_g.toFixed(2) + " g";
                      document.querySelector("#food-fat-total").innerText = "Gras total: " + item.fat_total_g.toFixed(2) + " g";
                      document.querySelector("#food-fat-saturated").innerText = "Gras sature: " + item.fat_saturated_g.toFixed(2) + " g";
                      document.querySelector("#food-carbs").innerText = "Glucide: " + item.carbohydrates_total_g.toFixed(2) + " g";
                      document.querySelector("#food-fiber").innerText = "Fibre: " + item.fiber_g.toFixed(2) + " g";
                      document.querySelector("#food-cholesterol").innerText = "Cholesterol: " + item.cholesterol_mg.toFixed(2) + " mg";
                      document.querySelector("#food-potassium").innerText = "Potassium: " + item.potassium_mg.toFixed(2) + " mg";
                      document.querySelector("#food-sodium").innerText = "Sodium: " + item.sodium_mg.toFixed(2) + " mg";
                      document.querySelector("#food-sugar").innerText = "Sucre: " + item.sugar_g .toFixed(2)+ " g";
                      document.querySelector("#food-protein").innerText = "Proteine: " + item.protein_g.toFixed(2) + " g"; 
                    }
                });
            }
              };

            listItem.onmouseout = function() 
            {
            listItem.style.fontSize = "1rem";
            listItem.style.transform = "scale(1)";
            listItem.style.backgroundColor = "white";
            listItem.style.border = "none";
            listItem.style.borderRadius = "0px";
            info.classList.add("hidden");
            };

        

   
              foodEnterContainer.appendChild(hiddenInput);
              foodList.appendChild(listItem);
              input.value = "";


             

              const url = 'https://api.calorieninjas.com/v1/nutrition?query=' + hiddenInput.value;
              const options = {
              method: 'GET',
              headers: {
              'X-Api-Key': 'TDNZYhxaGcvFVyCpv3wruA==7t0zg7grP0lOKrl7',
               'Content-Type': 'application/json'
                }
            };
            async function fetchdata() {
               try 
              {
                  const response = await fetch(url, options);
                  const result = await response.json();
                  return result.items; 
              } 
              catch (error) 
            {
              console.error('Error:', error);
            }
            }
           async function main() {
               js_data = await fetchdata(); 

               if (js_data && js_data.length > 0) {
               js_data.forEach(item => {
                   if (item.name.toLowerCase() === hiddenInput.value.toLowerCase()) {
                   
                    totalCalories += item.calories;
                    totalServingSize += item.serving_size_g;
                    totalFat += item.fat_total_g;
                    totalSaturatedFat += item.fat_saturated_g;
                    totalCarbs += item.carbohydrates_total_g;
                    totalFiber += item.fiber_g;
                    totalCholesterol += item.cholesterol_mg;
                    totalPotassium += item.potassium_mg;
                    totalSodium += item.sodium_mg;
                    totalSugar += item.sugar_g;
                    totalProtein += item.protein_g;
                    //fullName += (item.name + ', ');

                    //document.querySelector("#T-food-name").innerText = "Nom: " + fullName;
                    document.querySelector("#T-food-calories").innerText = "Calories: " + totalCalories.toFixed(2) + " kcal";
                    document.querySelector("#T-food-serving-size").innerText = "Portion: " + totalServingSize.toFixed(2) + " g";
                    document.querySelector("#T-food-fat-total").innerText = "Gras Total: " + totalFat.toFixed(2) + " g";
                    document.querySelector("#T-food-fat-saturated").innerText = "Gras Sature: " + totalSaturatedFat.toFixed(2) + " g";
                    document.querySelector("#T-food-carbs").innerText = "Glucide: " + totalCarbs.toFixed(2) + " g";
                    document.querySelector("#T-food-fiber").innerText = "Fibre: " + totalFiber.toFixed(2) + " g";
                    document.querySelector("#T-food-cholesterol").innerText = "Cholesterol: " + totalCholesterol.toFixed(2) + " mg";
                    document.querySelector("#T-food-potassium").innerText = "Potassium: " + totalPotassium.toFixed(2) + " mg";
                    document.querySelector("#T-food-sodium").innerText = "Sodium: " + totalSodium.toFixed(2) + " mg";
                    document.querySelector("#T-food-sugar").innerText = "Sucre: " + totalSugar.toFixed(2) + " g";
                    document.querySelector("#T-food-protein").innerText = "Proteine: " + totalProtein.toFixed(2) + " g";



                   }
               });
           
           }
             }
            main();
}

          </script>
    </head>
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
                  <a class="nav-link" href="../page_accueil/accueil.php">Accueil</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active navlinkactive" aria-current="page" href="aliment.php">Aliments</a>
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
      <div class="bloc_centre">
        <div class="container-fluid">
          <!--HEADLINE-->
          <div class="position-relative overflow-hidden text-center imgalimentheadline">
            <div class="col-md-7 p-lg-5 mx-auto my-5 textheadline">
              <!--TITRE HEADLINE-->
              <h1 class="display-3 fw-bold">Liste de repas</h1>
              <!--TEXTE HEADLINE-->
              <h3 class="fw-normal mb-3" style="padding-bottom: 20px;">Suivez votre consomation au quotidien! <br></h3>
              <div class="ListeAlimentDiv gap-3 justify-content-center lead fw-normal">
                <!--AFFICHAGE VALEURS NUTRITIVES D'UN ALIMENT-->
                <div class="card hidden" id="infoCard">
                  <div class="container">
                    <h4><b>Valeurs nutritive</b></h4>
                    <p class="card-text" id="food-name"></p>
                    <p class="card-text" id="food-calories"></p>
                    <p class="card-text" id="food-serving-size"></p>
                    <p class="card-text" id="food-fat-total"></p>
                    <p class="card-text" id="food-fat-saturated"></p>
                    <p class="card-text" id="food-carbs"></p>
                    <p class="card-text" id="food-fiber"></p>
                    <p class="card-text" id="food-cholesterol"></p>
                    <p class="card-text" id="food-potassium"></p>
                    <p class="card-text" id="food-sodium"></p>
                    <p class="card-text" id="food-sugar"></p>
                    <p class="card-text" id="food-protein"></p>
                  </div>
                </div>

                <!--LISTE D'ALIMENT-->
                <div class="d-flex flex-column align-items-center">
                  <div class="card mb-3">
                    <div class="card-body"  style="width: 18rem; background-color: white;">
                      <ul class="list-group list-group-flush" id="foodList">

                        <form>
                          <!--LISTE D'ALIMENT DATE-->
                          <label for="date">Date</label>
                          <input type="date" id="date" name="date" value="today">
                         </form>
                         <!--LISTE D'ALIMENT TITRE ALIMENT-->
                        <h5 class="card-header">Aliments</h5>
                      </ul>
                
                    </div>
                  </div>
                  <!--SCRIPT POUR OBTENIR LA DATE D'AUJOURD'HUI-->
                  <script>
                    const date = new Date(); 
                    const currentDate = date.toISOString().split('T')[0];             
                    document.getElementById("date").value = currentDate;
                  </script>
                  <!--FORMULAIRE AJOUTER UN ALIMENT-->
                  <form action="apiReq.php" method="get" style="padding-bottom: 10px;" id="ListeAlimentForm">
                    <div class="form-group" id="foodEnterContainer" >
                      <label for="foodenter">Entrer un aliment</label>
                      <input type="text" class="form-control" id="foodenter" name="foodenter[]" aria-describedby="emailHelp" placeholder="">
                    </div>
                    <!--FORMULAIRE AJOUTER UN ALIMENT BOUTONS-->
                    <button type="button" class="btn btn-secondary mt-3 btnanime" onclick="addFoodField()" id="addAlimentBtn">Ajouter un nouvel aliment</button>
                    <button type="submit" class="btn btn-primary mt-3 btnanime" id="addListeAlimentBtn">Sauvegarder</button>
                  <p id="messageAliment" class="hidden"></p>
                  </form>
                  <form class="needs-validation" action="aliment.php" method="post" id="EauForm">
                            <div class="d-flex flex-column align-items-center gap-2">
                              <label for="eau_consomme">Entrer votre eau consommée</label>
                              <input type="number" class="form-control text-center" id="eau_consomme" name="eau_consomme" min="0" step="100" required="" style="max-width: 500px;">
                              <button class="btn btn-primary btn-lg w-100 btnanime" type="submit" id="addEautBtn" style="max-width: 150px;">Ajouter</button>
                              <p id="messageEau" class="hidden text-center"></p>
                            </div>
                          </form>
                </div>
                  <?php     
                  if (isset($_GET['item'])) 
                  {
                  error_reporting(error_level: 0);
                  include("../../controleurs/ListeAlimentControleur.class.php");
                  include ("../../controleurs/AlimentControleur.class.php");
                  AlimentControleur::ajouterAliment();
                  }
                  ?>
                  <!--AFFICHAGE VALEURS NUTRITIVES TOTAL DE LA LISTE D'ALIMENT-->
                  <div class="card hidden" id="totalinfoCard">
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
              <!--FIN LISTE D'ALIMENT-->
            </div>
            <div class="product-device shadow-sm d-none d-md-block"></div>
            <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
            <!--FIN HEADLINE-->
          </div>
          <!--FIN CONTAINER-->
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
    <!--MESSAGE REPAS AJOUTÉ-->
              <script>
                addAlimentBtn.addEventListener("click", function () {
                  messageAliment.textContent = "Repas ajouté!";
                  messageAliment.classList.remove("hidden");
                  messageAliment.style.opacity = "1";

                  setTimeout(() => {
                    messageAliment.style.opacity = "0";
                    setTimeout(() => {
                      messageAliment.classList.add("hidden");
                    }, 500);
                  }, 1200);
                });
              </script>
               <!--MESSAGE LISTE ALIMENT AJOUTÉ-->
              <script>
                
                addListeAlimentBtn.addEventListener("click", function () {
                  event.preventDefault();
                  messageAliment.textContent = "Liste d'aliment sauvegardée...";
                  messageAliment.classList.remove("hidden");
                  messageAliment.style.opacity = "1";

                  setTimeout(() => {
                    messageAliment.style.opacity = "0";
                    setTimeout(() => {
                      messageAliment.classList.add("hidden");
                      ListeAlimentForm.submit();
                    }, 500);
                  }, 1500);
                });
              </script>
               <!--MESSAGE EAU CONSOMMÉE AJOUTÉ-->
              <script>
                
                addEautBtn.addEventListener("click", function () {
                  event.preventDefault();
                  messageEau.textContent = "Eau consommée ajoutée...";
                  messageEau.classList.remove("hidden");
                  messageEau.style.opacity = "1";

                  setTimeout(() => {
                    messageEau.style.opacity = "0";
                    setTimeout(() => {
                      messageEau.classList.add("hidden");
                      EauForm.submit();
                    }, 500);
                  }, 700);
                });
              </script>

   