<?php
require_once("modele/utilisateur.php");
class controleurInscription {

    protected static string $classe = "utilisateur";
    protected static string $identifiant = "loginUtilisateur";
    protected static $champs = array (
        "login" => ["text", "login"],
        "mdp" => ["password", "Mot de passe"],
        "nom" => ["text", "Nom"],
        "prenom" => ["text", "Prénom"],
        "email" => ["text", "Email"],
        "adresse" => ["text", "Adresse"],
        "cp" => ["text", "Code postal"],
        "ville" => ["text", "Ville"],
    );

    public static function affichage() {
        $champs = self::$champs;
        $classe = self::$classe;
        $identifiant = self::$identifiant;
        include("vue/debut.php");

        if (isset($_POST['login']) && isset($_POST['mdp'])) {
            self::traiterInscription();
        } else {
            include("vue/utilisateur/inscriptionVue.php");
        }
        include("vue/fin.php");
    }

    public static function traiterInscription() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newUser = new utilisateur();
            $newUser->set("loginUtilisateur", $_POST['login']);
            $newUser->set("mdpUtilisateur", $_POST['mdp']);
            $newUser->set("nomUtilisateur", $_POST['nom']);
            $newUser->set("prenomUtilisateur", $_POST['prenom']);
            $newUser->set("emailUtilisateur", $_POST['email']);
            $newUser->set("adresseUtilisateur", $_POST['adresse']);
            $newUser->set("cpUtilisateur", $_POST['cp']);
            $newUser->set("villeUtilisateur", $_POST['ville']);

            $newUser->insererTable();
            utilisateur::creerCompte();
            $_SESSION['message'] = "Votre compte a bien été créé. Vous pouvez maintenant vous connecter !";
            header("Location: routeur.php?page=connexion");
        }
    }
}

?>