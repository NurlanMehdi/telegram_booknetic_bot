<?php

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn->exec("SET NAMES utf8");
} catch (PDOException $e) {
    die("Connection Error: " . $e->getMessage());
}
