<?php

require_once __DIR__ . '/../config/database.php';

try {
    // SQL statement to create the services table
    $sql = "CREATE TABLE IF NOT EXISTS services (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";

    // Execute the SQL statement
    $conn->exec($sql);

    echo "Table 'services' created successfully.";
} catch (PDOException $e) {
    die("Error creating table: " . $e->getMessage());
}
