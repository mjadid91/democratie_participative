<?php

class vote extends objet {
    protected static string $classe = "vote";
    protected static string $identifiant = "IDVote";

    protected int $IDVote;
    protected int $IDProposition;
    protected string $typeVote; // Exemple : Majoritaire, Consultatif
    protected string $dateDebutVote;
    protected string $dateFinVote;
    protected string $majoriteVote; // Enum : Pour, Contre, En cours

    // Constructeur
    public function __construct(int $IDVote = null, int $IDProposition = null, string $typeVote = "", string $dateDebutVote = "", string $dateFinVote = "", string $majoriteVote = "") {
        if (!is_null($IDVote)) {
            $this->IDVote = $IDVote;
            $this->IDProposition = $IDProposition;
            $this->typeVote = $typeVote;
            $this->dateDebutVote = $dateDebutVote;
            $this->dateFinVote = $dateFinVote;
            $this->majoriteVote = $majoriteVote;
        }
    }

    public static function getVotesPourProposition($IDProposition) {
        $requete = "SELECT * FROM " . static::$classe . " WHERE IDProposition = :IDProposition";

        try {
            $stmt = Connexion::pdo()->prepare($requete);
            $stmt->bindParam(":IDProposition", $IDProposition);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif contenant tous les votes
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des votes pour la proposition : " . $e->getMessage());
            return false;
        }
    }

    public static function insererVote($IDProposition, $typeVote, $dateDebutVote, $dateFinVote, $majoriteVote) {
        $req = "INSERT INTO vote (IDProposition, typeVote, dateDebutVote, dateFinVote, majoriteVote) VALUES (:IDProposition, :typeVote, :dateDebutVote, :dateFinVote, :majoriteVote)";
        $stmt = Connexion::pdo()->prepare($req);

        $stmt->bindValue(':IDProposition', $IDProposition, PDO::PARAM_INT);
        $stmt->bindValue(':typeVote', $typeVote, PDO::PARAM_STR);
        $stmt->bindValue(':dateDebutVote', $dateDebutVote, PDO::PARAM_STR);
        $stmt->bindValue(':dateFinVote', $dateFinVote, PDO::PARAM_STR);
        $stmt->bindValue(':majoriteVote', $majoriteVote, PDO::PARAM_STR);

        try {
            $stmt->execute();
            return Connexion::pdo()->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion du vote : " . $e->getMessage());
            return false;
        }
    }

    // Soumettre ou modifier un vote
    public static function soumettreVote(string $loginUtilisateur, int $IDProposition, string $sensVote): string {
        $voteExistant = self::getVoteUtilisateur($loginUtilisateur, $IDProposition);

        if ($voteExistant) {
            if ($voteExistant['sensVote'] === $sensVote) {
                // Retirer le vote
                $sqlDelete = "DELETE FROM vote_utilisateur WHERE loginUtilisateur = :loginUtilisateur AND IDProposition = :IDProposition";
                $stmtDelete = Connexion::pdo()->prepare($sqlDelete);
                $stmtDelete->bindValue(':loginUtilisateur', $loginUtilisateur);
                $stmtDelete->bindValue(':IDProposition', $IDProposition);
                $stmtDelete->execute();
                return "Votre vote a été retiré.";
            } else {
                // Modifier le vote
                $sqlUpdate = "UPDATE vote_utilisateur SET sensVote = :sensVote WHERE loginUtilisateur = :loginUtilisateur AND IDProposition = :IDProposition";
                $stmtUpdate = Connexion::pdo()->prepare($sqlUpdate);
                $stmtUpdate->bindValue(':sensVote', $sensVote);
                $stmtUpdate->bindValue(':loginUtilisateur', $loginUtilisateur);
                $stmtUpdate->bindValue(':IDProposition', $IDProposition);
                $stmtUpdate->execute();
                return "Votre vote a été mis à jour.";
            }
        } else {
            // Insérer un nouveau vote
            $sqlInsert = "INSERT INTO vote_utilisateur (loginUtilisateur, IDProposition, sensVote) VALUES (:loginUtilisateur, :IDProposition, :sensVote)";
            $stmtInsert = Connexion::pdo()->prepare($sqlInsert);
            $stmtInsert->bindValue(':loginUtilisateur', $loginUtilisateur);
            $stmtInsert->bindValue(':IDProposition', $IDProposition);
            $stmtInsert->bindValue(':sensVote', $sensVote);
            $stmtInsert->execute();
            return "Votre vote a été enregistré.";
        }
    }


    public static function getVoteUtilisateur(string $loginUtilisateur, int $IDProposition) {
        $sql = "SELECT sensVote FROM vote_utilisateur WHERE loginUtilisateur = :loginUtilisateur AND IDProposition = :IDProposition";
        $stmt = Connexion::pdo()->prepare($sql);
        $stmt->bindValue(':loginUtilisateur', $loginUtilisateur);
        $stmt->bindValue(':IDProposition', $IDProposition);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}