<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // set your password if applicable
$db   = 'e-baon_delivery-system';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}
?>