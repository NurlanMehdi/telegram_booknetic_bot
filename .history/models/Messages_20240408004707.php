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
    

    public function addMessage($chatId, $messageId, $updateId, $text, $username)
    {
        $query = "INSERT INTO " . $this->table_name . " (chat_id, message_id, update_id, message, username) VALUES (:chat_id, :message_id, :update_id, :message, :username)";
    
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':chat_id', $chatId);
        $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
        $stmt->bindParam(':update_id', $updateId, PDO::PARAM_INT);
        $stmt->bindParam(':message', $text, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
}
