<?php
session_start();
if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
    header("Location: ../../Main/Index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admin Account</title>
    <link rel="stylesheet" href="../../Css/style.css">
</head>
<body class="admin-body">

<div class="admin-dashboard">
    <header class="admin-head">
        <div class="admin-logo-box">L</div>
        <div class="admin-head-text">
            <h1>Omacha Shop</h1>
            <p>admin - Manage Admin Acc.</p>
        </div>
    </header>

    <div class="admin-separator"></div>

    <div style="padding:16px 24px;">
        <h2>Manage Admin Acc.</h2>
        <p>Here you can later build the form for adding and editing admin users.</p>
        <p><a href="../../Main/Admin.php">← back to dashboard</a></p>
    </div>
</div>

</body>
</html>
