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
    <title>Manage Customer</title>
    <link rel="stylesheet" href="../../Css/Admin.css">
    <link rel="stylesheet" href="../../Css/Admin_css/Admin_ManageCustomer.css">
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
<!-- haha -->
    <div class="admin-separator"></div>

    <div class="admin-manage-wrap">
        <main class="admin-manage-main">

            <div class="admin-manage-title">
                <button class="admin-manage-btn">
                    <span class="admin-manage-icon">🙎🏻‍♂️</span>
                    <span>Manage Customer</span>
                </button>
            </div>

            <section class="admin-manage-card">

                <div class="admin-search-row">
                    <input
                        type="text"
                        class="admin-search-input"
                        placeholder="Search Product Name"
                    >
                    <button class="admin-search-btn" type="button">🔍</button>
                </div>

                <div class="admin-details-card">

                    <div class="admin-main-field">
                        <div class="admin-main-pill">PRODUCT NAME</div>
                    </div>

                    <div class="admin-details-grid">

                        <div class="admin-details-col">
                            <label>Name of the customer</label>
                            <input type="text" placeholder="Customer Name">

                            <label>Name of the seller</label>
                            <input type="text" placeholder="Seller Name">
                        </div>

                        <div class="admin-details-col">
                            <label>Address</label>
                            <input type="text" placeholder="Customer Address">

                            <label>Address</label>
                            <input type="text" placeholder="Seller Address">

                            <label>Amount</label>
                            <input type="number" placeholder="Amount">
                        </div>

                        <div class="admin-details-col">
                            <label>Name of the Delivery D.</label>
                            <input type="text" placeholder="Delivery Driver Name">

                            <label>Status</label>
                            <input type="text" placeholder="Status">
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
