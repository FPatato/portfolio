<?php
require_once(__DIR__ . '/../include/connexionBD.php');

class RememberMeDAO {

    // Ajouter un cookies dans la base de données
    public static function ajouterToken($user_id, $token, $date_expiration) {
        $pdo = ConnexionBD::getInstance();
        // Vérifier si le cookies existe déjà pour cet utilisateur
        $query = "INSERT INTO Tokens (user_id, token, date_expiration) VALUES (:user_id, :token, :date_expiration)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':user_id' => $user_id,
            ':token' => $token,
            ':date_expiration' => $date_expiration
        ]);
    }


    // Récupérer l'utilisateur à partir du cookies 
    public static function verifierToken($token) {
        $pdo = ConnexionBD::getInstance();

        error_log("Recherche du token : $token");

        // Récupérer l'utilisateur associé au cookies 
        $query = "SELECT utilisateurs.id, utilisateurs.username FROM Tokens
                    JOIN utilisateurs ON Tokens.user_id = utilisateurs.id
                    WHERE Tokens.token = :token AND Tokens.date_expiration > NOW()";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':token' => $token]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Supprimer le cookies de la base de données lors de la déconnexion
    public static function supprimerToken($token) {
        $pdo = ConnexionBD::getInstance();
        $query = "DELETE FROM Tokens WHERE token = :token";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':token' => $token]);
    }


    // Supprimer tous les tokens expirés
    public static function supprimerTokensExpiires() {
        $pdo = ConnexionBD::getInstance();
        $query = "DELETE FROM Tokens WHERE date_expiration < NOW()";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }
}
?>
