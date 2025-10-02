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
            <!--MENU GAUCHE-->
            <div class="flex-shrink-0 p-3" style="width: 280px;">
              <!--BOUTON PROFIL-->
              <a href="profil.php" class="d-flex align-items-center pb-3 mb-3 text-decoration-none border-bottom">
                <svg class="bi pe-none me-2" width="30" height="24"><use xlink:href="#bootstrap"></use></svg>
                <span class="fs-5 fw-semibold">Profil</span>
              </a>
              <ul class="list-unstyled ps-0">
                <li class="mb-1">
                  <!--BOUTON INFORMATIONS PERSONNELLES-->
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                    Informations personnelles
                  </button>
                  <div class="collapse" id="home-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small" >
                      <!--SOUS BOUTON CONSULTER-->
                      <li><a href="info_perso.php" class=" d-inline-flex text-decoration-none rounded" >Consulter</a></li>
                    </ul>
                  </div>
                </li>
                <li class="mb-1">
                  <!--BOUTON OBJECTIFS-->
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                    Objectifs
                  </button>
                  <div class="collapse" id="dashboard-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <!--SOUS BOUTON MES OBJECTIFS-->
                      <li><a href="../page_objectifs/mesobjectifs.php" class=" d-inline-flex text-decoration-none rounded">Mes objectifs</a></li>
                    </ul>
                  </div>
                </li>
                <li class="mb-1">
                  <!--BOUTON PARAMÈTRE-->
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                    Paramètres
                  </button>
                  <div class="collapse" id="orders-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <!--SOUS BOUTON DÉTAILS-->
                      <li><a href="detail_compte.php" class=" d-inline-flex text-decoration-none rounded">Détails du compte</a></li>
                    </ul>
                  </div>
                </li>
                <li class="border-top my-3"></li>
                <li class="mb-1">
                  <!--BOUTON COMPTE-->
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                    Compte
                  </button>
                  <div class="collapse" id="account-collapse" style="transition: height 0.5s ease; overflow: hidden; height: 0;">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <!--SOUS BOUTON SE DÉCONNECTER-->
                      <li><a href="../Déconnexion.php" class=" d-inline-flex text-decoration-none rounded" onclick="return confirmerLogout();">Se déconnecter</a></li>
                    </ul>
                  </div>
                </li>
              </ul>
            </div>
</body>
</html>
<script>
function confirmerLogout() {
  const confirmer = confirm("Vous allez être déconnecté. Continuer ?");
  return confirmer;
}
</script>