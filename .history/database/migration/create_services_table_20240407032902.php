<?php

require_once __DIR__ . '../../../config/database.php'; // Adjust the path to your database.php file

try {
    $db = new Database(); // Create a new Database instance
    $conn = $db->getConnection(); // Get the PDO connection from Database instance

    $sql = "CREATE TABLE IF NOT EXISTS services (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";

    $stmt = $conn->prepare($sql); // Prepare the SQL statement
    $stmt->execute(); // Execute the SQL statement

    echo "Table 'services' created successfully.";
} catch (PDOException $e) {
    die("Error creating table: " . $e->getMessage());
}
?>
