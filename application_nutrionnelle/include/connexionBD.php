<?php
require_once("configuration.php");

if (!class_exists('connexionBD')) {
    class connexionBD
    {
        private static $instance = null;

        private function __construct() {}

        public static function getInstance()
        {
            if (self::$instance == null) {
                try {
                    $dns = "mysql:host=" . HOST . ";dbname=" . DONNEE . ";charset=utf8";
                    self::$instance = new PDO($dns, USER, PASSWORD);
                    self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    self::$instance->exec("SET character_set_results = 'utf8'");
                    self::$instance->exec("SET character_set_client = 'utf8'");
                    self::$instance->exec("SET character_set_connection = 'utf8'");
                } catch (PDOException $e) {
                    die("Erreur de connexion" . $e->getMessage());
                }
            }
            return self::$instance;
        }

        public static function close()
        {
            self::$instance = null;
        }
    }
}
