<?php
session_start();
require "../Connection/Connection.php";

$username = $_POST['username'] ?? "";
$password = $_POST['password'] ?? "";
$role     = $_POST['role'] ?? "";

if ($username === "" || $password === "" || $role === "") {
    $_SESSION['login_error'] = "Please fill in all fields.";
    header("Location: Index.php");
    exit();
}

$sql = "SELECT * FROM users WHERE username = :username AND role = :role";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":username", $username);
$stmt->bindParam(":role", $role);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $user["password"] === $password) {
    $_SESSION["user_id"]  = $user["id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["role"]     = $user["role"];

    if ($role === "admin") {
        header("Location: Admin.php");
    } elseif ($role === "customer") {
        header("Location: Customer.php");
    } else {
        header("Location: Delivery_D.php");
    }
    exit();
} else {
    $_SESSION["login_error"] = "Incorrect username, password or role.";
    header("Location: Index.php");
    exit();
}
