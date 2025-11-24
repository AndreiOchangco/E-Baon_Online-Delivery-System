<?php
session_start();
require "../Connection/Connection.php";

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

$sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":username", $username);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $user["password"] === $password) {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["role"] = $user["role"];

    if ($user["role"] === "admin") {
        header("Location: Admin.php");
    } elseif ($user["role"] === "customer") {
        header("Location: Customer.php");
    } elseif ($user["role"] === "delivery") {
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
