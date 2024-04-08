<?php

require_once __DIR__ . '/../config/database.php';

class Services
{
    private $conn;
    private $table_name = 'services';

    public $id;
    public $service_id;
    public $title;
    public $price;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAllServices()
    {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getServiceById($id)
    {
        try {
            $query = "SELECT * FROM services WHERE id = 1";
            $stmt = $this->conn->prepare($query);-
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    var_dump($result);
            return $result;
        } catch (PDOException $e) {
            die("Error retrieving service: " . $e->getMessage());
        }
    }
    

    public function addService($service_id, $title, $price)
    {
        $query = "INSERT INTO " . $this->table_name . " (service_id, title, price) VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $service_id);
        $stmt->bindParam(2, $title);
        $stmt->bindParam(3, $price);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
