<?php
namespace model;
use PDO;
use Exception;
use PDOStatement;
class MyPDO extends PDO
{
    private static ?MyPDO $instance = null;
    private function __construct($dsn, $username = null, $password = null, $options = null)
    {
        parent::__construct($dsn, $username, $password, $options);
    }
    public static function getInstance($dsn, $username = null, $password = null, $options = null): MyPDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new MyPDO($dsn, $username, $password, $options);
            } catch (Exception $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}