<?php

class reaction {

    protected static string $classe = "reaction";
    protected static string $identifiant = "IDReaction";

    protected int $IDReaction;
    protected string $typeReaction;

    public function __construct(int $IDReaction = null, string $typeReaction = "") {
        if (!is_null($IDReaction)) {
            $this->IDReaction = $IDReaction;
            $this->typeReaction = $typeReaction;
        }
    }

    // Supprimer une réaction dans les deux tables
    public static function deleteReaction($IDCommentaire, $IDReaction, $loginUtilisateur) {
        try {
            // Supprimer dans envoie_reaction
            $reqDeleteEnvoie = "
            DELETE FROM envoie_reaction
            WHERE IDCommentaire = :IDCommentaire
            AND IDReaction = :IDReaction
            AND loginUtilisateur = :loginUtilisateur
        ";
            $stmtDeleteEnvoie = Connexion::pdo()->prepare($reqDeleteEnvoie);
            $stmtDeleteEnvoie->bindValue(':IDCommentaire', $IDCommentaire, PDO::PARAM_INT);
            $stmtDeleteEnvoie->bindValue(':IDReaction', $IDReaction, PDO::PARAM_INT);
            $stmtDeleteEnvoie->bindValue(':loginUtilisateur', $loginUtilisateur);
            $stmtDeleteEnvoie->execute();

            return "Réaction supprimée.";
        } catch (PDOException $e) {
            return "Erreur lors de la suppression de la réaction : " . $e->getMessage();
        }
    }
    public static function updateReaction($IDCommentaire, $IDReaction, $loginUtilisateur) {
        try {
            // Vérifier si l'IDReaction existe dans la table reaction
            $reqCheckReaction = "
            SELECT COUNT(*) 
            FROM reaction 
            WHERE IDReaction = :IDReaction
        ";
            $stmtCheckReaction = Connexion::pdo()->prepare($reqCheckReaction);
            $stmtCheckReaction->bindValue(':IDReaction', $IDReaction, PDO::PARAM_INT);
            $stmtCheckReaction->execute();

            if ($stmtCheckReaction->fetchColumn() == 0) {
                return "Erreur : Le type de réaction sélectionné n'existe pas.";
            }

            // Mettre à jour dans envoie_reaction
            $reqUpdate = "
            UPDATE envoie_reaction 
            SET IDReaction = :IDReaction 
            WHERE loginUtilisateur = :loginUtilisateur
            AND IDCommentaire = :IDCommentaire
        ";
            $stmtUpdate = Connexion::pdo()->prepare($reqUpdate);
            $stmtUpdate->bindValue(':IDReaction', $IDReaction, PDO::PARAM_INT);
            $stmtUpdate->bindValue(':IDCommentaire', $IDCommentaire, PDO::PARAM_INT);
            $stmtUpdate->bindValue(':loginUtilisateur', $loginUtilisateur);
            $stmtUpdate->execute();

            return "Réaction mise à jour.";
        } catch (PDOException $e) {
            return "Erreur lors de la mise à jour de la réaction : " . $e->getMessage();
        }
    }


    public static function insererReaction($IDCommentaire, $typeReaction, $loginUtilisateur) {
        try {
            $reqSelectReaction = "
            SELECT IDReaction 
            FROM reaction 
            WHERE typeReaction = :typeReaction
        ";
            $stmtSelectReaction = Connexion::pdo()->prepare($reqSelectReaction);
            $stmtSelectReaction->bindValue(':typeReaction', $typeReaction);
            $stmtSelectReaction->execute();
            $IDReaction = $stmtSelectReaction->fetchColumn();

            if (!$IDReaction) {
                return "Erreur : Le type de réaction sélectionné n'existe pas.";
            }

            // Vérifier si une réaction existe déjà
            $reqCheck = "
            SELECT IDReaction 
            FROM envoie_reaction
            WHERE IDCommentaire = :IDCommentaire 
            AND loginUtilisateur = :loginUtilisateur
        ";
            $stmtCheck = Connexion::pdo()->prepare($reqCheck);
            $stmtCheck->bindValue(':IDCommentaire', $IDCommentaire, PDO::PARAM_INT);
            $stmtCheck->bindValue(':loginUtilisateur', $loginUtilisateur);
            $stmtCheck->execute();

            $reactionExistante = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($reactionExistante) {
                // Si la réaction est identique, on la supprime
                if ($reactionExistante['IDReaction'] == $IDReaction) {
                    self::deleteReaction($IDCommentaire, $reactionExistante['IDReaction'], $loginUtilisateur);
                    return "Réaction supprimée.";
                } else {
                    // Sinon, on met à jour la réaction
                    return self::updateReaction($IDCommentaire, $IDReaction, $loginUtilisateur);
                }
            }

            // Insérer une nouvelle réaction
            $reqInsert = "
            INSERT INTO envoie_reaction (IDCommentaire, IDReaction, loginUtilisateur)
            VALUES (:IDCommentaire, :IDReaction, :loginUtilisateur)
        ";
            $stmtInsert = Connexion::pdo()->prepare($reqInsert);
            $stmtInsert->bindValue(':IDCommentaire', $IDCommentaire, PDO::PARAM_INT);
            $stmtInsert->bindValue(':IDReaction', $IDReaction, PDO::PARAM_INT);
            $stmtInsert->bindValue(':loginUtilisateur', $loginUtilisateur);
            $stmtInsert->execute();

            return "Réaction ajoutée.";
        } catch (PDOException $e) {
            return "Erreur lors de l'insertion de la réaction : " . $e->getMessage();
        }
    }

}
?>