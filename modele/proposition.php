<?php
require_once("objet.php");
class proposition extends objet {

    protected static string $classe = "proposition";
    protected static string $identifiant = "IDProposition";

    protected int $IDProposition;
    protected string $etatProposition;
    protected string $titreProposition;
    protected string $descriptionProposition;
    protected string $dateSoumissionProposition;
    protected string $dateFinProposition;
    protected float $montantProposition;
    protected ?int $IDBudget;
    protected ?string $loginUtilisateur;
    protected ?int $IDGroupe;

    // Constructeur
    public function __construct(int $IDProposition = null, string $etatProposition = "",
                                string $titreProposition = "", string $descriptionProposition = "",
                                string $dateSoumissionProposition = "", string $dateFinProposition = "",
                                float $montantProposition = 0.0, ?int $IDBudget = null,
                                ?string $loginUtilisateur = null, ?int $IDGroupe = null) {
        if (!is_null($IDProposition)) {
            $this->IDProposition = $IDProposition;
            $this->etatProposition = $etatProposition;
            $this->titreProposition = $titreProposition;
            $this->descriptionProposition = $descriptionProposition;
            $this->dateSoumissionProposition = $dateSoumissionProposition;
            $this->dateFinProposition = $dateFinProposition;
            $this->montantProposition = $montantProposition;
            $this->IDBudget = $IDBudget;
            $this->loginUtilisateur = $loginUtilisateur;
            $this->IDGroupe = $IDGroupe;
        }
    }

    // Ajouter une méthode pour récupérer les propositions liées à un groupe
    public static function getListePropositionDansGroupe($IDGroupe) {
        // Préparer la requête SQL
        $sql = "SELECT p.*
            FROM proposition p
            WHERE p.IDGroupe = :IDGroupe";

        // Exécuter la requête préparée
        $stmt = Connexion::pdo()->prepare($sql);
        $stmt->bindParam(':IDGroupe', $IDGroupe, PDO::PARAM_INT);
        $stmt->execute();

        // Récupérer les propositions
        $propositions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Vérifie si des propositions ont été trouvées
        if ($propositions) {
            return $propositions; // Retourne les propositions si trouvées
        } else {
            return false; // Retourne false si aucune proposition n'a été trouvée
        }
    }

    // Ajouter une methode qui recupere un seul proposition dans un groupe
    public static function getProposition($IDProposition) {
        // Préparer la requête SQL
        $sql = "SELECT p.*
            FROM proposition p
            WHERE p.IDProposition = :IDProposition";

        // Exécuter la requête préparée
        $stmt = Connexion::pdo()->prepare($sql);
        $stmt->bindParam(':IDProposition', $IDProposition, PDO::PARAM_INT);
        $stmt->execute();

        // Récupérer les propositions
        $proposition = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifie si des propositions ont été trouvées
        if ($proposition) {
            return $proposition; // Retourne les propositions si trouvées
        } else {
            return false; // Retourne false si aucune proposition n'a été trouvée
        }
    }

    public static function getCommentairesDansProposition($IDProposition) {
        // Requête pour récupérer les commentaires liés à la proposition
        $requete = "SELECT c.*, u.loginUtilisateur 
                    FROM commentaire c
                    JOIN commentaire_proposition cp ON c.IDCommentaire = cp.IDCommentaire
                    JOIN commentaire_utilisateur cu ON c.IDCommentaire = cu.IDCommentaire
                    JOIN utilisateur u ON cu.loginUtilisateur = u.loginUtilisateur
                    WHERE cp.IDProposition = :IDProposition";

        $resultat = Connexion::pdo()->prepare($requete);
        $resultat->bindParam(":IDProposition", $IDProposition);
        $resultat->execute();

        $resultat->setFetchMode(PDO::FETCH_CLASS, "commentaire");
        return $resultat->fetchAll();
    }

    public static function getListeMembreDansGroupe($IDGroupe) {
        // Préparer la requête SQL
        $sql = "SELECT distinct u.*
            FROM utilisateur u
            JOIN role_dans_groupe r ON u.loginUtilisateur = r.loginUtilisateur
            WHERE r.IDGroupe = :IDGroupe";

        $stmt = Connexion::pdo()->prepare($sql);
        $stmt->bindParam(':IDGroupe', $IDGroupe, PDO::PARAM_INT);
        $stmt->execute();

        $membres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($membres) {
            return $membres;
        } else {
            return false;
        }
    }

    public static function insererProposition(array $data) {
        $titreProposition = htmlspecialchars(trim($data['titreProposition']));
        $descriptionProposition = htmlspecialchars(trim($data['descriptionProposition']));
        $montantProposition = floatval($data['montantProposition']);
        $dateFinProposition = $data['dateFinProposition'];
        $IDGroupe = intval($data['IDGroupe']);
        $loginUtilisateur = htmlspecialchars(trim($data['loginUtilisateur']));

        // Vérification du budget disponible
        $budgetDisponible = budget::getBudgetParGroupe($IDGroupe); // Méthode ajoutée dans budget
        if (!$budgetDisponible || $montantProposition > $budgetDisponible['montantDisponible']) {
            echo "Le montant de la proposition dépasse le budget disponible.";
            return false;
        }

        $sql = "INSERT INTO proposition 
                (etatProposition, titreProposition, descriptionProposition, dateSoumissionProposition, dateFinProposition, montantProposition, IDGroupe, loginUtilisateur)
                VALUES 
                ('En attente', :titreProposition, :descriptionProposition, :dateSoumissionProposition, :dateFinProposition, :montantProposition, :IDGroupe, :loginUtilisateur)";
        $stmt = Connexion::pdo()->prepare($sql);
        $stmt->bindValue(':titreProposition', $titreProposition);
        $stmt->bindValue(':descriptionProposition', $descriptionProposition);
        $stmt->bindValue(':dateSoumissionProposition', date('Y-m-d'));
        $stmt->bindValue(':dateFinProposition', $dateFinProposition);
        $stmt->bindValue(':montantProposition', $montantProposition);
        $stmt->bindValue(':IDGroupe', $IDGroupe);
        $stmt->bindValue(':loginUtilisateur', $loginUtilisateur);

        try {
            $stmt->execute();
            budget::mettreAJourBudget($IDGroupe, $montantProposition);
            return Connexion::pdo()->lastInsertId();
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }
}