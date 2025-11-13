<?php
session_start();
require '../../Admin/config.php'; // path to your DB config

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $username = trim($_POST['userName']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // plain password

    $stmt = $conn->prepare("INSERT INTO login (name, userName, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $username, $email, $password);
    $stmt->execute();

    // Redirect to login page after registration
    header('Location: login.html');
    exit();
}
?>