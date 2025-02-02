<?php

require_once("modele/invitation.php");

class controleurInvitation {


    public static function ajouterMembre() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $IDGroupe = intval($_POST['IDGroupe']);
                $loginUtilisateur = htmlspecialchars(trim($_POST['loginUtilisateur']));

                if (empty($loginUtilisateur) || $IDGroupe <= 0) {
                    throw new Exception("Les données fournies sont invalides.");
                }

                // Vérifier si l'utilisateur existe
                if (!utilisateur::utilisateurExiste($loginUtilisateur)) {
                    $_SESSION['msgErreurAjout'] = "Erreur : L'utilisateur n'existe pas.";
                    header("Location: routeur.php?page=propositions&action=afficherPropositions&IDGroupe=$IDGroupe");
                    exit();
                }

                // Vérifier si l'utilisateur est déjà membre du groupe
                $sql = "SELECT COUNT(*) FROM role_dans_groupe WHERE loginUtilisateur = :loginUtilisateur AND IDGroupe = :IDGroupe";
                $stmt = Connexion::pdo()->prepare($sql);
                $stmt->execute([':loginUtilisateur' => $loginUtilisateur, ':IDGroupe' => $IDGroupe]);
                $estDejaMembre = $stmt->fetchColumn();

                if ($estDejaMembre > 0) {
                    $_SESSION['msgErreurAjout'] = "Erreur : L'utilisateur est déjà membre de ce groupe.";
                    header("Location: routeur.php?page=propositions&action=afficherPropositions&IDGroupe=$IDGroupe");
                    exit();
                }

                // Ajouter l'utilisateur au groupe
                $resultat = utilisateur::ajouterMembreAuGroupe($IDGroupe, $loginUtilisateur);

                if ($resultat) {
                    $_SESSION['msgAjoutMembre'] = "Le membre a été ajouté avec succès.";
                } else {
                    $_SESSION['msgErreurAjout'] = "Erreur : L'utilisateur n'a pas pu être ajouté au groupe.";
                }

                header("Location: routeur.php?page=propositions&action=afficherPropositions&IDGroupe=$IDGroupe");
                exit();
            } catch (Exception $e) {
                $_SESSION['msgErreurAjout'] = "Erreur : " . $e->getMessage();
                header("Location: routeur.php?page=propositions&action=afficherPropositions&IDGroupe=$IDGroupe");
                exit();
            }
        }
    }

    public static function supprimerMembre() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $IDGroupe = intval($_POST['IDGroupe']);
                $loginUtilisateur = htmlspecialchars(trim($_POST['loginUtilisateur']));

                if (empty($loginUtilisateur) || $IDGroupe <= 0) {
                    throw new Exception("Les données fournies sont invalides.");
                }

                // Vérifiez si l'utilisateur est membre du groupe
                if (!utilisateur::estMembreDuGroupe($IDGroupe, $loginUtilisateur)) {
                    $_SESSION['msgErreurSuppression'] = "Erreur : L'utilisateur n'est pas membre de ce groupe.";
                    header("Location: routeur.php?page=propositions&action=afficherPropositions&IDGroupe=$IDGroupe");
                    exit();
                }

                // Supprimer le membre
                $resultat = utilisateur::supprimerMembreDuGroupe($IDGroupe, $loginUtilisateur);

                if ($resultat) {
                    $_SESSION['msgSuppressionMembre'] = "Le membre a été supprimé avec succès.";
                } else {
                    $_SESSION['msgErreurSuppression'] = "Erreur : Impossible de supprimer l'utilisateur.";
                }

                header("Location: routeur.php?page=propositions&action=afficherPropositions&IDGroupe=$IDGroupe");
                exit();
            } catch (Exception $e) {
                $_SESSION['msgErreurSuppression'] = "Erreur : " . $e->getMessage();
                header("Location: routeur.php?page=propositions&action=afficherPropositions&IDGroupe=$IDGroupe");
                exit();
            }
        }
    }

    // Méthode pour traiter une invitation
    public static function accepterInvitation() {
        if (!isset($_GET['IDInvitation'])) {
            echo "<p class='alert alert-danger'>Erreur : ID de l'invitation manquant.</p>";
            return;
        }

        $IDInvitation = intval($_GET['IDInvitation']);

        try {
            // Vérifier si l'invitation existe
            $invitation = invitation::getInvitationById($IDInvitation);

            if ($invitation && $invitation['statutInvitation'] === 'En attente') {
                // Marquer l'invitation comme acceptée
                invitation::updateStatutInvitation($IDInvitation, 'Acceptée');

                echo "<p class='alert alert-success'>Invitation acceptée pour l'email {$invitation['emailInvite']}.</p>";
            } else {
                echo "<p class='alert alert-warning'>Invitation introuvable ou déjà traitée.</p>";
            }
        } catch (Exception $e) {
            echo "<p class='alert alert-danger'>Erreur lors du traitement de l'invitation : {$e->getMessage()}</p>";
        }
    }

    // Générer et afficher un lien d'acceptation en local
    public static function afficherLienInvitation($IDInvitation, $emailInvite, $IDGroupe) {
        $lienAcceptation = "http://localhost/votre-projet/routeur.php?page=invitation&action=accepter&IDInvitation=$IDInvitation";
        echo "<p>Invitation envoyée à : $emailInvite</p>";
        echo "<p>Lien d'acceptation (test en local) : <a href=\"$lienAcceptation\" target=\"_blank\">$lienAcceptation</a></p>";
    }


}
?>
