<?php

class signalement extends objet {
    protected static string $classe = "signalement";
    protected static string $identifiant = "IDSignalement";

    // Récupérer tous les signalements avec les informations des commentaires
    public static function getSignalements(): array {
        $sql = "SELECT s.IDSignalement, s.motifSignalement, s.dateSignalement, 
                       c.texteCommentaire, c.IDCommentaire, p.titreProposition
                FROM signalement s
                JOIN commentaire c ON s.IDCommentaire = c.IDCommentaire
                JOIN commentaire_proposition cp ON c.IDCommentaire = cp.IDCommentaire
                JOIN proposition p ON cp.IDProposition = p.IDProposition
                WHERE s.estSignalee = 1";

        $stmt = Connexion::pdo()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprimer un commentaire
    public static function supprimerCommentaire(int $IDCommentaire): bool {
        try {
            $pdo = Connexion::pdo();

            // Supprimer les signalements associés
            $sqlSignalements = "DELETE FROM signalement WHERE IDCommentaire = :IDCommentaire";
            $stmtSignalements = $pdo->prepare($sqlSignalements);
            $stmtSignalements->bindValue(':IDCommentaire', $IDCommentaire, PDO::PARAM_INT);
            $stmtSignalements->execute();

            // Supprimer les associations avec utilisateur
            $sqlCommentaireUtilisateur = "DELETE FROM commentaire_utilisateur WHERE IDCommentaire = :IDCommentaire";
            $stmtCommentaireUtilisateur = $pdo->prepare($sqlCommentaireUtilisateur);
            $stmtCommentaireUtilisateur->bindValue(':IDCommentaire', $IDCommentaire, PDO::PARAM_INT);
            $stmtCommentaireUtilisateur->execute();

            // Supprimer les associations avec proposition
            $sqlCommentaireProposition = "DELETE FROM commentaire_proposition WHERE IDCommentaire = :IDCommentaire";
            $stmtCommentaireProposition = $pdo->prepare($sqlCommentaireProposition);
            $stmtCommentaireProposition->bindValue(':IDCommentaire', $IDCommentaire, PDO::PARAM_INT);
            $stmtCommentaireProposition->execute();

            // Supprimer le commentaire lui-même
            $sqlCommentaire = "DELETE FROM commentaire WHERE IDCommentaire = :IDCommentaire";
            $stmtCommentaire = $pdo->prepare($sqlCommentaire);
            $stmtCommentaire->bindValue(':IDCommentaire', $IDCommentaire, PDO::PARAM_INT);
            $stmtCommentaire->execute();

            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du commentaire et des dépendances : " . $e->getMessage());
            return false;
        }
    }


}
