<?php
session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
    header("Location: ../../Main/Index.php");
    exit();
}

$username = $_SESSION["username"] ?? "Admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Product</title>
    <link rel="stylesheet" href="../../Css/Admin.css">
    <link rel="stylesheet" href="../../Css/Admin_css/Admin_ManageProduct.css">
</head>
<body class="admin-body">
    
<div class="admin-dashboard">

    <header class="admin-head">
        <div class="admin-logo-box">L</div>
        <div class="admin-head-text">
            <h1>Omacha Shop</h1>
            <p>admin</p>
        </div>
    </header>

    <div class="admin-separator"></div>

    <div class="product-pill-wrap">
        <button class="admin-pill">📦 Manage Product</button>
    </div>

    <div class="product-wrapper">
        <div class="product-card">

            <div class="product-search-row">
                <input type="text" placeholder="Search Product Name">
                <button class="product-search-btn">🔍</button>
            </div>

            <table class="product-table">
                <thead>
                    <tr>
                        <th style="width:80px;">ID</th>
                        <th>Product Name</th>
                        <th style="width:140px;">Category</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
    </div>

    <div class="admin-bottom-bar">
        <a href="../../Main/Admin.php" class="btn-login admin-logout">Back to Dashboard</a>
    </div>

</div>

</body>
</html>
