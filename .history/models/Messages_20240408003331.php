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
    

    public function addMessage($user_id, $message_id, $update_id, $message, $username)
    {
        try {
            $query = "INSERT INTO " . $this->table_name . " (user_id, message_id, update_id, message, username) VALUES (:user_id, :message_id, :update_id, :message, :username)";
    
            $stmt = $this->conn->prepare($query);
    
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);
            $stmt->bindParam(':update_id', $update_id, PDO::PARAM_INT);
            $stmt->bindParam(':message', $message, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
}
