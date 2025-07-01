<?php
// Database configuration
$db_host = "localhost"; // Hostname of the database server
$db_user = "root"; // Database username
$db_password = ""; // Database password
$db_name = "ticketing_db"; // Database name

// Create a connection to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
