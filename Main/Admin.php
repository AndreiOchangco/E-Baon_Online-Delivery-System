<?php
session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
    header("Location: Index.php");
    exit();
}

$username = $_SESSION["username"] ?? "Admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../Css/Admin.css">
</head>
<body class="admin-body">
    
<div class="admin-dashboard">

    <header class="admin-head">
        <img class="admin-logo-box" src="../images/e-baon-logo-outline.png" alt="">
        <div class="admin-head-text">
            <h1>E-Baon</h1>
            <p>admin</p>
        </div>
    </header>

    <div class="admin-separator"></div>

    <div class="admin-pill-wrap">
        <button class="admin-pill">📊 dashboard</button>
    </div>

    <section class="admin-card-grid">
        <a href="../Body/Admin/Admin_ManageOrder.php" class="admin-card">
            <div class="admin-card-icon">📋</div>
            <div class="admin-card-text">Manage Order</div>
        </a>

        <a href="../Body/Admin/Admin_ManageProduct.php" class="admin-card">
            <div class="admin-card-icon">📦</div>
            <div class="admin-card-text">Manage Product</div>
        </a>

        <a href="../Body/Admin/Admin_ManageCustomer.php" class="admin-card">
            <div class="admin-card-icon">🙎🏻‍♂️</div>
            <div class="admin-card-text">Manage Customer</div>
        </a>

        <a href="../Body/Admin/Admin_ManageDelivery.php" class="admin-card">
            <div class="admin-card-icon">🛵</div>
            <div class="admin-card-text">Manage Delivery D.</div>
        </a>

        <a href="../Body/Admin/Admin_ManageAdminAcc.php" class="admin-card admin-card-large">
            <div class="admin-card-icon">⚙️</div>
            <div class="admin-card-text">Manage Admin Acc.</div>
        </a>
    </section>

    <div class="admin-bottom-bar">
        <a href="Logout.php" class="btn-login admin-logout">LOGOUT</a>
    </div>

</div>

</body>
</html>
