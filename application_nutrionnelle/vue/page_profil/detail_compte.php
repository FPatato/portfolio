<!--PROGRESSION CONTROLEUR 0%-->

<?php
session_start();
require_once '../Page_connexion/connexion_base_de_donnée.php';
require_once '../../include/souvenirConnexion.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../page_de_connexion_ou_inscription.php');
    exit;
}

require_once(__DIR__ . "/../../include/connexionBD.php");
require_once(__DIR__ . "/../../controleurs/UtilisateurControleur.class.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/profil.css">
    <title>Détail du compte</title>
    <link rel="icon" href="../../images/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
      .position-relative {
      position: relative;
      }

      .toggle {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 1.2rem;
        color: #6c757d;
        z-index: 10;
      }

      .shake_erreur {
        animation: shake 0.3s ease-in-out 0s 3;
        border: 2px solid red !important;
      }

      .shake_bon {
        animation: shake 0.3s ease-in-out 0s 3;
        border: 2px solid green !important;
      }

      @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
        100% { transform: translateX(0); }
      }

      #boite_erreur, #boite_erreur_nom, #boite_erreur_mot, #boite_erreur_mot2 {
        padding: 10px;
        margin: 10px;
        background-color:  #ffe6e6;
        border: 1px solid red;
        border-radius: 5px;
        margin: 10px 0;
      }

      #boite_connexion {
        padding: 10px;
        margin: 10px;
        background-color:rgb(230, 255, 230);
        border: 1px solid green;
        border-radius: 5px;
        margin: 10px 0;
      }
    </style>
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
                    <li><a class="dropdown-item" href="../pages_statistiques/page_statistiques_personnelles/statistique.php" style="color:white;" >Statistiques Personnelles</a></li>
                    <li><a class="dropdown-item" href="../pages_statistiques/page_calendrier/calendrier.php" style="color:white;">Calendrier Hebdomadaire</a></li>
                  </ul>
                </li>
              </ul>

                <!--Profil et bouton déconnexion-->
                <h1 class="ligne">
                  <span style="display: flex; align-items: center; gap: 10px;">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      <li class="nav-item dropdown">
                        <a class="navtitle dropdown-toggle navlinkactive" href="" data-bs-toggle="dropdown" aria-expanded="false" style="color: gray; text-decoration:none;" id="welcomeMessage">
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
          <div class="bloc_gauche bg-dark">
          <?php require_once '../../include/menuprofil.php'?>
          </div>
          <!--FORMULAIRE DÉTAILS DU COMPTE-->
          <div class="formprofil col-md-7 col-lg-8">
            <h4 class="mb-3">Détails du compte :</h4>
              <div id="messageConfirmation" class="hidden alert alert-success">
                  Informations confidentiels modifiées.
              </div>
            <form class="needs-validation" method="POST" action="../../index/index.php?action=ModifierMotDePasse">
              <div class="row g-3">
                <!--NOM D'UTILISATEUR-->
                <div class="col-12">
                    <label for="username" class="form-label">Nom d'utilisateur : <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>
                    </label>
                    <div id="boite_erreur" style="color:red; <?php echo empty($_SESSION['messageUser']) ? 'display:none;' : ''; ?> text-align: center;">
                  <?php 
                    if (!empty($_SESSION['messageUser'])) {
                      echo $_SESSION['messageUser'];
                    }
                  ?>
                </div>
                    <div class="input-group has-validation">
                      <input type="text" class="form-control" id="username" name="username" placeholder="Nom d'utilisateur" required="">
              <script>
                  async function checkUsername(username) {
                      try {
                          let response = await fetch("../../index/index.php?action=CheckUsername", {
                              method: "POST",
                              headers: {
                                  "Content-Type": "application/x-www-form-urlencoded",
                              },
                              body: "Nusername=" + encodeURIComponent(username),
                          });

                          if (!response.ok) throw new Error("Erreur HTTP " + response.status);

                          let data = await response.text();
                          return data.trim() === "true";
                      } catch (err) {
                          console.error("Erreur:", err);
                      }
                  }
                  window.onload = function () {
                      let isTyping = false;
                      let stopTypingTimeout;

                      document.addEventListener("keydown", () => {
                          isTyping = true;
                          clearTimeout(stopTypingTimeout);
                          stopTypingTimeout = setTimeout(() => {
                              isTyping = false;
                          }, 1000);
                      });

                      document.addEventListener("keyup", () => {
                          clearTimeout(stopTypingTimeout);
                          stopTypingTimeout = setTimeout(() => {
                              isTyping = false;
                          }, 1000);
                      });
                  setInterval(async () => {
                      const selectUsername = document.getElementById("username");
                      const username = selectUsername.value;
                      if (username === "") {
                              document.getElementById("boite_erreur").style.display = "none";
                              selectUsername.classList.remove("shake_erreur", "shake_bon");
                              return;
                          }

                      if (!isTyping) {
                          if (username.length < 4) {
                              selectUsername.classList.add("shake_erreur");
                              selectUsername.classList.remove("shake_bon");
                              document.getElementById("boite_erreur").style.display = "block";
                              document.getElementById("boite_erreur").innerText = "Le nom d'utilisateur doit contenir au moins 4 caractères.";
                          } else {
                              const isAvailable = await checkUsername(username);
                              if (!isAvailable) {
                                  selectUsername.classList.add("shake_erreur");
                                  selectUsername.classList.remove("shake_bon");
                                  document.getElementById("boite_erreur").style.display = "block";
                                  document.getElementById("boite_erreur").innerText = "Nom d'utilisateur déjà pris.";
                              } else {
                                  selectUsername.classList.remove("shake_erreur");
                                  selectUsername.classList.add("shake_bon");
                                  document.getElementById("boite_erreur").style.display = "none";
                              }
                          }
                      }
                  }, 500);
              };
          </script>

                    </div>
                </div>
                <!--ANCIEN MOT DE PASSE-->
                <div class="col-12">
                <h4 class="mb-3">Modifier le mot de passe :</h4>
                    <label for="oldpassword" class="form-label">Saisir l'ancien mot de passe :</label>
                    <div class="input-group has-validation">
                      <input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="Ancien mot de passe" required="">
                      <span id="toggleOldPwd" class="toggle"><i data-feather="eye"></i></span>
                    </div>
                </div>
                <!--NOUVEAU MOT DE PASSE 1-->
                <div class="col-12">
                    <label for="password1" class="form-label">Saisir le nouveau mot de passe :</label>
                    <div id="boite_erreur_mot" style="color:red; display:none; text-align: center;">Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un symbole.</div>

                    <div class="input-group has-validation">
                      <input type="password" class="form-control" id="password1" name="password1" placeholder="Nouveau mot de passe" required="">
                      <span id="togglePwd1" class="toggle"><i data-feather="eye"></i></span>
                    </div>
                </div>
                <!--NOUVEAU MOT DE PASSE 2-->
                <div class="col-12">
                    <label for="password2" class="form-label">Resaisir le nouveau mot de passe :</label>
                    <div id="boite_erreur_mot2" style="color:red; display:none; text-align: center;">Les mots de passe ne correspondent pas.</div>
                    <div class="input-group has-validation">
                      <input type="password" class="form-control" id="password2" name="password2" placeholder="Nouveau mot de passe" required="">
                      <span id="togglePwd2" class="toggle"><i data-feather="eye"></i></span>
                    </div>
                </div>
                
              </div>
              <hr class="my-4">
              <!--BOUTON SOUMETTRE-->
              <button class="w-100 btn btn-dark btn-lg btnanime" type="submit" style="max-width: 400px; display: block; margin: 0 auto;">Modifier</button>
            </form>
            <!--FIN DIV POUR LE FORM-->
          </div>
          <!--FIN BLOC CENTRE-->
      </div>
      <?php require_once '../../include/footer.php'?>
      </div> 
      <!--FIN BLOC PRINCIPAL-->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const oldpassword = document.getElementById("oldpassword");
      const toggleOldPwd = document.getElementById("toggleOldPwd");

      const password1 = document.getElementById("password1");
      const togglePwd1 = document.getElementById("togglePwd1");

      const password2 = document.getElementById("password2");
      const togglePwd2 = document.getElementById("togglePwd2");

      toggleOldPwd.addEventListener("click", () => {
        oldpassword.type = oldpassword.type === "password" ? "text" : "password";
      });

      togglePwd1.addEventListener("click", () => {
        password1.type = password1.type === "password" ? "text" : "password";
      });

      togglePwd2.addEventListener("click", () => {
        password2.type = password2.type === "password" ? "text" : "password";
      });
    });
  </script>

<script>
    $(document).ready(function() {
      $('form').on('submit', function(e) {
        e.preventDefault();
        let formulaireValide = true;
        $('.message-erreur').hide().text('');

        // Récupérer les valeurs
        const nomUtilisateur = $('#username').val().trim();
        const motDePasse = $('#password1').val();
        const confirmationMotDePasse = $('#password2').val();

        // Nom d'utilisateur
        if (nomUtilisateur.length < 4) {
          $('#boite_erreur_nom').fadeIn('slow');
          setTimeout(function () {
            $('#boite_erreur_nom').fadeOut('slow');
          }, 5000);
          $('#username').addClass('shake_erreur');
          setTimeout(() => $('#username').removeClass('shake_erreur'), 300);
          formulaireValide = false;
        }
        const boite = $('#boite_erreur');
        if (boite.length && boite.is(':visible') && boite.text().trim() !== "") {
          setTimeout(function () {
            boite.fadeOut('slow');
          }, 5000);
          <?php unset($_SESSION['messageUser']);?>
        }
        // Mot de passe
        if (!motDePasseEstFort(motDePasse)) {
          $('#regles-mdp').slideDown();
          $('#boite_erreur_mot').fadeIn('slow');
          setTimeout(function () {
            $('#boite_erreur_mot').fadeOut('slow');
          }, 5000);
          $('#password1').addClass('shake_erreur');
          setTimeout(() => $('#password1').removeClass('shake_erreur'), 300);
          formulaireValide = false;
        }
        // Confirmation du mot de passe
        if (motDePasse !== confirmationMotDePasse) {
          $('#boite_erreur_mot2').fadeIn('slow');
          setTimeout(function () {
            $('#boite_erreur_mot2').fadeOut('slow');
          }, 5000);
          $('#password2').addClass('shake_erreur');
          setTimeout(() => $('#password2').removeClass('shake_erreur'), 300);
          formulaireValide = false;
        }


        // Bloquer la soumission s'il y a une erreur
        if (!formulaireValide) {
          return;
        }

        // Confirmation de l'utilisateur
        const confirmation = confirm("Vous allez modifier votre mot de passe. 'OK' pour confirmer");
        if (!confirmation) {
          return;
        }

        // Afficher le message de succès
        const messageConfirmation = $('#messageConfirmation');
        messageConfirmation.removeClass('hidden').css('opacity', 1);

        setTimeout(() => {
          messageConfirmation.css('opacity', 0);
          setTimeout(() => {
            messageConfirmation.addClass('hidden');
            $('form')[0].submit();
          }, 500);
        }, 1000);
      });

      function afficherErreur(selecteur, message) {
        $(selecteur)
          .stop(true, true)
          .hide()
          .text(message)
          .slideDown();
      }

      // Format du mot de passe valide
      function motDePasseEstFort(motDePasse) {
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        return regex.test(motDePasse);
      }
    });
  </script>

<!--Icone de l'Oeuil-->
  <script src="https://unpkg.com/feather-icons">
  </script>
  <script>
    feather.replace();
  </script>

</body>
</html>
<script>
function confirmerLogout() {
  const confirmer = confirm("Vous allez être déconnecté. Continuer ?");
  return confirmer;
}
</script>