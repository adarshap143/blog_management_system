<?php

$host = 'localhost';  // Your database host (usually 'localhost' for local development)
$username = 'root';  // Your database username
$password = '';  // Your database password
$database = 'blog';  // Your database name
$port = 3306;  // Your database port

// Create a database connection
$conn = mysqli_connect($host, $username, $password, $database, $port);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
