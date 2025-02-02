<?php
require_once ("modele/utilisateur.php");
require_once ("modele/objet.php");

class controleurConnexion {

    protected static string $classe = "utilisateur";
    protected static string $identifiant = "loginUtilisateur";
    protected static $champs = array (
        "login" => ["text", "login"],
        "mdp" => ["password", "mot de passe"],
    );

    public static function affichage() {
        $champs = self::$champs;
        $classe = self::$classe;
        $identifiant = self::$identifiant;

        include ("vue/debut.php");
        if (utilisateur::userIsConnected() && isset($_SESSION['utilisateur'])) {
            include("vue/utilisateur/deconnexionVue.php");
        } else if (isset($_POST['login']) && isset($_POST['mdp'])) {
            self::traiterConnexion();
        } else {
            include("vue/utilisateur/connexionVue.php");
        }
        include ("vue/fin.php");
    }

    public static function traiterConnexion() {
        $login = $_POST['login'];
        $mdp = $_POST['mdp'];

        $utilisateur = utilisateur::getOne($login);
        if ($utilisateur && ($mdp == $utilisateur->get("mdpUtilisateur"))) {
            $utilisateur->connecter();
            $_SESSION['messageConnexion'] = "Vous êtes connecté en tant que <strong>" . $login . "</strong>";
            header("Location: routeur.php?page=main");
        } else {
            header("Location: routeur.php?page=connexion");
            $_SESSION['erreurConnexion'] = "Votre login ou mot de passe est incorrect... Rééssayez !";
        }
    }


    public static function deconnecterUtilisateur() {
        $titre = "Déconnexion";
        utilisateur::deconnecter();
        $_SESSION['messageDeconnexion'] = "Vous vous êtes déconnecté avec succès !";
        header("Location: routeur.php?page=main");
    }
}
?>
