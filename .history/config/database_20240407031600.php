<?php

class Database
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "booknetic_bot";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->servername};dbname={$this->dbname}",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET NAMES utf8");
        } catch (PDOException $e) {
            die("Connection Error: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    // Diğer veritabanı işlemleri için gerekli metotları ekleyebilirsiniz
}
