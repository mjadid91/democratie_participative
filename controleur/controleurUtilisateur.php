<?php
class controleurUtilisateur {
    public static function afficherProfil() {
        // Vérification que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur'])) {
            header("Location: routeur.php?page=connexion");
            exit();
        }

        // Récupération des informations de l'utilisateur
        $loginUtilisateur = $_SESSION['utilisateur']->get('loginUtilisateur');
        $utilisateur = utilisateur::getOne($loginUtilisateur);

        if (!$utilisateur) {
            echo "<p class='alert alert-danger'>Erreur : Utilisateur non trouvé.</p>";
            return;
        }

        // Affichage de la vue
        include('vue/debut.php');
        include('vue/utilisateur/monProfil.php'); // Vue pour afficher les informations du profil
        include('vue/fin.php');
    }
}