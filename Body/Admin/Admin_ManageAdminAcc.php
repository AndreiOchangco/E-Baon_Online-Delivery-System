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
    <title>Manage Admin Acc.</title>
    <link rel="stylesheet" href="../../Css/Admin.css">
    <link rel="stylesheet" href="../../Css/Admin_css/Admin_ManageAdminAcc.css">
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

    <div class="admin-manage-wrap">
        <main class="admin-manage-main">

            <div class="admin-manage-title">
                <button class="admin-manage-btn">
                    <span class="admin-manage-icon">⚙️</span>
                    <span>Manage Admin Acc.</span>
                </button>
            </div>

            <section class="admin-manage-card">

                <div class="admin-search-row">
                    <input
                        type="text"
                        class="admin-search-input"
                        placeholder="Search Admin Name"
                    >
                    <button class="admin-search-btn" type="button">🔍</button>
                </div>

                <div class="admin-details-card">

                    <div class="admin-main-field">
                        <div class="admin-main-pill">ADMIN</div>
                    </div>

                    <div class="admin-details-grid">
                        <div class="admin-details-col">
                            <label>Admin name</label>
                            <input type="text" placeholder="Sample Admin">

                            <label>Username</label>
                            <input type="text" placeholder="Sample Username">
                        </div>

                        <div class="admin-details-col">
                            <label>Address</label>
                            <input type="text" placeholder="Admin Address">

                            <label>Password</label>
                            <input type="password" placeholder="Password">
                        </div>

                        <div class="admin-details-col">
                            <label>Age</label>
                            <input type="number" placeholder="Age">

                            <label>Sex</label>
                            <input type="text" placeholder="Sex">
                        </div>
                    </div>

                    <div class="admin-admin-actions">
                        <button type="button" class="admin-btn-save">Save</button>
                        <button type="button" class="admin-btn-edit">Edit</button>
                    </div>

                </div>
            </section>

            <div class="admin-bottom-btn-wrap">
                <a href="../../Main/Admin.php" class="admin-back-btn">
                    Back to Dashboard
                </a>
            </div>

        </main>
    </div>

</div>

</body>
</html>
