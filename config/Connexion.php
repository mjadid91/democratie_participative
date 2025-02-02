<?php
class Connexion {
    // Les attributs static caractéristiques de la connexion
    static private $hostname = 'localhost';
    static private $database = 'projets3';
    static private $login = 'root';
    static private $password = '';

    static private $tabUTF8 = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

    // L'attribut static qui matérialisera la connexion
    static private $pdo;

    // Le getter public de cet attribut
    static public function pdo() {
        return self::$pdo;
    }

    // La fonction static de connexion qui initialise $pdo et lance la tentative de connexion
    static public function connect() {
        $h = self::$hostname;
        $d = self::$database;
        $l = self::$login;
        $p = self::$password;
        $t = self::$tabUTF8;
        try {
            self::$pdo = new PDO("mysql:host=$h;dbname=$d", $l, $p, $t);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage() . "<br>";
        }
    }
}
?>