<?php
namespace models\base;
use configs\Config;
use PDO;
use PDOException;

/**
 * class DbConnection : c'est classe qui gère la connexion à la base de données.
 */
class DbConnection
{
    private static $pdo;

    private static $instance = null;

    private function __construct() 
    {
        $dsn  = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME;
        $user = Config::DB_USER;
        $pass = Config::DB_PASS;

        try 
        {
            self::$pdo = new PDO($dsn, $user, $pass);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch (PDOException $e) 
        {
            echo "erreur de pdo {$e->getMessage()}";
        }
    }

    /**
     * Fonction permettant d'avoir un instance de PDO
     * @return mixed renvoi une instance de $pdo
     */
    public function getPdo() : mixed
    {
        return self::$pdo;
    }

    /**
     * Fonction permettant d'avoir un instance de la classe DbConnection s'il n'y en a pas encore
     * @return mixed renvoi une instance de DbConnection
     */
    public static function getInstance() : mixed
    {
        if (self::$instance === null) 
        {
            self::$instance = new self();
        }

        return self::$instance;
    }
}