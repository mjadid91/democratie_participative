<?php

class commentaire extends objet {

    protected static string $classe = "commentaire";
    protected static string $identifiant = "IDCommentaire";

    protected int $IDCommentaire;
    protected string $texteCommentaire;
    protected string $dateCommentaire;

    // Constructeur
    public function __construct(int $IDCommentaire = null, string $texteCommentaire = "", string $dateCommentaire = "") {
        if (!is_null($IDCommentaire)) {
            $this->IDCommentaire = $IDCommentaire;
            $this->texteCommentaire = $texteCommentaire;
            $this->dateCommentaire = $dateCommentaire;
        }
    }

    // Ajouter une méthode pour récupérer les commentaires liés à une proposition
    public static function getCommentairesDansProposition($IDProposition) {
        $requete = "
    SELECT 
        c.IDCommentaire, 
        c.texteCommentaire, 
        c.dateCommentaire, 
        u.loginUtilisateur,
        GROUP_CONCAT(DISTINCT CONCAT(r.typeReaction, ':', er.loginUtilisateur) SEPARATOR ';') AS reactions
    FROM 
        commentaire c
    JOIN 
        commentaire_proposition cp ON c.IDCommentaire = cp.IDCommentaire
    JOIN 
        commentaire_utilisateur cu ON c.IDCommentaire = cu.IDCommentaire
    JOIN 
        utilisateur u ON cu.loginUtilisateur = u.loginUtilisateur
    LEFT JOIN 
        envoie_reaction er ON c.IDCommentaire = er.IDCommentaire
    LEFT JOIN 
        reaction r ON er.IDReaction = r.IDReaction
    WHERE 
        cp.IDProposition = :IDProposition
    GROUP BY 
        c.IDCommentaire
    ORDER BY 
        c.dateCommentaire ASC
    ";

        $stmt = Connexion::pdo()->prepare($requete);
        $stmt->bindValue(':IDProposition', $IDProposition, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function insererCommentaire(string $texteCommentaire) {
        $req = "INSERT INTO commentaire (texteCommentaire, dateCommentaire) VALUES (:texteCommentaire, :dateCommentaire)";
        $stmt = Connexion::pdo()->prepare($req);

        $stmt->bindParam(":texteCommentaire", $texteCommentaire);
        $dateCommentaire = date('Y-m-d H:i:s');
        $stmt->bindParam(":dateCommentaire", $dateCommentaire);

        try {
            $stmt->execute();
            return Connexion::pdo()->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }

    public static function supprimerCommentaire($IDCommentaire, $loginUtilisateur) {
        try {
            // Vérifier si le commentaire appartient à l'utilisateur
            $verifReq = "SELECT COUNT(*) FROM commentaire_utilisateur 
                     WHERE IDCommentaire = :IDCommentaire 
                     AND loginUtilisateur = :loginUtilisateur";
            $verifStmt = Connexion::pdo()->prepare($verifReq);
            $verifStmt->bindValue(':IDCommentaire', $IDCommentaire, PDO::PARAM_INT);
            $verifStmt->bindValue(':loginUtilisateur', $loginUtilisateur, PDO::PARAM_STR);
            $verifStmt->execute();

            if ($verifStmt->fetchColumn() == 0) {
                throw new Exception("Vous n'êtes pas autorisé à supprimer ce commentaire.");
            }

            // Supprimer les associations du commentaire avec la proposition et l'utilisateur
            $reqAssocProp = "DELETE FROM commentaire_proposition WHERE IDCommentaire = :IDCommentaire";
            $stmtAssocProp = Connexion::pdo()->prepare($reqAssocProp);
            $stmtAssocProp->bindValue(':IDCommentaire', $IDCommentaire, PDO::PARAM_INT);
            $stmtAssocProp->execute();

            $reqAssocUser = "DELETE FROM commentaire_utilisateur WHERE IDCommentaire = :IDCommentaire";
            $stmtAssocUser = Connexion::pdo()->prepare($reqAssocUser);
            $stmtAssocUser->bindValue(':IDCommentaire', $IDCommentaire, PDO::PARAM_INT);
            $stmtAssocUser->execute();

            // Supprimer le commentaire lui-même
            $req = "DELETE FROM commentaire WHERE IDCommentaire = :IDCommentaire";
            $stmt = Connexion::pdo()->prepare($req);
            $stmt->bindValue(':IDCommentaire', $IDCommentaire, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression du commentaire : " . $e->getMessage());
            return false;
        }
    }


}