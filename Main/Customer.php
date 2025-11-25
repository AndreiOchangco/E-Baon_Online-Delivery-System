<?php
session_start();
if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
    header("Location: Index.php");
    exit();
}
$username = $_SESSION["username"] ?? "Customer";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Page</title>
    <link rel="stylesheet" href="../Css/Customer.css">
    <link rel="stylesheet" href="../Css/main.css">
</head>
<body class="mainpage-body">
    <div class="mainpage-dashboard">

        <header class="mainpage-head">
            <img class="mainpage-logo-box" src="../images/e-baon-logo.png" alt="">
            <div class="mainpage-head-text">
                <h1>E-Baon</h1>
                <p>admin</p>
            </div>
        </header>

        <div class="mainpage-separator"></div>

        <div class="mainpage-pill-wrap">
            <button class="mainpage-pill">📊 dashboard</button>
        </div>

        <section class="mainpage-card-grid">
            <a href="#" class="mainpage-card">
                <div class="mainpage-card-icon">📋</div>
                <div class="mainpage-card-text">Draft</div>
            </a>
        </section>

        <div class="mainpage-bottom-bar">
            <a href="Logout.php" class="btn-login mainpage-logout">LOGOUT</a>
        </div>

    </div>
</body>
</html>
