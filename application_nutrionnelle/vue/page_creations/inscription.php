<!--PROGRESSION CONTROLEUR 75%-->
<?php
require_once(__DIR__ . "/../../modeles/Utilisateur.class.php");
require_once(__DIR__ . "/../../modeles/InformationsPersonnelles.class.php");
require_once(__DIR__ . "/../../modeles/Historique.class.php");
require_once(__DIR__ . "/../../DAO/UtilisateurDAO.class.php");
require_once(__DIR__ . "/../../DAO/InformationsPersonnellesDAO.class.php");
require_once(__DIR__ . "/../../DAO/HistoriqueDAO.class.php");
require_once(__DIR__ . "/../../include/connexionBD.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$message = '';
$info = [];

$erreur_inscription = $_SESSION['erreur_inscription'] ?? null;

unset($_SESSION['erreur_inscription']);

$donnees = $_SESSION['donnees_formulaire'] ?? [];
unset($_SESSION['donnees_formulaire']);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription</title>
    <link rel="icon" href="../../images/logo.png" type="image/png">
    <link rel="stylesheet" href="../../css/accueil.css" />
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

        #regles-mdp li {
            list-style: none;
            margin-bottom: 4px;
            font-weight: bold;
        }

        .valide {
            color: green;
        }

        .invalide {
            color: red;
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

        #boite_user,
        #boite_email,
        #boite_tel {
            display: none;
            text-align: center;
            color: red;
            padding: 10px;
            margin: 10px;
            background-color: #ffe6e6;
            border: 1px solid red;
            border-radius: 5px;
            margin: 10px 0;
        }

        #boite_confirmation {
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

    <!-- BLOC HAUT - NAVBAR -->
    <div class="bloc_haut">
        <!--NAVBAR-->
        <nav class="navbar navbar-expand-md bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href=""><img src="../../images/logo.png" style="height: 60px;" /></a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="">Aliments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="">Objectifs</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href=""
                                data-bs-toggle="dropdown">Statistiques</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="">Statistiques Personnelles</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="">Calendrier Hebdomadaire</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- BLOC CENTRE - FORMULAIRE -->
    <div class="bloc_centre_connexion">
        <div class="container col-xl-10 col-xxl-8 px-4 py-5">
            <div class="row align-items-center g-lg-5 py-5">

                <!-- TEXTE À GAUCHE -->
                <div class="col-lg-6 text-center text-lg-start">
                    <!--TITRE GAUCHE-->
                    <h1 class="display-4 fw-bold text-body-emphasis mb-3">Inscription</h1>
                    <!--TEXTE DU GAUCHE-->
                    <p class="col-lg-10 fs-4">
                        Remplissez le formulaire pour créer votre compte personnel,
                        qui vous donnera accès à toutes les fonctionnalités !
                    </p>
                </div>

                <!-- FORMULAIRE À DROITE -->
                <div class="col-md-10 mx-auto col-lg-6">
                    <!--FORMULAIRE-->
                    <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary"
                        action="../../index/index.php?action=ajoutUtilisateur" method="POST">
                        <!--FORMULAIRE NOM D'UTILISATEUR-->
                        <div id="boite_user">
                        </div>
                        <script>
                            async function checkUsername(username) {
                                try {
                                    let response = await fetch("../../index/index.php?action=CheckUsername", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/x-www-form-urlencoded",
                                        },
                                        body: "Nusername=" + (username),
                                    });

                                    if (!response.ok) throw new Error("Erreur HTTP " + response.status);

                                    let data = await response.text();
                                    return data.trim() === "true";
                                } catch (err) {
                                    console.error("Erreur:", err);
                                }
                            }
                            async function checkEmail(email) {
                                try {
                                    let response = await fetch("../../index/index.php?action=CheckEmail", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/x-www-form-urlencoded",
                                        },
                                        body: "Nemail=" + (email),
                                    });

                                    if (!response.ok) throw new Error("Erreur HTTP " + response.status);

                                    let data = await response.text();
                                    return data.trim() === "true";
                                } catch (err) {
                                    console.error("Erreur:", err);
                                }
                            }
                            async function checkTelephone(telephone) {
                                try {
                                    let response = await fetch("../../index/index.php?action=CheckTelephone", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/x-www-form-urlencoded",
                                        },
                                        body: "Ntelephone=" + encodeURIComponent(telephone),
                                    });

                                    if (!response.ok) throw new Error("Erreur HTTP " + response.status);

                                    let data = await response.text();
                                    return data.trim() === "true"; // true = disponible
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
                                    const selectEmail = document.getElementById("email");
                                    const email = selectEmail.value;
                                    const selectTel = document.getElementById("telephone");
                                    const telephone = selectTel.value;

                                    if (!isTyping) {
                                        if (username) {
                                            if (username.length < 4) {
                                                selectUsername.classList.add("shake_erreur");
                                                selectUsername.classList.remove("shake_bon");
                                                document.getElementById("boite_user").style.display = "block";
                                                document.getElementById("boite_user").innerText = "Le nom d'utilisateur doit contenir au moins 4 caractères.";
                                            } else {
                                                const isAvailable = await checkUsername(username);
                                                if (!isAvailable) {
                                                    selectUsername.classList.add("shake_erreur");
                                                    selectUsername.classList.remove("shake_bon");
                                                    document.getElementById("boite_user").style.display = "block";
                                                    document.getElementById("boite_user").innerText = "Nom d'utilisateur déjà pris.";
                                                } else {
                                                    selectUsername.classList.remove("shake_erreur");
                                                    selectUsername.classList.add("shake_bon");
                                                    document.getElementById("boite_user").style.display = "none";
                                                }
                                            }
                                        }
                                    }
                                    if (!isTyping) {
                                        if (email) {
                                            if (email.length > 6 && email.includes("@") && email.includes(".")) {
                                                const isEmailAvailable = await checkEmail(email);
                                                if (!isEmailAvailable) {
                                                    selectEmail.classList.add("shake_erreur");
                                                    selectEmail.classList.remove("shake_bon");
                                                    document.getElementById("boite_email").style.display = "block";
                                                    document.getElementById("boite_email").innerText = "Courriel déjà utilisé.";
                                                } else {
                                                    selectEmail.classList.remove("shake_erreur");
                                                    selectEmail.classList.add("shake_bon");
                                                    document.getElementById("boite_email").style.display = "none";
                                                }
                                            }
                                            else {
                                                selectEmail.classList.add("shake_erreur");
                                                selectEmail.classList.remove("shake_bon");
                                                document.getElementById("boite_email").style.display = "block";
                                                document.getElementById("boite_email").innerText = "L'email doit contenir au moins 6 caractères,\ndoît inclure un '@' et un '.'";
                                            }
                                        }
                                    }
                                    if(!isTyping)
                                    {
                                        if (telephone) {
                                            const phoneRegex = /^\(\d{3}\)\s?\d{3}-\d{4}$/;
                                            if (!phoneRegex.test(telephone)) {
                                                selectTel.classList.add("shake_erreur");
                                                selectTel.classList.remove("shake_bon");
                                                document.getElementById("boite_tel").style.display = "block";
                                                document.getElementById("boite_tel").innerText = "Numéro invalide. Format requis : (XXX) XXX-XXXX";
                                            } else {
                                                const isAvailable = await checkTelephone(telephone);
                                                if (!isAvailable) {
                                                    selectTel.classList.add("shake_erreur");
                                                    selectTel.classList.remove("shake_bon");
                                                    document.getElementById("boite_tel").style.display = "block";
                                                    document.getElementById("boite_tel").innerText = "Numéro déjà utilisé.";
                                                } else {
                                                    selectTel.classList.remove("shake_erreur");
                                                    selectTel.classList.add("shake_bon");
                                                    document.getElementById("boite_tel").style.display = "none";
                                                }
                                            }
                                        }
                                    }
                                }, 2000);
                            };
                        </script>

                       
                        <div id="boite_confirmation" style="display:none; color: green; text-align: center;">
                            Compte utilisateur créé
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="username"
                                placeholder="Nom d'utilisateur" required />
                            <label for="username">Nom d'utilisateur</label>
                        </div>

                        <!--FORMULAIRE MOT DE PASSE-->
                        <div class="form-floating mb-3 position-relative">
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Mot de passe" required />
                            <label for="password">Mot de passe</label>
                            <span id="togglePassword" class="toggle"><i data-feather="eye"></i></span>
                        </div>
                        <div class="message-erreur" id="erreur-password" style="color:red; display:none;"></div>

                        <ul id="regles-mdp" class="message-erreur" style="display: none; color: red; margin-top: 10px;">
                            <li id="regle-longueur">Au moins 8 caractères</li>
                            <li id="regle-majuscule">Une lettre majuscule</li>
                            <li id="regle-minuscule">Une lettre minuscule</li>
                            <li id="regle-chiffre">Un chiffre</li>
                            <li id="regle-special">Un caractère spécial</li>
                        </ul>


                        <!--FORMULAIRE CONFIRMATION DU MOT DE PASSE-->
                        <div class="form-floating mb-3">
                            <input type="password" name="confirm_password" class="form-control" id="confirm_password"
                                placeholder="Confirmer le mot de passe" required />
                            <label for="confirm_password">Confirmer le mot de passe</label>
                            <div class="message-erreur" id="erreur-confirmation-password"
                                style="color:red; display:none;"></div>
                            <span id="toggleConfirmPassword" class="toggle"><i data-feather="eye"></i></span>
                        </div>

                        <!--FORMULAIRE PRENOM-->
                        <div class="form-floating mb-3">
                            <input type="text" name="prenom" class="form-control" id="prenom" placeholder="Prénom"
                                required />
                            <label for="prenom">Prénom</label>
                        </div>

                        <!--FORMULAIRE NOM-->
                        <div class="form-floating mb-3">
                            <input type="text" name="nom" class="form-control" id="nom" placeholder="Nom" required />
                            <label for="nom">Nom</label>
                        </div>

                        <!--FORMULAIRE EMAIL-->
                        <div id="boite_email"></div>
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="email" placeholder="Courriel"
                                required />
                            <label for="email">Courriel</label>
                            <div class="message-erreur" id="erreur-courriel" style="color:red; display:none;"></div>
                        </div>

                        <!--FORMULAIRE ADRESSE -->
                        <div class="form-floating mb-3">
                            <input type="text" name="adresse" class="form-control" id="adresse" placeholder="Adresse" />
                            <label for="adresse">Adresse</label>
                        </div>

                        <!--FORMULAIRE PAYSN-->
                        <div class="form-floating mb-3">
                            <input type="text" name="pays" class="form-control" id="pays" placeholder="Pays"
                                value="Canada" />
                            <label for="pays">Pays</label>
                        </div>

                        <!--FORMULAIRE PROVINCE-->
                        <div class="form-floating mb-3">
                            <input type="text" name="province" class="form-control" id="province" placeholder="Province"
                                value="Québec" />
                            <label for="province">Province</label>
                        </div>

                        <!--FORMULAIRE ZIP-->
                        <div class="form-floating mb-3">
                            <input type="text" name="zip" class="form-control" id="zip" placeholder="Code postal" />
                            <label for="zip">Code postal</label>
                        </div>

                        <!--FORMULAIRE DATE DE NAISSANCE-->
                        <div class="form-floating mb-3">
                            <input type="number" name="age" class="form-control" id="age" required />
                            <label for="age">Age</label>
                            <div class="message-erreur" id="erreur-age" style="color:red; display:none;"></div>
                        </div>
                        <!--FORMULAIRE SEXE-->
                        <div class="mb-3">
                            <label for="sexe" class="form-label">Sexe</label>
                            <select name="sexe" class="form-select" id="sexe" required>
                                <option value="" disabled selected>Je suis un(e)</option>
                                <option value="homme">Homme</option>
                                <option value="femme">Femme</option>
                            </select>
                        </div>
                        <!--FORMULAIRE POIDS -->
                        <div class="form-floating mb-3">
                            <input type="number" step="0.1" name="poids" class="form-control" id="poids" required
                                placeholder="Poids" />
                            <label for="poids">Poids (kg)</label>
                            <div class="message-erreur" id="erreur-poids" style="color:red; display:none;"></div>
                        </div>

                        <!--FORMULAIRE TAILLE-->
                        <div class="form-floating mb-3">
                            <input type="number" step="0.1" name="taille" class="form-control" id="taille" required
                                placeholder="Taille" />
                            <label for="taille">Taille (cm)</label>
                            <div class="message-erreur" id="erreur-taille" style="color:red; display:none;"></div>
                        </div>

                        <!--FORMULAIRE ACTIVITE-->
                        <div class="mb-3">
                            <label for="activite" class="form-label">Niveau d'activité</label>
                            <select name="activite" class="form-select" id="activite" required>
                                <option value="" disabled selected>Niveau d'activité</option>
                                <option value="Sédentaire">Sédentaire</option>
                                <option value="Modéré">Modéré</option>
                                <option value="Actif">Actif</option>
                            </select>
                        </div>

                        <!--FORMULAIRE TELEPHONE-->
                        <div id="boite_tel"></div>
                        <div class="form-floating mb-3">
                            <input type="text" name="telephone" class="form-control" id="telephone"
                                placeholder="Téléphone" />
                            <label for="telephone">Téléphone</label>
                        </div>
                       

                        <button class="w-100 btn btn-lg btn-primary" type="submit"
                            id="Boutton_inscrire">S'inscrire</button>
                        <hr class="my-4" />
                        <small class="text-muted">En vous inscrivant, vous acceptez les termes d'utilisation de notre
                            plateforme.</small>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require_once '../../include/footer.php' ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
    <script>
        function updateSubmitButton() {
            const emailInput = document.getElementById("courriel");
            const telInput = document.getElementById("telephone");
            const submitButton = document.getElementById("Boutton_inscrire");

            const emailError = emailInput.classList.contains("shake_erreur");
            const telError = telInput.classList.contains("shake_erreur");

            submitButton.disabled = emailError || telError;
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passwordInput = document.getElementById("password");
            const togglePassword = document.getElementById("togglePassword");

            const confirmPasswordInput = document.getElementById("confirm_password");
            const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

            togglePassword.addEventListener("click", () => {
                passwordInput.type = passwordInput.type === "password" ? "text" : "password";
            });

            toggleConfirmPassword.addEventListener("click", () => {
                confirmPasswordInput.type = confirmPasswordInput.type === "password" ? "text" : "password";
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('form').on('submit', function (e) {
                let formulaireValide = true;
                $('.message-erreur').hide().text('');
                // Récupérer les valeurs
                const nomUtilisateur = $('#username').val().trim();
                const courriel = $('#email').val().trim();
                const motDePasse = $('#password').val();
                const confirmationMotDePasse = $('#confirm_password').val();
                const age = parseInt($('#age').val());
                const poids = parseFloat($('#poids').val());
                const taille = parseFloat($('#taille').val());

                // Nom d'utilisateur
                if (nomUtilisateur.length < 4) {
                    afficherErreur('#erreur-nom-utilisateur', "Le nom d'utilisateur doit posséder au moins 4 caractères.");
                    formulaireValide = false;
                }
                // Mot de passe
                if (!motDePasseEstFort(motDePasse)) {
                    afficherErreur('#erreur-password', "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un symbole.");
                    $('#regles-mdp').slideDown();
                    formulaireValide = false;
                }
                // Confirmation mot de passe
                if (motDePasse !== confirmationMotDePasse) {
                    afficherErreur('#erreur-confirmation-password', "Les mots de passe ne correspondent pas.");
                    formulaireValide = false;
                }
                // Email
                if (!validerCourriel(courriel)) {
                    afficherErreur('#erreur-courriel', "L'email doit contenir au moins 6 caractères,\ndoît inclure un '@' et un '.'");
                    formulaireValide = false;
                }
                // Âge
                if (isNaN(age) || age < 10 || age > 100) {
                    afficherErreur('#erreur-age', "L'âge doit être entre 10 et 100 ans.");
                    formulaireValide = false;
                }
                // Poids
                if (isNaN(poids) || poids < 30 || poids > 300) {
                    afficherErreur('#erreur-poids', "Le poids doit être entre 30 kg et 300 kg.");
                    formulaireValide = false;
                }
                // Taille
                if (isNaN(taille) || taille < 100 || taille > 250) {
                    afficherErreur('#erreur-taille', "La taille doit être entre 100 cm et 250 cm.");
                    formulaireValide = false;
                }

                if (!formulaireValide) e.preventDefault();
            });

            function afficherErreur(selecteur, message) {
                $(selecteur)
                    .stop(true, true)
                    .hide()
                    .text(message)
                    .slideDown();
            }
            // Format de courriel
            function validerCourriel(courriel) {
                const expression = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return expression.test(courriel);
            }
            // Format du mot de passe
            function motDePasseEstFort(motDePasse) {
                const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
                return regex.test(motDePasse);
            }
            // Quand l'utilisateur tape dans le mot de passe
            $('#password').on('input', function () {
                const motDePasse = $(this).val();
                $('#regles-mdp').show();
                let toutesLesConditions = true;

                // Vérification de la longueur
                const regleLongueur = motDePasse.length >= 8;
                $('#regle-longueur')
                    .html((regleLongueur ? '✔️' : '❌') + ' Au moins 8 caractères')
                    .toggleClass('valide', regleLongueur)
                    .toggleClass('invalide', !regleLongueur);
                if (!regleLongueur) toutesLesConditions = false;
                // Majuscule
                const regleMajuscule = /[A-Z]/.test(motDePasse);
                $('#regle-majuscule')
                    .html((regleMajuscule ? '✔️' : '❌') + ' Une lettre majuscule')
                    .toggleClass('valide', regleMajuscule)
                    .toggleClass('invalide', !regleMajuscule);
                if (!regleMajuscule) toutesLesConditions = false;
                // Minuscule
                const regleMinuscule = /[a-z]/.test(motDePasse);
                $('#regle-minuscule')
                    .html((regleMinuscule ? '✔️' : '❌') + ' Une lettre minuscule')
                    .toggleClass('valide', regleMinuscule)
                    .toggleClass('invalide', !regleMinuscule);
                if (!regleMinuscule) toutesLesConditions = false;
                // Chiffre
                const regleChiffre = /\d/.test(motDePasse);
                $('#regle-chiffre')
                    .html((regleChiffre ? '✔️' : '❌') + ' Un chiffre')
                    .toggleClass('valide', regleChiffre)
                    .toggleClass('invalide', !regleChiffre);
                if (!regleChiffre) toutesLesConditions = false;
                // Caractère spécial
                const regleSpecial = /[\W_]/.test(motDePasse);
                $('#regle-special')
                    .html((regleSpecial ? '✔️' : '❌') + ' Un caractère spécial')
                    .toggleClass('valide', regleSpecial)
                    .toggleClass('invalide', !regleSpecial);
                if (!regleSpecial) toutesLesConditions = false;
                // Cacher la liste si toutes les règles sont respectées
                if (toutesLesConditions) {
                    $('#regles-mdp').slideUp();
                } else {
                    $('#regles-mdp').slideDown();
                }
            });
        });
    </script>
    <!-- Icone de l'Oeuil -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
    <script>
        $(document).ready(function () {
            <?php if (!empty($erreur_inscription)): ?>
                if ('<?php echo $erreur_inscription; ?>'.includes(1)) {
                    $('#username').addClass('shake_erreur');
                    setTimeout(() => $('#username').removeClass('shake_erreur'), 300);
                }
                if ('<?php echo $erreur_inscription; ?>'.includes(2)) {
                    $('#email').addClass('shake_erreur');
                    setTimeout(() => $('#email').removeClass('shake_erreur'), 300);
                }

                if ('<?php echo $erreur_inscription; ?>'.includes(3)) {
                    $('#username').addClass('shake_bon');
                    setTimeout(() => $('#username').removeClass('shake_bon'), 300);
                    $('#password').addClass('shake_bon');
                    setTimeout(() => $('#password').removeClass('shake_bon'), 300);
                    $('#confirm_password').addClass('shake_bon');
                    setTimeout(() => $('#confirm_password').removeClass('shake_bon'), 300);
                    $('#prenom').addClass('shake_bon');
                    setTimeout(() => $('#prenom').removeClass('shake_bon'), 300);
                    $('#nom').addClass('shake_bon');
                    setTimeout(() => $('#nom').removeClass('shake_bon'), 300);
                    $('#email').addClass('shake_bon');
                    setTimeout(() => $('#email').removeClass('shake_bon'), 300);
                    $('#adresse').addClass('shake_bon');
                    setTimeout(() => $('#adresse').removeClass('shake_bon'), 300);
                    $('#pays').addClass('shake_bon');
                    setTimeout(() => $('#pays').removeClass('shake_bon'), 300);
                    $('#province').addClass('shake_bon');
                    setTimeout(() => $('#province').removeClass('shake_bon'), 300);
                    $('#zip').addClass('shake_bon');
                    setTimeout(() => $('#zip').removeClass('shake_bon'), 300);
                    $('#age').addClass('shake_bon');
                    setTimeout(() => $('#age').removeClass('shake_bon'), 300);
                    $('#sexe').addClass('shake_bon');
                    setTimeout(() => $('#sexe').removeClass('shake_bon'), 300);
                    $('#poids').addClass('shake_bon');
                    setTimeout(() => $('#poids').removeClass('shake_bon'), 300);
                    $('#taille').addClass('shake_bon');
                    setTimeout(() => $('#taille').removeClass('shake_bon'), 300);
                    $('#activite').addClass('shake_bon');
                    setTimeout(() => $('#activite').removeClass('shake_bon'), 300);
                    $('#telephone').addClass('shake_bon');
                    setTimeout(() => $('#telephone').removeClass('shake_bon'), 300);

                    $('#boite_confirmation').fadeIn('slow');
                    setTimeout(function () {
                        window.location.href = "../Page_connexion/Connexion.php";
                    }, 2000);
                }
            <?php endif; ?>
        });
    </script>
</body>

</html>