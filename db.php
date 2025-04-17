<?php
// Database connection settings
$host = 'localhost'; // Change this if necessary
$db = 'agri'; // Your correct database name
$user = 'root'; // Your database username
$pass = ''; // Your database password
$charset = 'utf8mb4'; // Recommended character set

// DSN (Data Source Name) for PDO connection
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Add debugging output here


try {
    // Create PDO instance
    $pdo = new PDO($dsn, $user, $pass);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Debugging: Successfully connected
    

} catch (PDOException $e) {
    // Log the error message and stop execution in case of a connection failure
    error_log("Database connection failed: " . $e->getMessage(), 3, 'error_log.txt');
    
    // Show a generic error message to the user
    die("Could not connect to the database. Please try again later.");
}
?>
