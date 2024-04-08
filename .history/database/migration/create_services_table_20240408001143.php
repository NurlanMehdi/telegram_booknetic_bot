<?php

require_once __DIR__ . '../../../config/database.php'; // Adjust the path to your database.php file

try {
    $db = new Database(); // Create a new Database instance
    $conn = $db->getConnection(); // Get the PDO connection from Database instance

    // Query to check if the 'services' table exists
    $tableName = 'services';
    $sql = "SHOW TABLES LIKE '$tableName'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $tableExists = $stmt->rowCount() > 0;

    if (!$tableExists) {
        // Table does not exist, create it
        $createTableSql = "CREATE TABLE $tableName (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $createStmt = $conn->prepare($createTableSql);
        $createStmt->execute();

        echo "Table 'services' created successfully.";
    } else {
        echo "Table 'services' already exists.";
    }

    $tableName = 'messages';
    $sql = "SHOW TABLES LIKE '$tableName'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $tableExists = $stmt->rowCount() > 0;

    if (!$tableExists) {
        // Table does not exist, create it
        $createTableSql = "CREATE TABLE messages (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) NOT NULL,
            update_id INT(11) NOT NULL,
            message_id INT(11) NOT NULL,
            message TEXT NOT NULL,
            username VARCHAR(255)
        )";
        $createStmt = $conn->prepare($createTableSql);
        $createStmt->execute();

        echo "Table 'messages' created successfully.";
    } else {
        echo "Table 'messages' already exists.";
    }
} catch (PDOException $e) {
    die("Error creating/checking table: " . $e->getMessage());
}
?>
