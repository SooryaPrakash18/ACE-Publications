<?php
$servername = "localhost";
$username = "root";
$password = "root"; // default in XAMPP
$database = "userdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
