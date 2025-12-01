<?php
$host = "localhost";
$dbname = "e_baon";   
$username = "root";
$password = "";

// Create mysqli connection
$conn = new mysqli($host, $username, "", $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>