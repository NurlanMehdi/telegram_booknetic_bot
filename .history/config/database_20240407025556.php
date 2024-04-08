<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booknetic_bot";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn->exec("SET NAMES utf8");
} catch (PDOException $e) {
    die("Connection Error: " . $e->getMessage());
}
