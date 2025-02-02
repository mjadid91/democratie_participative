<?php
require_once("objet.php");
class commentaireProposition extends objet {
    protected static string $classe = "commentaire_proposition";
    protected static string $identifiant = "IDCommentaire";

    protected int $IDCommentaire;
    protected int $IDProposition;

    // Constructeur
    public function __construct(int $IDCommentaire = null, int $IDProposition = null) {
        if (!is_null($IDCommentaire)) {
            $this->IDCommentaire = $IDCommentaire;
            $this->IDProposition = $IDProposition;
        }
    }
}
?>