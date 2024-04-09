<?php 

class Database
{
    private static $instance = null;
    private $connection;

    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "booknetic_bot";

    private function __construct()
    {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->servername};dbname={$this->dbname}",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->exec("SET NAMES utf8");
        } catch (PDOException $e) {
            die("Bağlantı Hatası: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }


}
