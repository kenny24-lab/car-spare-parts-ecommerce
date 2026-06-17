<?php



require_once __DIR__ . "/constants.php";



// Database Configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "car_spare_parts";

// Create MySQLi Connection
$conn = new mysqli($host, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Set Character Encoding
$conn->set_charset("utf8mb4");




?>