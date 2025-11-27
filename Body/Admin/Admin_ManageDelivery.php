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
    <title>Manage Delivery D.</title>
      <link rel="shortcut icon" href="../../images/e-baon-logo.png">
    <link rel="stylesheet" href="../../Css/Admin.css">
    <link rel="stylesheet" href="../../Css/Admin_css/Admin_ManageDelivery.css">
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

    <div class="delivery-pill-wrap">
        <button class="admin-pill">🚚 Manage Delivery D.</button>
    </div>

    <div class="delivery-wrapper">
        <div class="delivery-card">

            <div class="delivery-search-row">
                <input type="text" placeholder="Search Delivery Driver">
                <button class="delivery-search-btn">🔍</button>
            </div>

            <table class="delivery-table">
                <thead>
                    <tr>
                        <th style="width:80px;">ID</th>
                        <th>Driver Name</th>
                        <th style="width:160px;">Status</th>
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
