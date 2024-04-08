<?php

require_once __DIR__ . '/../config/database.php';

class Messages
{
    private $conn;
    private $table_name = 'messages';

    public $id;
    public $userId;
    public $updateId;
    public $messageId;
    public $message;
    public $username;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAllMessages()
    {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getMessageById($id)
    {
        try {
            $query = "SELECT * FROM messages WHERE message_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            die("Error retrieving message: " . $e->getMessage());
        }
    }
    

    public function addMessage($user_id, $message_id, $update_id,$message,$username)
    {
        $query = "INSERT INTO " . $this->table_name . " (user_id, message_id, update_id, message ,username) VALUES (?, ?, ? , ? , ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $message_id);
        $stmt->bindParam(3, $update_id);
        $stmt->bindParam(3, $message);
        $stmt->bindParam(3, $username);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
