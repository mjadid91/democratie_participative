<?php
spl_autoload_register(function ($class) {
    $file = __DIR__ . "/modele/" . $class . ".php";
    if (file_exists($file)) {
        require_once $file;
    }
});

session_start();

$pages = ["main","utilisateur", "connexion", "inscription", "deconnexion", "groupes", "propositions", "signalements", "invitation"];
$actions = ["deconnecterUtilisateur", "connexion", "inscription", "affichage", "afficherFormulaireGroupe",
            "afficherPropositions", "afficherDetailsProposition", "afficherFormulaireProposition", "afficherProfil", "afficherFormulaireVote", "creerVote",
            "creerGroupe", "creerProposition", "ajouterCommentaire", "ajouterReaction", "soumettreVote", "afficherSignalements", "afficherFormulaireModifierGroupe", "afficherFormulaireModifierProposition", "modifierGroupe", "modifierProposition", "ajouterMembre", "supprimerMembre", "supprimerCommentaire"];

$page = "main";
$action = "affichage";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["page"]) && in_array($_POST["page"], $pages)) {
        $page = $_POST["page"];
    }
    if (isset($_POST["action"]) && in_array($_POST["action"], $actions)) {
        $action = $_POST["action"];
    }
} else {
    if (isset($_GET["page"]) && in_array($_GET["page"], $pages)) {
        $page = $_GET["page"];
    }
    if (isset($_GET["action"]) && in_array($_GET["action"], $actions)) {
        $action = $_GET["action"];
    }
}

$controleur = "controleur" . ucfirst($page);
require_once "controleur/$controleur.php";
require_once "config/Connexion.php";

Connexion::connect();

$controleur::$action();
?>
