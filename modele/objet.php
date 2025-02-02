<?php
class objet {

    public function set($attribut, $valeur) : void{
        $this->$attribut = $valeur;
    }

    public function get($attribut) {
        return $this->$attribut;
    }

    public function getAll() {
        $classe = static::$classe;
        $requete = "SELECT * FROM $classe";
        $resultat = Connexion::pdo()->query($requete);
        $resultat->setFetchMode(PDO::FETCH_CLASS, $classe);
        $tab = $resultat->fetchAll();
        return $tab;
    }

    public static function getOne($id) {
        // récupère toutes informations d'une ligne de la table
        $classeRecuperee = static::$classe;
        $identifiant = static::$identifiant;

        $requetePreparee = "SELECT * FROM $classeRecuperee WHERE $identifiant = :id_tag;";
        $resultat = Connexion::pdo()->prepare($requetePreparee);

        $tags = array("id_tag" => $id);
        try{
            $resultat->execute($tags);
            $resultat->setFetchmode(PDO::FETCH_CLASS,$classeRecuperee);

            $element = $resultat->fetch();
            return $element;
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function insererTable() {
        $attributs = get_object_vars($this); // Récupère les attributs et leurs valeurs de l'objet
        $classe = static::$classe;

        $colonnes = implode(", ", array_keys($attributs)); // Génère la liste des colonnes
        $valeurs = ":" . implode(", :", array_keys($attributs)); // Génère les paramètres pour les valeurs


         $req = "INSERT INTO $classe ($colonnes) VALUES ($valeurs)";
         $resultat = Connexion::pdo()->prepare($req);

        // Associe chaque attribut de l'objet aux paramètres dans la requête
        foreach ($attributs as $attribut => $valeur) {
            $resultat->bindValue(":$attribut", $valeur);
        }

        try {
            $resultat->execute();
        } catch (PDOException $e) {
            echo "<p class='alert alert-danger'>Erreur : d'insertion dans la table $classe : " . $e->getMessage() . "</p>";
        }
    }
}
?>