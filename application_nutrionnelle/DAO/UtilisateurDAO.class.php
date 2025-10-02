<?php
require_once(__DIR__ . "/../modeles/Utilisateur.class.php");
require_once(__DIR__ . "/../include/connexionBD.php");

class UtilisateurDAO
{
    /* Ajouter un utilisateur */
    public static function ajouterUser(Utilisateur $user): bool
    {
        $pdo = ConnexionBD::getInstance();

        $username = $user->getUsername();
        $password = $user->getPassword();
        $date_inscription = $user->getDateInscription();
        $_SESSION['username'] = $username;
        if (empty($password)) {
            throw new Exception("Le mot de passe est obligatoire.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $date = date("Y/m/d");
        $stmt = $pdo->prepare(query: "INSERT INTO Utilisateurs (username, password, date_inscription) VALUES (:username, :password, :date_inscription)");
        $stmt1 = $pdo->prepare("INSERT INTO historique (id_hist, utilisateur_id, date, id_ali, id_obj) VALUES (:id_hist, :utilisateur_id, :date, :id_ali, :id_obj)");
         
         $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword,
            'date_inscription' => $date_inscription
        ]);
        $id_user = UtilisateurDAO::getIdByUsername($username);
    if ($id_user) 
    {
    
        return  $stmt1->execute([
            ':id_hist' => rand(1, getrandmax()),
            'utilisateur_id' => $id_user,
            'date' => $date,
            'id_ali' => null,
            'id_obj' => null
        ]);
         
    }
    return false;
    }

    /* Rechercher un utilisateur par username */
    public static function rechercherUser($username)
    {
        $pdo = ConnexionBD::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row;
        }
        return null;
    }

    /* Changer le nom d'utilisateur */
    public static function changerUsername($oldUsername, $newUsername): string|bool
    {
        if (self::rechercherUser($newUsername)) {
            return "Ce nom d'utilisateur est déjà pris.";
        }

        $pdo = ConnexionBD::getInstance();
        $stmt = $pdo->prepare("UPDATE Utilisateurs SET username = ? WHERE username = ?");
        return $stmt->execute([$newUsername, $oldUsername]);
    }

    /* Changer le mot de passe (hashé ici) */
    public static function changerMotDePasse($username, $plainPassword): bool
    {
        $pdo = ConnexionBD::getInstance();
        $hashed = password_hash($plainPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE Utilisateurs SET password = ? WHERE username = ?");
        return $stmt->execute([$hashed, $username]);
    }

    /* Récupérer l'ID par username */
    public static function getIdByUsername($username): ?int
    {
        $pdo = ConnexionBD::getInstance();
        $stmt = $pdo->prepare("SELECT id FROM Utilisateurs WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id'] : null;
    }
    //Vérifie le nom d'utilisateur et le mot de passe saisie et permet a l'utilisateur de se connecter
    public static function connexion($username, $pass, $user){
        if (password_verify($pass, $user['password'])) {
            setcookie('username', $username, time() + (10 * 365 * 24 * 60 * 60), "/");
            ListeObjectifControleur::creerNouvelleListeObjectif();
            if (isset($_POST['remember_me'])) {
                $token = bin2hex(random_bytes(32));
                $date_expiration = date('Y-m-d H:i:s', strtotime('+7 days'));
                RememberMeControleur::ajouterToken($user['id'], $token, $date_expiration);
                setcookie(
                'rememberMe',
                $token,
                [
                    'expires' => time() + (7 * 24 * 60 * 60),
                    'path' => '/',
                    'secure' => isset($_SERVER['HTTPS']),
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]
                );
                error_log("Token généré et cookie défini pour l'utilisateur ID=" . $user['id']);
            }

            ListeObjectifControleur::creerNouvelleListeObjectif();
            $erreur = 3;
        } else {
        $erreur = 2;
        error_log("Échec de connexion : mot de passe invalide pour $username");
        }
        return $erreur;
    }
    //Vérifie si le nom d'utilisateur saisit est disponible ou déja pris dans la base de données
    public static function verifierUsername($username): bool
    {
        $pdo = ConnexionBD::getInstance();
        $stmt = $pdo->prepare("SELECT username FROM Utilisateurs WHERE username = ?");
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return false; // Nom d'utilisateur déjà pris
        }
        return true; // Nom d'utilisateur disponible
    }
}
?>