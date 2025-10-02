<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../controleurs/RememberMeControleur.class.php');
require_once(__DIR__ . '/../../controleurs/ListeObjectifControleur.class.php');
require_once(__DIR__ . '/../../include/connexionBD.php');

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (isset($_COOKIE['rememberMe']) && !isset($_SESSION['user_id'])) {
  error_log("Tentative de reconnexion via cookie...");
  $user = RememberMeControleur::verifierToken($_COOKIE['rememberMe']);
  error_log("DEBUG USER FROM TOKEN: " . print_r($user, true));
  if ($user) {
    error_log("Utilisateur retrouvé via token : ID=" . $user['id']);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    exit;
  } else {
    error_log("Token invalide ou expiré : " . $_COOKIE['rememberMe']);
  }
}
$erreur_connexion = $_SESSION['erreur_connexion'] ?? null;
unset($_SESSION['erreur_connexion']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/connexion.css">
  <title>Connexion</title>
  <link rel="icon" href="../../images/logo.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
      0% {
        transform: translateX(0);
      }

      25% {
        transform: translateX(-5px);
      }

      50% {
        transform: translateX(5px);
      }

      75% {
        transform: translateX(-5px);
      }

      100% {
        transform: translateX(0);
      }
    }

    /* Animation 2 - Apparition en fondu avec glissement */
    .form-animated {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease;
    }

    .form-animated.visible {
      opacity: 1;
      transform: translateY(0);
    }

    #boite,
    #boite_mot_de_passe {
      padding: 10px;
      margin: 10px;
      background-color: #ffe6e6;
      border: 1px solid red;
      border-radius: 5px;
      margin: 10px 0;
    }

    #boite_connexion {
      padding: 10px;
      margin: 10px;
      background-color: rgb(230, 255, 230);
      border: 1px solid green;
      border-radius: 5px;
      margin: 10px 0;
    }
  </style>
</head>

<body>
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
              <a class="nav-link" aria-current="page" href="">Accueil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="">Aliments</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="">Objectifs</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="" data-bs-toggle="dropdown"
                aria-expanded="false">Statistiques</a>
              <ul class="dropdown-menu bg-dark">
                <li><a class="dropdown-item" href="" style="color:white;">Statistiques Personnelles</a></li>
                <li><a class="dropdown-item" href="" style="color:white;">Calendrier Hebdomadaire</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>

  <!--BLOC CENTRE-->
  <div class="bloc_centre_connexion" style="min-height: 705px;">
    <div class="container col-xl-10 col-xxl-8 px-4 py-5">
      <div class="row align-items-center g-lg-5 py-5">
        <!--BLOC CONNEXION-->
        <div class="col-lg-7 text-center text-lg-start">
          <h1 class="display-4 fw-bold lh-1 text-body-emphasis mb-3">Connexion</h1>
          <p class="col-lg-10 fs-4">Entrez votre nom ainsi que votre mot de passe dans la section de droite.</p>
        </div>
        <div class="col-md-10 mx-auto col-lg-5">

          <!--FORMULAIRE CONNEXION-->
          <form class="form-animated p-4 p-md-5 border rounded-3 bg-body-tertiary"
            action="../../index/index.php?action=Connexion" method="post">
            <div id="boite" style="display:none; color: red; text-align: center;">
              L'identifiant est invalide.
            </div>
            <!--BLOC MESSAGE MOT DE PASSE-->
            <div id="boite_mot_de_passe" style="display:none; color: red; text-align: center;">
              Le mot de passe est invalide.
            </div>
            <!--BLOC MESSAGE CONNEXION-->
            <div id="boite_connexion" style="display:none; color: green; text-align: center;">
              Connexion réussie!
            </div>
            <!--INPUT USERNAME-->
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="username" name="username" placeholder="Votre Nom" required>
              <label for="username">Nom d'utilisateur</label>
            </div>
            <!--INPUT MOT DE PASSE-->
            <div class="form-floating mb-3">
              <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe"
                required>
              <label for="password">Mot de passe</label>
              <span id="togglePwd" class="toggle"><i data-feather="eye"></i></span>
            </div>
            <!--REMEMBER ME-->
            <div class="checkbox mb-3">
              <input type="checkbox" name="remember_me" value="1">
              <label style="margin-left:5px;">Se souvenir</label>
            </div>
            <!--BOUTON CONNEXION-->
            <button class="w-100 btn btn-lg btn-primary" type="submit" id="loginBtn"
              onclick="return confirmerLogin()">Se connecter</button>
            <br>
            <p id="message" class="hidden"></p>
            <div>
              <button onclick="window.location.href='../page_creations/inscription.php'"
                class="btn btn-secondary w-100">Créer un Compte</button>
            </div>
            <hr class="my-4">
            <small class="text-body-secondary">En se connectant, vous accédez aux différentes fonctionnalités de
              l'application.</small>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php require_once '../../include/footer.php' ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      <?php if (!empty($erreur_connexion)): ?>
        //1 erreur nom d'utilisateur
        if ('<?php echo $erreur_connexion; ?>'.includes(1)) {
          //faire bouger le champ pour le nom d'utilisateur et le met en rouge
          $('#username').addClass('shake_erreur');
          setTimeout(() => $('#username').removeClass('shake_erreur'), 300);

          //met un boite qui dit mauvais nom d'utilisateur et disparait après 5 sec
          $('#boite').fadeIn('slow');
          setTimeout(function () {
            $('#boite').fadeOut('slow');
          }, 5000);
        }
        //2 erreur Mot de passe
        if ('<?php echo $erreur_connexion; ?>'.includes(2)) {
          //faire bouger le champ pour le mot de passe et le met en rouge
          $('#password').addClass('shake_erreur');
          setTimeout(() => $('#password').removeClass('shake_erreur'), 300);

          //met un boite qui dit mauvais mot de passe et disparait après 5 sec
          $('#boite_mot_de_passe').fadeIn('slow');
          setTimeout(function () {
            $('#boite_mot_de_passe').fadeOut('slow');
          }, 5000);
        }
      <?php endif; ?>
    });
  </script>
  <?php if (!empty($erreur_connexion) && $erreur_connexion == 3): ?><!--3 veux dire que la connexion est réussit-->
    <script>
      $(document).ready(function () {

        //faire bouger les champs et les mets en verts
        $('#username').addClass('shake_bon');
        setTimeout(() => $('#username').removeClass('shake_bon'), 300);
        $('#password').addClass('shake_bon');
        setTimeout(() => $('#password').removeClass('shake_bon'), 300);

        //affiche connexion réussite
        $('#boite_connexion').fadeIn('slow');

        //sleep pendant 2sec
        setTimeout(function () {
          window.location.href = "../page_accueil/accueil.php";
        }, 2000);
      });
    </script>
  <?php endif; ?>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const passwordInput = document.getElementById("password");
      togglePwd.addEventListener("click", () => {
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
      });

      // Déclencher animation formulaire
      const form = document.querySelector(".form-animated");
      setTimeout(() => form.classList.add("visible"), 100);
    });
  </script>
  <script src="https://unpkg.com/feather-icons"></script>
  <script>feather.replace();</script>
</body>

</html>