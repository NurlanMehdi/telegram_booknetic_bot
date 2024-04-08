<?php

class Services
{
    private $conn;
    private $table_name = 'services';

    public $id;
    public $service_id;
    public $title;
    public $price;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Tüm hizmetleri getir
    public function getAllServices()
    {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Belirli bir hizmeti getir
    public function getServiceById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        return $stmt;
    }

    // Yeni bir hizmet ekle
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
