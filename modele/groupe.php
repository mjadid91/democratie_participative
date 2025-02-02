<?php
require_once("objet.php");

class groupe extends objet {
    protected static string $classe = "groupe";
    protected static string $identifiant = "IDGroupe";

    protected int $IDGroupe;
    protected string $nomGroupe;
    protected string $descriptionGroupe;
    protected string $dateCreationGroupe;
    protected string $couleurGroupe;
    protected string $imageGroupe;

    // Constructeur
    public function __construct(int $IDGroupe = null, string $nomGroupe = "", string $descriptionGroupe = "", string $dateCreationGroupe = "", string $couleurGroupe = "", ?string $imageGroupe = null) {
        if (!is_null($IDGroupe)) {
            $this->IDGroupe = $IDGroupe;
            $this->nomGroupe = $nomGroupe;
            $this->descriptionGroupe = $descriptionGroupe;
            $this->dateCreationGroupe = $dateCreationGroupe;
            $this->couleurGroupe = $couleurGroupe;
            $this->imageGroupe = $imageGroupe ?? "";
        }
    }

    public static function getGroupeByUser($login) {
        $requete = "SELECT DISTINCT groupe.IDGroupe, groupe.nomGroupe, groupe.descriptionGroupe, 
                groupe.dateCreationGroupe, groupe.couleurGroupe, groupe.imageGroupe
                FROM groupe
                JOIN role_dans_groupe ON groupe.IDGroupe = role_dans_groupe.IDGroupe
                WHERE role_dans_groupe.loginUtilisateur = :loginUtilisateur";

        $resultat = Connexion::pdo()->prepare($requete);
        $resultat->bindParam(":loginUtilisateur", $login);
        $resultat->execute();
        $resultat->setFetchMode(PDO::FETCH_CLASS, static::$classe);

        return $resultat->fetchAll();
    }

    public static function getGroupeAdminByUser($login) {
        $requete = "SELECT DISTINCT groupe.IDGroupe, groupe.nomGroupe, groupe.descriptionGroupe, 
                groupe.dateCreationGroupe, groupe.couleurGroupe, groupe.imageGroupe
                FROM groupe
                JOIN role_dans_groupe ON groupe.IDGroupe = role_dans_groupe.IDGroupe
                JOIN role ON role_dans_groupe.IDRole = role.IDRole
                WHERE role_dans_groupe.loginUtilisateur = :loginUtilisateur 
                AND role.nomRole = 'Administrateur'";

        $resultat = Connexion::pdo()->prepare($requete);
        $resultat->bindParam(":loginUtilisateur", $login);
        $resultat->execute();
        $resultat->setFetchMode(PDO::FETCH_CLASS, static::$classe);

        return $resultat->fetchAll();
    }

    public function insererDansGroupe() {
        $req = "INSERT INTO groupe (nomGroupe, descriptionGroupe, dateCreationGroupe, couleurGroupe, imageGroupe)
                VALUES (:nomGroupe, :descriptionGroupe, :dateCreationGroupe, :couleurGroupe, :imageGroupe)";
        $stmt = Connexion::pdo()->prepare($req);

        $stmt->bindValue(":nomGroupe", $this->nomGroupe);
        $stmt->bindValue(":descriptionGroupe", $this->descriptionGroupe);
        $stmt->bindValue(":dateCreationGroupe", $this->dateCreationGroupe);
        $stmt->bindValue(":couleurGroupe", $this->couleurGroupe);
        $stmt->bindValue(":imageGroupe", $this->imageGroupe);

        try {
            $stmt->execute();
            return Connexion::pdo()->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion dans Groupe : " . $e->getMessage());
            return false;
        }
    }

    public function modifierGroupe() {
        $req = "UPDATE groupe 
            SET nomGroupe = :nomGroupe, 
                descriptionGroupe = :descriptionGroupe, 
                couleurGroupe = :couleurGroupe, 
                imageGroupe = :imageGroupe
            WHERE IDGroupe = :IDGroupe";
        $stmt = Connexion::pdo()->prepare($req);

        $stmt->bindValue(":nomGroupe", $this->nomGroupe);
        $stmt->bindValue(":descriptionGroupe", $this->descriptionGroupe);
        $stmt->bindValue(":couleurGroupe", $this->couleurGroupe);
        $stmt->bindValue(":imageGroupe", $this->imageGroupe);
        $stmt->bindValue(":IDGroupe", $this->IDGroupe, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la modification du groupe : " . $e->getMessage());
            return false;
        }
    }


}
?>
