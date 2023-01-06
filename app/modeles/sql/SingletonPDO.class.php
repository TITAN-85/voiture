<?php
 
class SingletonPDO extends PDO
{
    private static $instance = null;

    const DB_SERVEUR  = 'db5011450633.hosting-data.io';
    const DB_NOM      = 'dbs9660527';
    const DB_DSN      = 'mysql:host='. self::DB_SERVEUR .';dbname='. self::DB_NOM.';charset=utf8'; 
    const DB_LOGIN    = 'dbu2951294';
    const DB_PASSWORD = 'SpawN2012ivoiture';

    // WEBDEV:
    // const DB_SERVEUR  = 'localhost';
    // const DB_NOM      = 'e2295298';
    // const DB_DSN      = 'mysql:host='. self::DB_SERVEUR .';dbname='. self::DB_NOM.';charset=utf8'; 
    // const DB_LOGIN    = 'e2295298';
    // const DB_PASSWORD = 'N2l9MjSSqxLCqU33D1PR';

    private function __construct() {
      $options = [
        PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION, // Gestion des erreurs par des exceptions de la classe PDOException
        PDO::ATTR_EMULATE_PREPARES  => false                   // Préparation des requêtes non émulée
      ];
      parent::__construct(self::DB_DSN, self::DB_LOGIN, self::DB_PASSWORD, $options);
      $this->query("SET lc_time_names = 'fr_FR'"); // Pour afficher les jours en français
    }
  
    private function __clone (){}

    public static function getInstance() {  
      if(is_null(self::$instance))
      {
        self::$instance = new SingletonPDO();
      }
      return self::$instance;
    }
}