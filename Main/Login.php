<?php
session_start();
require "../Connection/connection.php"; // Make sure this is mysqli connection

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: Index.php");
    exit();
}

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

if ($username === "" || $password === "") {
    $_SESSION["login_error"] = "Please fill in all fields.";
    header("Location: Index.php");
    exit();
}

// Prepare and execute mysqli statement
$sql = "SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $id, $dbUsername, $dbPassword, $role);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Check credentials
if ($dbUsername && $dbPassword === $password) {
    $_SESSION["user_id"] = $id;
    $_SESSION["username"] = $dbUsername;
    $_SESSION["role"] = $role;

    if ($role === "admin") {
        header("Location: Admin.php");
    } elseif ($role === "customer") {
        header("Location: Customer.php");
    } elseif ($role === "delivery") {
        header("Location: Delivery_D.php");
    } else {
        $_SESSION["login_error"] = "Unknown role for this account.";
        header("Location: Index.php");
    }
    exit();
} else {
    $_SESSION["login_error"] = "Incorrect username or password.";
    header("Location: Index.php");
    exit();
}
?>