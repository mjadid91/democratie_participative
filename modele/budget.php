<?php

class budget {
    protected static string $classe = "budget";
    protected static string $identifiant = "IDBudget";

    protected int $IDBudget;
    protected float $montantUtiliseeBudget;
    protected float $montantTotalBudget;
    protected ?int $IDGroupe;

    public function __construct(
        int $IDBudget = null,
        float $montantUtiliseeBudget = 0.0,
        float $montantTotalBudget = 0.0,
        ?int $IDGroupe = null
    ) {
        if ($montantTotalBudget <= 0) {
            throw new Exception("Erreur : Le montant total du budget doit être supérieur à 0.");
        }
        if (is_null($IDGroupe)) {
            throw new Exception("Erreur : Le groupe associé au budget est requis.");
        }

        $this->IDBudget = $IDBudget ?? 0;
        $this->montantUtiliseeBudget = $montantUtiliseeBudget;
        $this->montantTotalBudget = $montantTotalBudget;
        $this->IDGroupe = $IDGroupe;
    }

    public static function getBudgetParGroupe($IDGroupe) {
        $sql = "SELECT montantTotalBudget, montantUtiliseeBudget, (montantTotalBudget - montantUtiliseeBudget) AS montantDisponible
                FROM budget WHERE IDGroupe = :IDGroupe";
        $stmt = Connexion::pdo()->prepare($sql);
        $stmt->bindValue(':IDGroupe', $IDGroupe, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insererBudget() {
        $sql = "INSERT INTO budget (montantTotalBudget, montantUtiliseeBudget, IDGroupe)
                VALUES (:montantTotalBudget, :montantUtiliseeBudget, :IDGroupe)";
        $stmt = Connexion::pdo()->prepare($sql);

        $stmt->bindValue(":montantTotalBudget", $this->montantTotalBudget, PDO::PARAM_STR);
        $stmt->bindValue(":montantUtiliseeBudget", $this->montantUtiliseeBudget, PDO::PARAM_STR);
        $stmt->bindValue(":IDGroupe", $this->IDGroupe, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return Connexion::pdo()->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion du budget : " . $e->getMessage());
            return false;
        }
    }
}
?>
