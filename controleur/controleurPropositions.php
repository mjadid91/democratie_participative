<?php
require_once("modele/proposition.php");

class controleurPropositions {

    protected static $champs = array(
        "titreProposition" => ["text", "Nom de votre proposition"],
        "descriptionProposition" => ["text", "Description de votre proposition"],
        "montantProposition" => ["number", "Montant"],
        "dateFinProposition" => ["date", "Date de fin"]
    );

    public static function afficherPropositions() {
        if (!isset($_GET['IDGroupe']) || empty($_GET['IDGroupe'])) {
            echo "Erreur : L'ID du groupe n'a pas été spécifié.";
            return;
        }

        $IDGroupe = intval($_GET['IDGroupe']);
        $propositions = proposition::getListePropositionDansGroupe($IDGroupe);
        $membres = proposition::getListeMembreDansGroupe($IDGroupe) ?? [];
        $estAdmin = false;
        $nomGroupe = groupe::getOne($IDGroupe)->get('nomGroupe');
        $budgetGroupe = budget::getBudgetParGroupe($IDGroupe);

        if (!$propositions) $propositions = [];
        if (!$membres) $membres = [];

        if (isset($_SESSION['utilisateur'])) {
            $loginUtilisateur = $_SESSION['utilisateur']->get('loginUtilisateur');
            $estAdmin = roleDansGroupe::isAdmin($loginUtilisateur, $IDGroupe);
        }

        include('vue/debut.php');
        include('vue/proposition/lesPropositionsVue.php');
        include('vue/fin.php');
    }

    public static function creerProposition() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $IDGroupe = intval($_POST['IDGroupe'] ?? 0);
            if ($IDGroupe <= 0) {
                echo "<p class='alert alert-danger'>Erreur : L'ID du groupe n'a pas été spécifié ou est invalide.</p>";
                return;
            }

            $titreProposition = htmlspecialchars(trim($_POST['titreProposition']));
            $descriptionProposition = htmlspecialchars(trim($_POST['descriptionProposition']));
            $montantProposition = floatval($_POST['montantProposition']);
            $dateFinProposition = htmlspecialchars($_POST['dateFinProposition']);
            $loginUtilisateur = $_SESSION['utilisateur']->get('loginUtilisateur');

            if (empty($titreProposition) || empty($descriptionProposition) || $montantProposition <= 0 || empty($dateFinProposition)) {
                echo "<p class='alert alert-danger'>Tous les champs doivent être remplis correctement.</p>";
                return;
            }

            // Vérifier si une heure est spécifiée dans la dateFinProposition
            if (strlen($dateFinProposition) === 10) { // Format YYYY-MM-DD
                $dateFinProposition .= " 23:59:59";
            }

            $etatProposition = "En attente";
            $dateSoumissionProposition = date('Y-m-d H:i:s'); // Inclut l'heure actuelle

            try {
                // Insertion de la proposition
                $req = "INSERT INTO proposition 
                (etatProposition, titreProposition, descriptionProposition, dateSoumissionProposition, dateFinProposition, montantProposition, IDGroupe, loginUtilisateur)
                VALUES 
                (:etatProposition, :titreProposition, :descriptionProposition, :dateSoumissionProposition, :dateFinProposition, :montantProposition, :IDGroupe, :loginUtilisateur)";
                $stmt = Connexion::pdo()->prepare($req);
                $stmt->bindValue(':etatProposition', $etatProposition);
                $stmt->bindValue(':titreProposition', $titreProposition);
                $stmt->bindValue(':descriptionProposition', $descriptionProposition);
                $stmt->bindValue(':dateSoumissionProposition', $dateSoumissionProposition);
                $stmt->bindValue(':dateFinProposition', $dateFinProposition);
                $stmt->bindValue(':montantProposition', $montantProposition);
                $stmt->bindValue(':IDGroupe', $IDGroupe);
                $stmt->bindValue(':loginUtilisateur', $loginUtilisateur);
                $stmt->execute();

                // Ajout du rôle d'organisateur si non existant
                $reqVerifRole = "SELECT COUNT(*) FROM role_dans_groupe WHERE loginUtilisateur = :login AND IDGroupe = :idGroupe AND IDRole = 5";
                $stmtVerifRole = Connexion::pdo()->prepare($reqVerifRole);
                $stmtVerifRole->bindValue(":login", $loginUtilisateur);
                $stmtVerifRole->bindValue(":idGroupe", $IDGroupe);
                $stmtVerifRole->execute();

                if ($stmtVerifRole->fetchColumn() == 0) {
                    $reqRole = "INSERT INTO role_dans_groupe (loginUtilisateur, IDGroupe, IDRole) VALUES (:login, :idGroupe, :idRole)";
                    $stmtRole = Connexion::pdo()->prepare($reqRole);
                    $stmtRole->bindValue(":login", $loginUtilisateur);
                    $stmtRole->bindValue(":idGroupe", $IDGroupe);
                    $stmtRole->bindValue(":idRole", 5);
                    $stmtRole->execute();
                }

                $_SESSION['msgProposition'] = "La proposition a été ajoutée avec succès, vous avez été promu organisateur.";
                header("Location: routeur.php?page=propositions&action=afficherPropositions&IDGroupe=$IDGroupe");
                exit();
            } catch (PDOException $e) {
                echo "<p class='alert alert-danger'>Erreur lors de l'insertion de la proposition : " . $e->getMessage() . "</p>";
            }
        }
    }



    public static function afficherDetailsProposition() {
        if (!isset($_GET['IDProposition']) || empty($_GET['IDProposition'])) {
            echo "Erreur : L'ID de la proposition n'a pas été spécifié.";
            return;
        }

        $IDProposition = intval($_GET['IDProposition']);
        $proposition = proposition::getProposition($IDProposition);

        if (!$proposition) {
            echo "Erreur : Proposition introuvable.";
            return;
        }

        $commentaires = commentaire::getCommentairesDansProposition($IDProposition);
        $votes = vote::getVotesPourProposition($IDProposition);

        // Récupérer le vote de l'utilisateur connecté
        $utilisateurVote = null;
        if (isset($_SESSION['utilisateur'])) {
            $loginUtilisateur = $_SESSION['utilisateur']->get('loginUtilisateur');
            $utilisateurVote = vote::getVoteUtilisateur($loginUtilisateur, $IDProposition);
        }

        include('vue/debut.php');
        include('vue/proposition/detailsPropositionVue.php');
        include('vue/fin.php');
    }


    public static function ajouterCommentaire() {
        if (!isset($_POST['IDProposition']) || empty($_POST['IDProposition'])) {
            echo "Erreur : L'ID de la proposition n'a pas été spécifié.";
            return;
        }

        // Vérifiez si le texte du commentaire est présent et non vide
        if (!isset($_POST['texteCommentaire']) || empty(trim($_POST['texteCommentaire']))) {
            echo "Erreur : Le commentaire est vide.";
            return;
        }

        // Récupérez les données du formulaire
        $IDProposition = $_POST['IDProposition'];
        $texteCommentaire = trim($_POST['texteCommentaire']);
        $loginUtilisateur = $_SESSION['utilisateur']->get('loginUtilisateur'); // Récupération de l'utilisateur connecté


        // Appel au modèle pour insérer le commentaire
        $IDCommentaire = commentaire::insererCommentaire($texteCommentaire);

        if ($IDCommentaire) {
            try {
                // Associer le commentaire à la proposition
                $reqProposition = "INSERT INTO commentaire_proposition (IDCommentaire, IDProposition) VALUES (:IDCommentaire, :IDProposition)";
                $stmtProposition = Connexion::pdo()->prepare($reqProposition);
                $stmtProposition->bindValue(":IDCommentaire", $IDCommentaire);
                $stmtProposition->bindValue(":IDProposition", $IDProposition);
                $stmtProposition->execute();

                // Associer le commentaire à l'utilisateur
                $reqUtilisateur = "INSERT INTO commentaire_utilisateur (IDCommentaire, loginUtilisateur) VALUES (:IDCommentaire, :loginUtilisateur)";
                $stmtUtilisateur = Connexion::pdo()->prepare($reqUtilisateur);
                $stmtUtilisateur->bindValue(":IDCommentaire", $IDCommentaire);
                $stmtUtilisateur->bindValue(":loginUtilisateur", $loginUtilisateur);
                $stmtUtilisateur->execute();

                // Redirection après succès
                header("Location: routeur.php?page=propositions&action=afficherDetailsProposition&IDProposition=$IDProposition");
                exit();
            } catch (PDOException $e) {
                echo "<p class='alert alert-danger'>Erreur lors de l'association du commentaire : " . $e->getMessage() . "</p>";
            }
        } else {
            echo "Erreur lors de l'ajout du commentaire.";
        }
    }

    public static function supprimerCommentaire() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $IDCommentaire = intval($_POST['IDCommentaire']);
                $IDProposition = intval($_POST['IDProposition']);
                $loginUtilisateur = $_SESSION['utilisateur']->get('loginUtilisateur');

                if ($IDCommentaire <= 0 || $IDProposition <= 0) {
                    throw new Exception("Données invalides.");
                }

                // Appeler le modèle pour supprimer le commentaire
                $resultat = commentaire::supprimerCommentaire($IDCommentaire, $loginUtilisateur);

                if ($resultat) {
                    $_SESSION['msgSuppression'] = "Le commentaire a été supprimé avec succès.";
                } else {
                    $_SESSION['msgErreurSuppression'] = "Erreur lors de la suppression du commentaire.";
                }

                header("Location: routeur.php?page=propositions&action=afficherDetailsProposition&IDProposition=$IDProposition");
                exit();
            } catch (Exception $e) {
                $_SESSION['msgErreurSuppression'] = "Erreur : " . $e->getMessage();
                header("Location: routeur.php?page=propositions&action=afficherDetailsProposition&IDProposition=$IDProposition");
                exit();
            }
        }
    }


    public static function ajouterReaction() {
        if (!isset($_SESSION['utilisateur']) || empty($_SESSION['utilisateur']->get('loginUtilisateur'))) {
            echo "<p class='alert alert-danger'>Vous devez être connecté pour réagir.</p>";
            return;
        }

        if (!isset($_POST['IDCommentaire']) || empty($_POST['IDCommentaire'])) {
            echo "<p class='alert alert-danger'>Erreur : L'ID du commentaire n'a pas été spécifié.</p>";
            return;
        }

        if (!isset($_POST['typeReaction']) || empty($_POST['typeReaction'])) {
            echo "<p class='alert alert-danger'>Erreur : Le type de réaction n'a pas été spécifié.</p>";
            return;
        }

        $IDCommentaire = intval($_POST['IDCommentaire']);
        $typeReaction = htmlspecialchars(trim($_POST['typeReaction']));
        $loginUtilisateur = $_SESSION['utilisateur']->get('loginUtilisateur');

        if ($IDCommentaire <= 0) {
            echo "<p class='alert alert-danger'>Erreur : L'ID du commentaire est invalide.</p>";
            return;
        }

        $validReactions = ['Jaime', 'Jaime pas', 'Jadore', 'Mort de rire', 'Triste'];
        if (!in_array($typeReaction, $validReactions)) {
            echo "<p class='alert alert-danger'>Erreur : Le type de réaction est invalide.</p>";
            return;
        }

        $resultat = reaction::insererReaction($IDCommentaire, $typeReaction, $loginUtilisateur);

        if ($resultat === "Réaction supprimée." || $resultat === "Réaction ajoutée." || $resultat === "Réaction mise à jour.") {
            echo "<p class='alert alert-success'>$resultat</p>";
            header("Location: routeur.php?page=propositions&action=afficherDetailsProposition&IDProposition=" . $_POST['IDProposition']);
            exit();
        } else {
            echo "<p class='alert alert-danger'>$resultat</p>";
        }
    }

    public static function creerVote() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération et validation de l'ID de la proposition
            $IDProposition = intval($_POST['IDProposition'] ?? 0);
            if ($IDProposition <= 0) {
                echo "<p class='alert alert-danger'>Erreur : L'ID de la proposition est invalide ou manquant.</p>";
                return;
            }

            $typeVote = htmlspecialchars(trim($_POST['typeVote'] ?? ''));
            $dateDebutVote = date('Y-m-d H:i:s'); // Inclut l'heure actuelle
            $dateFinVote = htmlspecialchars(trim($_POST['dateFinVote'] ?? ''));
            $majoriteVote = "En cours";

            if (empty($typeVote) || empty($dateFinVote)) {
                echo "<p class='alert alert-danger'>Tous les champs doivent être remplis correctement.</p>";
                return;
            }

            // Récupérer la date de fin de la proposition
            $proposition = proposition::getProposition($IDProposition);
            if (!$proposition) {
                echo "<p class='alert alert-danger'>Erreur : Proposition introuvable.</p>";
                return;
            }

            $dateFinProposition = $proposition['dateFinProposition'];

            // Vérifier si la date de fin du vote est supérieure à la date de fin de la proposition
            if (strtotime($dateFinVote) > strtotime($dateFinProposition)) {
                $_SESSION['erreurDate'] = "La date de fin du vote ne peut pas dépasser la date de fin de la proposition.";
                header("Location: routeur.php?page=propositions&action=afficherFormulaireVote&IDProposition=$IDProposition");
                exit();
            }

            // Vérifier si la date de fin du vote est antérieure à la date de début
            if (strtotime($dateFinVote) < strtotime($dateDebutVote)) {
                $_SESSION['erreurDate'] = "La date de fin du vote doit être postérieure à la date de début.";
                header("Location: routeur.php?page=propositions&action=afficherFormulaireVote&IDProposition=$IDProposition");
                exit();
            }

            try {
                $IDVote = vote::insererVote($IDProposition, $typeVote, $dateDebutVote, $dateFinVote, $majoriteVote);

                if ($IDVote) {
                    header("Location: routeur.php?page=propositions&action=afficherDetailsProposition&IDProposition=$IDProposition");
                    exit();
                } else {
                    echo "<p class='alert alert-danger'>Erreur lors de l'insertion du vote.</p>";
                }
            } catch (PDOException $e) {
                echo "<p class='alert alert-danger'>Erreur lors de l'insertion du vote : " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            echo "<p class='alert alert-danger'>Erreur : Méthode de requête non valide.</p>";
        }
    }

    public static function modifierProposition() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $IDProposition = intval($_POST['IDProposition']);
                $titreProposition = htmlspecialchars(trim($_POST['titreProposition']));
                $descriptionProposition = htmlspecialchars(trim($_POST['descriptionProposition']));
                $montantProposition = floatval($_POST['montantProposition']);
                $dateFinProposition = htmlspecialchars(trim($_POST['dateFinProposition']));

                if (empty($titreProposition) || empty($descriptionProposition) || $montantProposition <= 0 || empty($dateFinProposition)) {
                    throw new Exception("Tous les champs doivent être remplis correctement.");
                }

                // Validation de la date et de l'heure
                if (!strtotime($dateFinProposition)) {
                    throw new Exception("La date de fin spécifiée est invalide.");
                }

                // Récupérer l'ancienne proposition pour valider
                $propositionActuelle = proposition::getProposition($IDProposition);
                if (!$propositionActuelle) {
                    throw new Exception("Proposition introuvable.");
                }

                $IDGroupe = intval($propositionActuelle['IDGroupe']);

                // Vérifiez si l'utilisateur est l'organisateur
                if (!roleDansGroupe::isOrganisateur($_SESSION['utilisateur']->get('loginUtilisateur'), $IDGroupe)) {
                    throw new Exception("Vous n'êtes pas autorisé à modifier cette proposition.");
                }

                // Mettre à jour la proposition
                $req = "UPDATE proposition
                SET titreProposition = :titreProposition,
                    descriptionProposition = :descriptionProposition,
                    montantProposition = :montantProposition,
                    dateFinProposition = :dateFinProposition
                WHERE IDProposition = :IDProposition";
                $stmt = Connexion::pdo()->prepare($req);
                $stmt->bindValue(':titreProposition', $titreProposition);
                $stmt->bindValue(':descriptionProposition', $descriptionProposition);
                $stmt->bindValue(':montantProposition', $montantProposition);
                $stmt->bindValue(':dateFinProposition', $dateFinProposition);
                $stmt->bindValue(':IDProposition', $IDProposition, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['msgModification'] = "La proposition a été modifiée avec succès.";
                header("Location: routeur.php?page=propositions&action=afficherPropositions&IDGroupe=$IDGroupe");
                exit();
            } catch (Exception $e) {
                $_SESSION['msgErreurModification'] = "Erreur : " . $e->getMessage();
                header("Location: routeur.php?page=propositions&action=afficherFormulaireModifierProposition&IDProposition=$IDProposition");
                exit();
            }
        }
    }


    public static function soumettreVote() {
        if (!isset($_SESSION['utilisateur'])) {
            die("Erreur : Vous devez être connecté pour voter.");
        }

        $loginUtilisateur = $_SESSION['utilisateur']->get('loginUtilisateur');
        $IDProposition = intval($_POST['IDProposition'] ?? 0);
        $sensVote = ($_POST['valeurVote'] === '1') ? 'Pour' : 'Contre';

        if ($IDProposition <= 0 || empty($sensVote)) {
            die("Erreur : Données de vote invalides.");
        }

        $resultat = vote::soumettreVote($loginUtilisateur, $IDProposition, $sensVote);
        $_SESSION['messageVoteSoumis'] = $resultat;

        header("Location: routeur.php?page=propositions&action=afficherDetailsProposition&IDProposition=$IDProposition");
        exit();
    }


    public static function afficherFormulaireVote() {
        $IDProposition = $_GET['IDProposition'];
        include('vue/debut.php');
        include('vue/proposition/formulaireVoteVue.php');
        include('vue/fin.php');
    }
    public static function afficherFormulaireProposition() {
        $champs = self::$champs;
        include('vue/debut.php');
        include('vue/proposition/formulaireProposition.php');
        include('vue/fin.php');
    }

    public static function afficherFormulaireModifierProposition() {
        if (!isset($_GET['IDProposition']) || empty($_GET['IDProposition'])) {
            echo "<p class='alert alert-danger'>Erreur : L'ID de la proposition n'a pas été spécifié.</p>";
            return;
        }

        $IDProposition = intval($_GET['IDProposition']);

        // Récupérer les données de la proposition
        $proposition = proposition::getProposition($IDProposition);

        if (!$proposition) {
            echo "<p class='alert alert-danger'>Erreur : Proposition introuvable.</p>";
            return;
        }

        // Vérifiez si l'utilisateur est l'organisateur
        if (!roleDansGroupe::isOrganisateur($_SESSION['utilisateur']->get('loginUtilisateur'), $proposition['IDGroupe'])) {
            echo "<p class='alert alert-danger'>Vous n'êtes pas autorisé à modifier cette proposition.</p>";
            return;
        }

        include 'vue/debut.php';
        include 'vue/proposition/formulaireModifierProposition.php';
        include 'vue/fin.php';
    }

}
