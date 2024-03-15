<?php

namespace App\Lib;

$vendorDir = dirname(__DIR__);
$baseDir = dirname($vendorDir);

require_once($baseDir.'/config.php');

if (!defined('MY_APP_STARTED')) {
    die("Accès refusé");
}



class Database
{
    private $host = DATABASE_HOSTNAME;
    private $dbname = DATABASE_NAME;
    private $login = DATABASE_USER;
    private $password = DATABASE_PASSWORD;
    function dbConnect()
    {

        try {
            $database = new \PDO("mysql:host=$this->host;dbname=$this->dbname", $this->login, $this->password);
            $database->exec("SET CHARACTER SET utf8");
            return $database;
        } catch (\Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
