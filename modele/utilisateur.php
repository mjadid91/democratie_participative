<?php
require_once('objet.php');

class utilisateur extends objet {
    protected static string $classe = "utilisateur";
    protected static string $identifiant = "loginUtilisateur";

    protected string $loginUtilisateur;
    protected string $mdpUtilisateur;
    protected string $nomUtilisateur;
    protected string $prenomUtilisateur;
    protected string $emailUtilisateur;
    protected string $adresseUtilisateur;
    protected string $cpUtilisateur;
    protected string $villeUtilisateur;

    public function __construct(string $loginUtilisateur = NULL, string $mdpUtilisateur = NULL, string $nomUtilisateur = NULL, string $prenomUtilisateur = NULL, string $emailUtilisateur = NULL, string $adresseUtilisateur = NULL, string $cpUtilisateur = NULL, string $villeUtilisateur = NULL) {
        if (!is_null($loginUtilisateur)) {
            $this->loginUtilisateur = $loginUtilisateur;
            $this->mdpUtilisateur = $mdpUtilisateur;
            $this->nomUtilisateur = $nomUtilisateur;
            $this->prenomUtilisateur = $prenomUtilisateur;
            $this->emailUtilisateur = $emailUtilisateur;
            $this->adresseUtilisateur = $adresseUtilisateur;
            $this->cpUtilisateur = $cpUtilisateur;
            $this->villeUtilisateur = $villeUtilisateur;
        }
    }

    public static function userIsConnected() {
        return isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] !== null;
    }

    public function connecter() {
        $_SESSION['utilisateur'] = $this;
    }

    public static function creerCompte() {
        if (!isset($_SESSION['utilisateur'])) {
            $_SESSION['utilisateur'] = NULL;
        }
    }

    public static function deconnecter() {
        $_SESSION['utilisateur'] = NULL;

    }

    public static function utilisateurExiste($loginUtilisateur) {
        $sql = "SELECT COUNT(*) as count FROM utilisateur WHERE loginUtilisateur = :loginUtilisateur";
        $stmt = Connexion::pdo()->prepare($sql);
        $stmt->bindParam(':loginUtilisateur', $loginUtilisateur);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public static function ajouterMembreAuGroupe($IDGroupe, $loginUtilisateur) {
        $sql = "INSERT INTO role_dans_groupe (loginUtilisateur, IDGroupe, IDRole) 
            VALUES (:loginUtilisateur, :IDGroupe, 2)"; // IDRole = 2 pour Membre
        $stmt = Connexion::pdo()->prepare($sql);
        $stmt->bindParam(':loginUtilisateur', $loginUtilisateur);
        $stmt->bindParam(':IDGroupe', $IDGroupe, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du membre : " . $e->getMessage());
            return false;
        }
    }

    public static function supprimerMembreDuGroupe($IDGroupe, $loginUtilisateur) {
        $sql = "DELETE FROM role_dans_groupe WHERE loginUtilisateur = :loginUtilisateur AND IDGroupe = :IDGroupe";
        $stmt = Connexion::pdo()->prepare($sql);

        try {
            $stmt->execute([':loginUtilisateur' => $loginUtilisateur, ':IDGroupe' => $IDGroupe]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du membre : " . $e->getMessage());
            return false;
        }
    }

    public static function estMembreDuGroupe($IDGroupe, $loginUtilisateur) {
        $sql = "SELECT COUNT(*) as count FROM role_dans_groupe WHERE loginUtilisateur = :loginUtilisateur AND IDGroupe = :IDGroupe";
        $stmt = Connexion::pdo()->prepare($sql);
        $stmt->bindParam(':loginUtilisateur', $loginUtilisateur);
        $stmt->bindParam(':IDGroupe', $IDGroupe, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }


}
?>
