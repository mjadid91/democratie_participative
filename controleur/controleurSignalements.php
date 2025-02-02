<?php
require_once('modele/signalement.php');
class controleurSignalements {
    // Afficher les signalements
    public static function afficherSignalements() {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur'])) {
            header("Location: routeur.php?page=connexion");
            exit();
        }

        $loginUtilisateur = $_SESSION['utilisateur']->get('loginUtilisateur');

        // Récupérer le nom du groupe où l'utilisateur est modérateur
        $sql = "SELECT g.nomGroupe 
                FROM groupe g
                JOIN role_dans_groupe rdg ON g.IDGroupe = rdg.IDGroupe
                JOIN role r ON rdg.IDRole = r.IDRole
                WHERE rdg.loginUtilisateur = :loginUtilisateur AND r.nomRole = 'Modérateur'";

        $stmt = Connexion::pdo()->prepare($sql);
        $stmt->bindValue(':loginUtilisateur', $loginUtilisateur);
        $stmt->execute();
        $nomGroupe = $stmt->fetchColumn();

        // Récupérer les signalements
        $signalements = signalement::getSignalements();

        include('vue/debut.php');
        include('vue/utilisateur/signalementsVue.php');
        include('vue/fin.php');
    }

    public static function supprimerCommentaire() {
        if (!isset($_POST['IDCommentaire']) || empty($_POST['IDCommentaire'])) {
            $_SESSION['messageErreurSuppSignalement'] = "Aucun commentaire spécifié.";
            header("Location: routeur.php?page=signalements&action=afficherSignalements");
            exit();
        }

        $IDCommentaire = intval($_POST['IDCommentaire']);

        if (signalement::supprimerCommentaire($IDCommentaire)) {
            $_SESSION['messageSignalementSupp'] = "Commentaire supprimé avec succès.";
        } else {
            $_SESSION['messageErreurSuppSignalement'] = "Erreur lors de la suppression du commentaire.";
        }

        // Redirection après traitement
        header("Location: routeur.php?page=signalements&action=afficherSignalements");
        exit();
    }
}
