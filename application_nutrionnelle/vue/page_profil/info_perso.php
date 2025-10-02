<!--PROGRESSION CONTROLEUR 0%-->
<?php
require_once(__DIR__ . "/../../include/connexionBD.php");
require_once(__DIR__ . "/../../DAO/InformationsPersonnellesDAO.class.php");
require_once(__DIR__ . "/../../controleurs/InformationsPersonnellesControleur.class.php");
require_once(__DIR__ . "/../../modeles/InformationsPersonnelles.class.php");
require_once(__DIR__ . "/../../DAO/UtilisateurDAO.class.php");
require_once '../Page_connexion/connexion_base_de_donnée.php';
require_once '../../include/souvenirConnexion.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../page_de_connexion_ou_inscription.php');
    exit;
}

if (empty($_SESSION['messageInfoPerso'])) {
  $_SESSION['messageInfoPerso'] = "";
}

$currentUserInfo = InformationsPersonnellesControleur::getInformationsPersonnelles();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/profil.css">
  <title>Informations Personnelles</title>
  <link rel="icon" href="../../images/logo.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
  @keyframes shake {
    0% { transform: translateX(0); }
    20% { transform: translateX(-5px); }
    40% { transform: translateX(5px); }
    60% { transform: translateX(-5px); }
    80% { transform: translateX(5px); }
    100% { transform: translateX(0); }
  }
  .shake_erreur {
    border-color: red !important;
    animation: shake 0.3s;
  }
  .shake_bon {
    border-color: green !important;
  }

  #boite_email, #boite_tel {
    display: none;
    color: red;
    padding: 10px;
    margin: 10px;
    background-color:  #ffe6e6;
    border: 1px solid red;
    border-radius: 5px;
    margin: 10px 0;
  }
</style>

<script>
  async function checkEmail(email) {
    const response = await fetch("../../index/index.php?action=CheckEmail", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "Nemail=" + encodeURIComponent(email),
    });
    const data = await response.text();
    return data.trim() === "true"; // true = email dispo
  }

  window.addEventListener("DOMContentLoaded", () => {
    let typing = false;
    let stopTypingTimeout;

    const emailInput = document.getElementById("courriel");

    // Création dynamique de la boite email sous le champ si pas déjà présente
    let boiteEmail = document.getElementById("boite_email");
    if (!boiteEmail) {
      boiteEmail = document.createElement("div");
      boiteEmail.id = "boite_email";
      emailInput.insertAdjacentElement("afterend", boiteEmail);
    }

    document.addEventListener("keydown", () => {
      typing = true;
      clearTimeout(stopTypingTimeout);
      stopTypingTimeout = setTimeout(() => {
        typing = false;
      }, 1000);
    });

    document.addEventListener("keyup", () => {
      clearTimeout(stopTypingTimeout);
      stopTypingTimeout = setTimeout(() => {
        typing = false;
      }, 1000);
    });

    setInterval(async () => {
      if (!emailInput) return;

      const email = emailInput.value.trim();

      if (email.length === 0) {
        emailInput.classList.remove("shake_erreur");
        emailInput.classList.remove("shake_bon");
        boiteEmail.style.display = "none"; 
        boiteEmail.innerText = "";
        return;
      }

      if (!typing) {
        if (email.length < 6 || !email.includes("@") || !email.includes(".")) {
          emailInput.classList.add("shake_erreur");
          emailInput.classList.remove("shake_bon");
          boiteEmail.style.display = "block";
          boiteEmail.innerText =
            "L'email doit contenir au moins 6 caractères,\ndoît inclure un '@' et un '.'";
          updateSubmitButton();
          return;
        }

        const isAvailable = await checkEmail(email);
        if (!isAvailable) {
          emailInput.classList.add("shake_erreur");
          emailInput.classList.remove("shake_bon");
          boiteEmail.style.display = "block";
          boiteEmail.innerText = "Email déjà pris.";
        } else {
          emailInput.classList.remove("shake_erreur");
          emailInput.classList.add("shake_bon");
          boiteEmail.style.display = "none";
          boiteEmail.innerText = "";
        }
        updateSubmitButton();
      }
    }, 2000);
  });
</script>
<script>
  function updateSubmitButton() {
    const telInput = document.getElementById("telephone");
    const submitButton = document.getElementById("submitButton");

    const telError = telInput.classList.contains("shake_erreur");

    submitButton.disabled = telError;
  }
  async function checkTelephone(telephone) {
  const response = await fetch("../../index/index.php?action=CheckTelephone", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "Ntelephone=" + encodeURIComponent(telephone),
  });
  const data = await response.text();
  return data.trim() === "true";
  }

  window.addEventListener("DOMContentLoaded", () => {
    let typingTel = false;
    let stopTypingTimeoutTel;

    const telInput = document.getElementById("telephone");

    let boiteTel = document.getElementById("boite_tel");
    if (!boiteTel) {
      boiteTel = document.createElement("div");
      boiteTel.id = "boite_tel";
    }

    telInput.addEventListener("keydown", () => {
      typingTel = true;
      clearTimeout(stopTypingTimeoutTel);
    });

    telInput.addEventListener("keyup", () => {
      clearTimeout(stopTypingTimeoutTel);
      stopTypingTimeoutTel = setTimeout(() => {
        typingTel = false;
      }, 1000);
    });

    setInterval(async () => {
      if (!telInput) return;

      const telephone = telInput.value.trim();

      if (telephone.length === 0) {
        telInput.classList.remove("shake_erreur");
        telInput.classList.remove("shake_bon");
        boiteTel.style.display = "none";
        boiteTel.innerText = "";
        updateSubmitButton();
        return;
      }

      const phoneRegex = /^\(\d{3}\)\s?\d{3}-\d{4}$/;

      if (!typingTel) {
        if (!phoneRegex.test(telephone)) {
          telInput.classList.add("shake_erreur");
          telInput.classList.remove("shake_bon");
          boiteTel.style.display = "block";
          boiteTel.innerText = "Numéro invalide. Format requis : (XXX) XXX-XXXX";
          updateSubmitButton();
          return;
        }

        const isAvailable = await checkTelephone(telephone);
        if (!isAvailable) {
          telInput.classList.add("shake_erreur");
          telInput.classList.remove("shake_bon");
          boiteTel.style.display = "block";
          boiteTel.innerText = "Ce numéro de téléphone est déjà utilisé.";
        } else {
          telInput.classList.remove("shake_erreur");
          telInput.classList.add("shake_bon");
          boiteTel.style.display = "none";
          boiteTel.innerText = "";
        }
        updateSubmitButton();
      }
    }, 2000);
  });
</script>
<script>
function updateSubmitButton() {
  const emailInput = document.getElementById("courriel");
  const telInput = document.getElementById("telephone");
  const submitButton = document.getElementById("addInformationBtn");

  const emailError = emailInput.classList.contains("shake_erreur");
  const telError = telInput.classList.contains("shake_erreur");

  submitButton.disabled = emailError || telError;
}
</script>

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
    <div class="bloc_centre">
      <!--BLOC GAUCHE-->
      <div class="bloc_gauche bg-dark">
        <?php require_once '../../include/menuprofil.php' ?>
      </div>
      <!--FORMULAIRE INFORMATIONS PERSONNELLES-->
      <div class="formprofil col-md-7 col-lg-8">
        <!--TITRE FORMULAIRE-->
        <h4 class="mb-3">Informations personnelles :</h4>
        <!-- Message de confirmation -->
        <?php if (!empty($_SESSION['messageInfoPerso'])): ?>
          <div id="messageConfirmation" class="alert alert-success show">
            <?php echo $_SESSION['messageInfoPerso']; ?>
          </div>
          <?php $_SESSION['messageInfoPerso'] = ""; ?>
        <?php endif; ?>
        <form class="needs-validation" action="../../index/index.php?action=ModifierInfoPerso" method="POST">
          <div class="row g-3">
            <?php if (!empty($erreur_user)) : ?>
                <div style="background-color:#FFFFFF; border-radius: 10px; text-align: center; color:red; border: 2px solid red; padding-bottom: 4px; padding-top: 4px;">
            <?php echo $erreur_user; ?>
            </div><br><?php endif; ?>
            <!--PRÉNOM-->
            <div class="col-sm-4">
              <label for="prenom" class="form-label">Prénom : <b><?php echo $currentUserInfo['prenom']; ?></b></label>
              <input type="text" class="form-control" id="prenom" name="prenom">
            </div>
            <!--NOM-->
            <div class="col-sm-4">
              <label for="nom" class="form-label">Nom : <b><?php echo $currentUserInfo['nom']; ?></b></label>
              <input type="text" class="form-control" id="nom" name="nom">
            </div>
            <!--SEXE-->
            <div class="col-sm-4">
              <label for="sexe" class="form-label">Sexe : <b><?php echo $currentUserInfo['sexe']; ?></b></label>
              <select class="form-select" id="sexe" name="sexe">
                <option value="">Choisir...</option>
                <option>Homme</option>
                <option>Femme</option>
              </select>
            </div>
            <!--COURRIEL-->
            <div class="col-4">
              <label for="courriel" class="form-label">Email : <b><?php echo $currentUserInfo['courriel']; ?></b></label>
              <input type="email" class="form-control" id="courriel" name="courriel">
              <div class="message-erreur" id="erreur-courriel" style="color:red; display:none;"></div>
            </div>
            <!--ADRESSE-->
            <div class="col-4">
              <label for="adresse" class="form-label">Adresse : <b><?php echo $currentUserInfo['adresse']; ?></b></label>
              <input type="text" class="form-control" id="adresse" name="adresse">
            </div>
            <!--TELEPHONE-->
            <div class="col-4">
              <label for="telephone" class="form-label">Téléphone : <b><?php echo $currentUserInfo['telephone']; ?></b></label>
              <input type="text" class="form-control" id="telephone" name="telephone">
              <div id="boite_tel"></div>
            </div>
            <!--PAYS-->
            <div class="col-md-4">
              <label for="pays" class="form-label">Pays : <b><?php echo $currentUserInfo['pays']; ?></b></label>
              <select class="form-select" id="pays" name="pays">
                <option value="">Choisir...</option>
                <option>Canada</option>
              </select>
            </div>
            <!--PROVINCE-->
            <div class="col-md-4">
              <label for="province" class="form-label">Province : <b><?php echo $currentUserInfo['province']; ?></b></label>
              <select class="form-select" id="province" name="province">
                <option value="">Choisir...</option>
                <option>Québec</option>
              </select>
            </div>
            <!--CODE POSTAL-->
            <div class="col-md-4">
              <label for="zip" class="form-label">Code postal : <b><?php echo $currentUserInfo['zip']; ?></b></label>
              <input type="text" class="form-control" id="zip" name="zip">
            </div>
          </div>
          <hr class="my-4">
          <div class="row gy-3">
            <!--AGE-->
            <div class="col-md-3">
              <label for="age" class="form-label">Age : <b><?php echo $currentUserInfo['age']; ?></b></label>
              <input type="number" class="form-control" id="age" name="age">
              <div class="message-erreur" id="erreur-age" style="color:red; display:none;"></div>
            </div>
            <!--POIDS-->
            <div class="col-md-3">
              <label for="poids" class="form-label">Poids : <b><?php echo "{$currentUserInfo['poids']}kg"; ?></b></label>
              <input type="number" class="form-control" id="poids" name="poids" placeholder="<?php echo "{$currentUserInfo['poids']}"; ?>">
              <div class="message-erreur" id="erreur-poids" style="color:red; display:none;"></div>
            </div>
            <!--TAILLE-->
            <div class="col-md-3">
              <label for="taille" class="form-label">Taille : <b><?php echo "{$currentUserInfo['taille']}cm"; ?></b></label>
              <input type="number" class="form-control" id="taille" name="taille" placeholder="<?php echo "{$currentUserInfo['taille']}"; ?>">
              <div class="message-erreur" id="erreur-taille" style="color:red; display:none;"></div>
            </div>
            <!--NIVEAU ACTIVITE-->
            <div class="col-md-3">
              <label for="niv_activite" class="form-label">Niveau d'activité : <b><?php echo $currentUserInfo['niv_activite']; ?></b></label>
              <select class="form-select" id="niv_activite" name="niv_activite">
                <option value="">Choisir...</option>
                <option>Sédentaire</option>
                <option>Modéré</option>
                <option>Actif</option>
              </select>
            </div>
          </div>
          <p class="imc">IMC : <b><?php echo InformationsPersonnellesControleur::calculIMC(); ?></b></p>
          <hr class="my-4">

          <!--BOUTON SOUMETTRE-->
          <button class="w-100 btn btn-dark btn-lg btnanime" type="submit" id="addInformationBtn" style="max-width: 400px; display: block; margin: 0 auto;">Modifier</button>
          <?php echo $_SESSION['messageInfoPerso']?>
        </form>
      </div>
      <!--FIN BLOC CENTRE-->
    </div>
    <?php require_once '../../include/footer.php' ?>
  </div>
  <!--FIN BLOC PRINCIPAL-->
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

<script>
  window.addEventListener("DOMContentLoaded", () => {
    const message = document.getElementById("messageConfirmation");
    if (message && message.textContent.trim() !== "") {
      message.classList.remove("hidden");
      message.style.opacity = "1";
      // apparition et disparition
      setTimeout(() => {
        message.style.opacity = "0";
        setTimeout(() => {
          message.style.display = "none";
        }, 500);
      }, 1500);
    }
  });
</script>

<script>
  $(document).ready(function () {
      $('form').on('submit', function (e) {
        e.preventDefault();
        let formulaireValide = true;
        $('.message-erreur').hide().text('');
        // Récupérer les valeurs
        const courriel = $('#courriel').val().trim();
        const age = parseInt($('#age').val());
        const poids = parseFloat($('#poids').val());
        const taille = parseFloat($('#taille').val());
        
        // Email
        if (courriel !== '') {
          if (!validerCourriel(courriel)) {
              afficherErreur('#erreur-courriel', "L'adresse email n'est pas valide.");
              formulaireValide = false;
          }
        }
        // Âge
        if ($('#age').val().trim() !== '') {
          if (isNaN(age) || age < 10 || age > 100) {
              afficherErreur('#erreur-age', "L'âge doit être compris entre 10 et 100 ans.");
              formulaireValide = false;
          }
        }
        // Poids
        if ($('#poids').val().trim() !== '') {
          if (isNaN(poids) || poids < 30 || poids > 300) {
              afficherErreur('#erreur-poids', "Le poids doit être entre 30 kg et 300 kg.");
              formulaireValide = false;
          }
        }
        // Taille
        if ($('#taille').val().trim() !== '') {
          if (isNaN(taille) || taille < 100 || taille > 300) {
              afficherErreur('#erreur-taille', "La taille doit être entre 100 cm et 250 cm.");
              formulaireValide = false;
          }
        }
        // Bloquer la soumission si erreur
        if (!formulaireValide) {
            return;
        }

        // Confirmation de la modif
        const confirmation = confirm("Vous allez modifier vos informations personnelles. 'OK' pour confirmer");
        if (!confirmation) {
            return;
        }

        // Afficher message de succès
        const messageConfirmation  = $('#messageConfirmation');
        messageConfirmation.removeClass('hidden').css('opacity', 1);

        setTimeout(() => {
            messageConfirmation.css('opacity', 0);
            setTimeout(() => {
                messageConfirmation.addClass('hidden');
                $('form')[0].submit();
            });
        });
    });

      function afficherErreur(selecteur, message) {
          $(selecteur)
              .stop(true, true)
              .hide()
              .text(message)
              .slideDown();
      }
      // Format du courriel
      function validerCourriel(courriel) {
          const expression = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          return expression.test(courriel);
      }
    });
  </script>

<script>
function confirmerLogout() {
  const confirmer = confirm("Vous allez être déconnecté. Continuer ?");
  return confirmer;
}
</script>