<?php
require_once("modele/groupe.php");
class controleurGroupes {

    protected static $champs = array(
        "nomGroupe" => ["text", "Nom du groupe"],
        "descriptionGroupe" => ["text", "Description"],
        "montantTotalBudget" => ["number", "Montant total du budget"]
    );

    public static function affichage() {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur'] === NULL) {
            header("Location: routeur.php?page=connexion");
            exit();
        }

        // Récupère l'utilisateur connecté
        $loginUtilisateur = $_SESSION['utilisateur']->get('loginUtilisateur');

        // Récupère les groupes auxquels l'utilisateur appartient et ceux qu'il administre
        $tabGroupes = groupe::getGroupeByUser($loginUtilisateur);
        $tabGroupesAdmin = groupe::getGroupeAdminByUser($loginUtilisateur);

        // Supprimer les doublons entre les deux listes
        $groupesUniques = [];
        foreach (array_merge($tabGroupes, $tabGroupesAdmin) as $groupe) {
            $groupesUniques[$groupe->get('IDGroupe')] = $groupe; // Utilisation de l'IDGroupe comme clé
        }
        $groupesUniques = array_values($groupesUniques); // Réindexe le tableau

        include('vue/debut.php');
        include('vue/groupe/lesGroupesVue.php');
        include('vue/fin.php');
    }


    public static function creerGroupe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Étape 1 : Récupération des données du formulaire
                $nomGroupe = htmlspecialchars(trim($_POST['nomGroupe']));
                $descriptionGroupe = htmlspecialchars(trim($_POST['descriptionGroupe']));
                $couleurGroupe = htmlspecialchars(trim($_POST['couleurGroupe']));
                $montantTotalBudget = floatval($_POST['montantTotalBudget']);
                $imageGroupe = '';

                if (empty($nomGroupe) || $montantTotalBudget <= 0) {
                    throw new Exception("Le nom du groupe et le montant du budget sont obligatoires.");
                }

                $dateCreation = date('Y-m-d');

                // Étape 2 : Création de l'objet groupe
                $groupe = new groupe();
                $groupe->set('nomGroupe', $nomGroupe);
                $groupe->set('descriptionGroupe', $descriptionGroupe);
                $groupe->set('couleurGroupe', $couleurGroupe);
                $groupe->set('imageGroupe', $imageGroupe);
                $groupe->set('dateCreationGroupe', $dateCreation);

                // Insérer le groupe dans la base de données
                $IDGroupe = $groupe->insererDansGroupe();
                if (!$IDGroupe) {
                    throw new Exception("Erreur lors de la création du groupe.");
                }

                // Étape 3 : Création de l'objet budget
                $budget = new budget(null, 0.0, $montantTotalBudget, $IDGroupe);
                if (!$budget->insererBudget()) {
                    throw new Exception("Erreur lors de la création du budget.");
                }

                // Étape 4 : Associer l'utilisateur comme administrateur dans Role_dans_groupe
                $loginUtilisateur = $_SESSION['utilisateur']->get('loginUtilisateur');
                $req = "INSERT INTO role_dans_groupe (loginUtilisateur, IDGroupe, IDRole) VALUES (:login, :idGroupe, :idRole)";
                $stmt = Connexion::pdo()->prepare($req);
                $stmt->bindValue(":login", $loginUtilisateur);
                $stmt->bindValue(":idGroupe", $IDGroupe);
                $stmt->bindValue(":idRole", 1); // Rôle 1 : Administrateur
                $stmt->execute();

                // Étape 5 : Redirection vers la liste des groupes
                $_SESSION['messageGroupeCreer'] = "Groupe et budget créés avec succès.";
                header("Location: routeur.php?page=groupes");
                exit();

            } catch (Exception $e) {
                $_SESSION['message'] = "Erreur : " . $e->getMessage();
                header("Location: routeur.php?page=groupes&action=afficherFormulaireGroupe");
                exit();
            }
        }
    }

    public static function modifierGroupe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $IDGroupe = intval($_POST['IDGroupe']);
                $nomGroupe = htmlspecialchars(trim($_POST['nomGroupe']));
                $descriptionGroupe = htmlspecialchars(trim($_POST['descriptionGroupe']));
                $montantTotalBudget = floatval($_POST['montantTotalBudget']);

                if (empty($nomGroupe) || empty($descriptionGroupe) || $montantTotalBudget <= 0) {
                    throw new Exception("Tous les champs sont requis et le montant total doit être supérieur à zéro.");
                }

                // Mise à jour des informations du groupe
                $groupe = new groupe($IDGroupe, $nomGroupe, $descriptionGroupe, "", "");
                if (!$groupe->modifierGroupe()) {
                    throw new Exception("Erreur lors de la modification des informations du groupe.");
                }

                // Mise à jour du budget
                $budget = budget::getBudgetParGroupe($IDGroupe);
                $budget['montantTotalBudget'] = $montantTotalBudget;

                $stmt = Connexion::pdo()->prepare("
                UPDATE budget
                SET montantTotalBudget = :montantTotalBudget
                WHERE IDGroupe = :IDGroupe
            ");
                $stmt->bindValue(':montantTotalBudget', $montantTotalBudget, PDO::PARAM_STR);
                $stmt->bindValue(':IDGroupe', $IDGroupe, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['message'] = "Le groupe et le budget ont été modifiés avec succès.";
                header("Location: routeur.php?page=groupes");
                exit();
            } catch (Exception $e) {
                $_SESSION['message'] = "Erreur : " . $e->getMessage();
                header("Location: routeur.php?page=groupes&action=afficherFormulaireModifierGroupe&IDGroupe=$IDGroupe");
                exit();
            }
        }
    }

    public static function supprimerMembre() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $IDGroupe = intval($_POST['IDGroupe']);
                $loginUtilisateur = htmlspecialchars(trim($_POST['loginUtilisateur']));

                // Vérification des droits (l'utilisateur doit être admin)
                if (!roleDansGroupe::isAdmin($_SESSION['utilisateur']->get('loginUtilisateur'), $IDGroupe)) {
                    throw new Exception("Vous n'avez pas les droits pour supprimer un membre de ce groupe.");
                }

                // Suppression du membre dans la base de données
                $req = "DELETE FROM role_dans_groupe WHERE IDGroupe = :IDGroupe AND loginUtilisateur = :loginUtilisateur";
                $stmt = Connexion::pdo()->prepare($req);
                $stmt->bindValue(':IDGroupe', $IDGroupe, PDO::PARAM_INT);
                $stmt->bindValue(':loginUtilisateur', $loginUtilisateur, PDO::PARAM_STR);
                $stmt->execute();

                $_SESSION['msgSuppression'] = "Le membre a été supprimé avec succès.";
                header("Location: routeur.php?page=propositions&action=afficherPropositions&IDGroupe=$IDGroupe");
                exit();
            } catch (Exception $e) {
                $_SESSION['msgErreurSuppression'] = "Erreur : " . $e->getMessage();
                header("Location: routeur.php?page=propositions&action=afficherPropositions&IDGroupe=$IDGroupe");
                exit();
            }
        }
    }



    public static function envoyerInvitation() {}

    public static function afficherFormulaireGroupe() {
        $champs = self::$champs;
        include('vue/debut.php');
        include('vue/groupe/formulaireGroupe.php');
        include('vue/fin.php');
    }

    public static function afficherFormulaireModifierGroupe() {
        if (!isset($_GET['IDGroupe']) || empty($_GET['IDGroupe'])) {
            echo "<p class='alert alert-danger'>Erreur : L'ID du groupe n'a pas été spécifié.</p>";
            return;
        }

        $IDGroupe = intval($_GET['IDGroupe']);
        $groupe = groupe::getOne($IDGroupe);
        $budget = budget::getBudgetParGroupe($IDGroupe);

        if (!$groupe) {
            echo "<p class='alert alert-danger'>Erreur : Groupe introuvable.</p>";
            return;
        }

        include('vue/debut.php');
        include('vue/groupe/formulaireModifierGroupe.php');
        include('vue/fin.php');
    }


}
?>
