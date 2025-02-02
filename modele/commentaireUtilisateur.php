<?php

require_once("objet.php");

class commentaireUtilisateur extends objet {
    protected static string $classe = "commentaire_utilisateur";
    protected static string $identifiant = "IDCommentaire";

    protected int $IDCommentaire;
    protected string $loginUtilisateur;

    // Constructeur
    public function __construct(int $IDCommentaire = null, string $loginUtilisateur = "") {
        if (!is_null($IDCommentaire)) {
            $this->IDCommentaire = $IDCommentaire;
            $this->loginUtilisateur = $loginUtilisateur;
        }
    }
}
?>