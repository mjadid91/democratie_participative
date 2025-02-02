<?php

class invitation extends objet {

    protected static string $classe = "invitation";
    protected static string $identifiant = "IDInvitation";

    protected int $IDInvitation;
    protected string $emailInvite;
    protected int $IDGroupe;
    protected string $loginUtilisateur;
    protected string $dateInvitation;
    protected string $statutInvitation;

    // Constructeur
    public function __construct(
        int $IDInvitation = null,
        string $emailInvite = "",
        int $IDGroupe = null,
        string $loginUtilisateur = "",
        string $dateInvitation = "",
        string $statutInvitation = "En attente"
    ) {
        if (!is_null($IDInvitation)) {
            $this->IDInvitation = $IDInvitation;
            $this->emailInvite = $emailInvite;
            $this->IDGroupe = $IDGroupe;
            $this->loginUtilisateur = $loginUtilisateur;
            $this->dateInvitation = $dateInvitation;
            $this->statutInvitation = $statutInvitation;
        }
    }

    public static function getInvitationById($IDInvitation) {
        try {
            $sql = "SELECT * FROM invitation WHERE IDInvitation = :IDInvitation";
            $stmt = Connexion::pdo()->prepare($sql);
            $stmt->execute([':IDInvitation' => $IDInvitation]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération de l'invitation : " . $e->getMessage());
        }
    }

    public static function updateStatutInvitation($IDInvitation, $statut) {
        try {
            $sql = "UPDATE invitation SET statutInvitation = :statut WHERE IDInvitation = :IDInvitation";
            $stmt = Connexion::pdo()->prepare($sql);
            $stmt->execute([
                ':statut' => $statut,
                ':IDInvitation' => $IDInvitation
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour de l'invitation : " . $e->getMessage());
        }
    }



    public static function insererInvitation($emailInvite, $IDGroupe, $loginUtilisateur) {
        try {
            $dateInvitation = date('Y-m-d');
            $sql = "INSERT INTO invitation (emailInvite, IDGroupe, loginUtilisateur, dateInvitation, statutInvitation) 
                VALUES (:emailInvite, :IDGroupe, :loginUtilisateur, :dateInvitation, 'En attente')";
            $stmt = Connexion::pdo()->prepare($sql);
            $stmt->execute([
                ':emailInvite' => $emailInvite,
                ':IDGroupe' => $IDGroupe,
                ':loginUtilisateur' => $loginUtilisateur,
                ':dateInvitation' => $dateInvitation
            ]);
            return Connexion::pdo()->lastInsertId(); // Retourne l'ID de l'invitation insérée
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'insertion de l'invitation : " . $e->getMessage());
        }
    }
}