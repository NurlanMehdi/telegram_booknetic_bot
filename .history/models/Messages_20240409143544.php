<?php

require_once __DIR__ . "/../config/database.php";

class Messages
{
    private $conn;
    private $table_name = "messages";

    public $id;
    public $userId;
    public $updateId;
    public $messageId;
    public $message;
    public $username;


    public function __construct()
    {
        $db = Database::getInstance();
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
            $query =
                "SELECT * FROM messages WHERE message_id = :id AND message_id != 0";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            die("Error retrieving message: " . $e->getMessage());
        }
    }

    public function getMessageByChatId($id)
    {
        try {
            $query =
                "SELECT username FROM messages WHERE chat_id = :id ORDER BY id DESC LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            die("Error retrieving message: " . $e->getMessage());
        }
    }

    public function addMessage($chatId, $messageId, $updateId, $text, $username)
    {
        $query =
            "INSERT INTO " .
            $this->table_name .
            " (chat_id, message_id, update_id, message, username) VALUES (:chat_id, :message_id, :update_id, :message, :username)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":chat_id", $chatId);
        $stmt->bindParam(":message_id", $messageId, PDO::PARAM_INT);
        $stmt->bindParam(":update_id", $updateId, PDO::PARAM_INT);
        $stmt->bindParam(":message", $text, PDO::PARAM_STR);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateLatestMessageKey($key)
    {
        try {
            $query =
                "SELECT id FROM messages WHERE message_id != 0 ORDER BY id DESC LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $updateQuery =
                "UPDATE messages SET message_key = :new_key WHERE id = :id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(":new_key", $key, PDO::PARAM_STR);
            $updateStmt->bindParam(":id", $result["id"]);

            if ($updateStmt->execute()) {
                // echo "The message_key value of the latest message has been updated.";
            } else {
                echo "Error updating message_key.";
            }
        } catch (PDOException $e) {
            die("Error updating message key: " . $e->getMessage());
        }
    }

    public function getMessageData($chatId)
    {
        try {
            $data = [];

            $data["service"] = $this->getLastMessageByMessageKey(
                $chatId,
                "service_id"
            );
            $data["date"] = $this->getLastMessageByMessageKey($chatId, "date");
            $data["time"] = $this->getLastMessageByMessageKey($chatId, "time");
            $data["info"] = $this->getLastMessageByMessageKey($chatId, "info");

            return $data;
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    private function getLastMessageByMessageKey($chatId, $messageKey)
    {
        $query =
            "SELECT m.message
                  FROM " .
            $this->table_name .
            " m
                  WHERE m.message_key = :messageKey
                  AND m.chat_id = :chatId
                  AND m.message_id != 0
                  ORDER BY m.id DESC
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":messageKey", $messageKey, PDO::PARAM_STR);
        $stmt->bindParam(":chatId", $chatId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
