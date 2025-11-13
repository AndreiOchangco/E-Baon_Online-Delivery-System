<?php
session_start();
require '../Admin/config.php'; // updated path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['userName']);
    $password = trim($_POST['password']);

    // Admin login
    $adminAccounts = ['admin' => 'admin'];
    if (isset($adminAccounts[$username]) && $password === $adminAccounts[$username]) {
        $_SESSION['user'] = $username;
        header('Location: ../Admin/main/index.php');
        exit();
    }

    // Normal user login
    $stmt = $conn->prepare("SELECT * FROM login WHERE userName = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $_SESSION['user'] = $username;
        header('Location: main/index.php'); // normal user dashboard
        exit();
    } else {
        header('Location: login.html'); // redirect on failure
        exit();
    }
}
?>