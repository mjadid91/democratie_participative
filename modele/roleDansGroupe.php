<?php
require_once("objet.php");
class roleDansGroupe extends objet {

    protected static string $classe = "role_dans_groupe";
    protected static string $identifiant = "loginUtilisateur";

    protected string $loginUtilisateur;
    protected int $IDGroupe;
    protected int $IDRole;

    public function __construct(string $loginUtilisateur = NULL, int $IDGroupe = NULL, int $IDRole = NULL) {
        if (!is_null($loginUtilisateur)) {
            $this->loginUtilisateur = $loginUtilisateur;
            $this->IDGroupe = $IDGroupe;
            $this->IDRole = $IDRole;
        }
    }

    public static function isAdmin($loginUtilisateur, $IDGroupe) {
        $requete = "SELECT COUNT(*) FROM role_dans_groupe WHERE loginUtilisateur = :loginUtilisateur AND IDGroupe = :IDGroupe AND IDRole = 1";
        $resultat = Connexion::pdo()->prepare($requete);
        $resultat->bindParam(":loginUtilisateur", $loginUtilisateur);
        $resultat->bindParam(":IDGroupe", $IDGroupe);
        $resultat->execute();

        return $resultat->fetchColumn() > 0; // Retourne true si l'utilisateur est admin, false sinon
    }

    public static function isMembre ($loginUtilisateur, $IDGroupe) {
        $requete = "SELECT * FROM role_dans_groupe WHERE loginUtilisateur = :loginUtilisateur AND IDGroupe = :IDGroupe AND IDRole = 2";
        $resultat = Connexion::pdo()->prepare($requete);
        $resultat->bindParam(":loginUtilisateur", $loginUtilisateur);
        $resultat->bindParam(":IDGroupe", $IDGroupe);
        $resultat->execute();
        $resultat->setFetchMode(PDO::FETCH_CLASS, static::$classe);
        return $resultat->fetchAll();
    }


    public static function isOrganisateur($loginUtilisateur) {
        $requete = "SELECT COUNT(*) 
                FROM role_dans_groupe 
                JOIN role ON role_dans_groupe.IDRole = role.IDRole
                WHERE role_dans_groupe.loginUtilisateur = :loginUtilisateur
                AND role.nomRole = 'Organisateur'";
        $resultat = Connexion::pdo()->prepare($requete);
        $resultat->bindParam(":loginUtilisateur", $loginUtilisateur);
        $resultat->execute();

        return $resultat->fetchColumn() > 0; // Retourne true si l'utilisateur est organisateur, false sinon
    }


    public static function isModo($loginUtilisateur) {
        $requete = "SELECT COUNT(*) 
                FROM role_dans_groupe 
                JOIN role ON role_dans_groupe.IDRole = role.IDRole 
                WHERE role_dans_groupe.loginUtilisateur = :login 
                AND role.nomRole = 'Moderateur'";

        $stmt = Connexion::pdo()->prepare($requete);

        $stmt->bindParam(":login", $loginUtilisateur); // Le placeholder correspond ici
        $stmt->execute();

        return $stmt->fetchColumn() > 0; // Retourne vrai si l'utilisateur a le rôle de modérateur
    }
}
?>