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
    <title>Customer Homepage</title>
    <link rel="stylesheet" href="../Css/Customer.css">
</head>
<body class="customer-body">

<header class="customer-head">
    <div class="customer-logo-box">L</div>
    <div class="customer-head-text">
        <h1>Omacha Shop</h1>
        <p>customer</p>
    </div>
</header>

<div class="customer-separator"></div>

<main class="customer-main">

    <div class="customer-search-row">
        <div class="customer-search-box">
            <span class="customer-search-icon">🔍</span>
            <input type="text" placeholder="Search for shop">
        </div>
    </div>

    <div class="customer-promo-row">
        <div class="promo-banner-box">
            <img src="../Image/customer/placeholder.jpg" class="promo-banner" alt="Promo banner">
            <div class="promo-banner-text">Placeholder Text</div>
        </div>
    </div>

    <div class="customer-category-header">
        <span class="customer-category-label">Category</span>
        <span class="customer-category-line"></span>
    </div>

    <div class="customer-category-row">
        <div class="category-circle"></div>
    </div>

    <section class="customer-shop-row">

        <div class="shop-column">
            <div class="shop-card">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-picture">
                    <img src="../Image/customer/placeholder.jpg" class="shop-img" alt="Product 1">
                </div>
                <div class="shop-info">Name of the product / price</div>
                <button class="shop-add-btn">Add</button>
            </div>
        </div>

    </section>

</main>

<footer class="customer-bottom-nav">
    <button class="bottom-icon">🛒</button>
    <div class="bottom-status">No Order Yet</div>
    <button class="bottom-icon">👤</button>
</footer>

</body>
</html>
