<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . "/../include/connexionBD.php");
require_once(__DIR__ . "/../modeles/Utilisateur.class.php");
require_once(__DIR__ . "/../include/connexionBD.php");
require_once(__DIR__ . "/../modeles/InformationsPersonnelles.class.php");

class InformationsPersonnellesDAO
{

    /*--Ajouter des informations personnelles dans la base de données--*/
    public static function ajouterInformationsPersonnelles($info)
    {
        $pdo = ConnexionBD::getInstance();
        $sql = "INSERT INTO InformationsPersonnelles 
            (utilisateur_id, prenom, nom, courriel, adresse, pays, province, zip, age, sexe, poids, taille, niv_activite, telephone) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt2 = $pdo->prepare("INSERT INTO statistique (id_hist, poids, imc, calories_total, proteines_total, carbs_total, fat_total, date) VALUES (:id_hist, :poids, :imc, :calories, :proteines, :carbs, :fat, :date)");

        $id_user = UtilisateurDAO::getIdByUsername($_SESSION['username']);
        $id_hist = HistoriqueDAO::getIdhistbyUsernameid($id_user);
        $date = date("Y/m/d");
        $imc = self::calculIMC($info['poids'], $info['taille']);

        $stmt2->execute([
            ':id_hist' => $id_hist,
            ':poids' => $info['poids'],
            ':imc' => $imc,
            ':calories' => null,
            ':proteines' => null,
            ':carbs' => null,
            ':fat' => null,
            ':date' => $date
        ]);
        return $stmt->execute([
            $info['utilisateur_id'],
            $info['prenom'],
            $info['nom'],
            $info['courriel'],
            $info['adresse'],
            $info['pays'],
            $info['province'],
            $info['zip'],
            $info['age'],
            $info['sexe'],
            $info['poids'],
            $info['taille'],
            $info['niv_activite'],
            $info['telephone']
        ]);
    }

    /*--Modifier les informations personnelles dans la base de données--*/
    public static function modifier($utilisateur_id, $info)
    {
        $pdo = ConnexionBD::getInstance();

        $champs = [];
        $valeurs = [];

        foreach ($info as $cle => $valeur) {
            if ($cle !== 'utilisateur_id' && $valeur === '') {
                continue;
            }

            if ($cle !== 'utilisateur_id') {
                $champs[] = "$cle = ?";
                $valeurs[] = $valeur;
            }
        }

        $valeurs[] = $utilisateur_id;

        $sql = "UPDATE InformationsPersonnelles SET " . implode(", ", $champs) . " WHERE utilisateur_id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($valeurs);
    }

    /*-- Récupérer les informations personnelles d'un utilisateur --*/
    public static function getInformationsPersonnelles($utilisateur_id)
    {
        $pdo = ConnexionBD::getInstance();
        $sql = "SELECT* FROM InformationsPersonnelles WHERE utilisateur_id = ?";
        $start = $pdo->prepare($sql);
        $start->execute([$utilisateur_id]);
        $row = $start->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new InformationsPersonnelles(
                $row["utilisateur_id"],
                $row["prenom"],
                $row["nom"],
                $row["courriel"],
                $row["adresse"],
                $row["pays"],
                $row["province"],
                $row["zip"],
                $row["age"],
                $row["sexe"],
                $row["poids"],
                $row["taille"],
                $row["niv_activite"],
                $row["telephone"]
            );
        }
        return null;
    }

    /*--Rechercher un utilisateur dans la DB--*/
    public static function chercher($utilisateur_id)
    {
        $pdo = ConnexionBD::getInstance();
        $sql = "SELECT* FROM InformationsPersonnelles WHERE utilisateur_id = ?";
        $start = $pdo->prepare($sql);
        $start->execute([$utilisateur_id]);
        $row = $start->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row;
        }
        return null;
    }

    //Calcul l'imc de l'utilisateur avec son poids et sa taille
    public static function calculIMC($poids, $taille)
    {
        return $poids / (($taille / 100) * ($taille / 100));
    }

    //Vérifie si l'email est présent dans la base de donnée
    public static function chercherParEmail($email)
    {
        $pdo = ConnexionBD::getInstance();
        $sql = "SELECT * FROM InformationsPersonnelles WHERE courriel = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    public static function verifyEmail($email)
    {
        $pdo = ConnexionBD::getInstance();
        $sql = "SELECT * FROM InformationsPersonnelles WHERE courriel = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return false;
        } else {
            return true;
        }
    }
    public static function verifyTelephone($telephone)
    {
        $pdo = ConnexionBD::getInstance();
        $sql = "SELECT * FROM InformationsPersonnelles WHERE telephone = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$telephone]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return false;
        } else {
            return true;
        }
    }
}
?>