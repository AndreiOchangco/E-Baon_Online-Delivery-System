<?php 
session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
    header("Location: ../../Main/Index.php");
    exit();
}

$username = $_SESSION["username"] ?? "Customer";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="../../Css/Customer_css/Profile.css">
</head>
<body class="profile-body">

<header class="profile-head">
    <div class="profile-logo-box">L</div>

    <div class="profile-head-text">
        <h1>Omacha Shop</h1>
        <p>customer</p>
    </div>

    <div class="profile-menu-wrap">
        <div class="profile-menu-box">
            <button type="button" class="profile-menu-toggle">☰</button>
            <a href="../../Main/Customer.php" class="profile-menu-back">⬅</a>
        </div>
    </div>
</header>

<main class="profile-main">

    <section class="profile-top-card">
        <img src="../../Image/customer/placeholder.jpg" class="profile-avatar" alt="Profile">
        <div class="profile-user-info">
            <div class="profile-name">
                <?php echo htmlspecialchars(strtoupper($username)); ?>
            </div>
            <div class="profile-view-link">View profile</div>
        </div>
    </section>

    <section class="profile-history-bar">
        <div class="profile-history-title">Orders History</div>
    </section>

    <section class="profile-orders-row">

        <div class="profile-order-card">
            <div class="profile-order-header">
                <img src="../../Image/customer/placeholder.jpg" class="profile-shop-icon" alt="Shop">
                <div class="profile-shop-address">Address of the Shop</div>
            </div>
            <div class="profile-order-lines">
                <div class="profile-order-line"><span>Product 1</span><span>Price</span></div>
                <div class="profile-order-line"><span>Product 2</span><span>Price</span></div>
                <div class="profile-order-line"><span>Product 3</span><span>Price</span></div>
            </div>
            <div class="profile-order-footer">
                <button class="profile-total-btn">Total price</button>
            </div>
        </div>

        <div class="profile-order-card">
            <div class="profile-order-header">
                <img src="../../Image/customer/placeholder.jpg" class="profile-shop-icon" alt="Shop">
                <div class="profile-shop-address">Address of the Shop</div>
            </div>
            <div class="profile-order-lines">
                <div class="profile-order-line"><span>Product 1</span><span>Price</span></div>
                <div class="profile-order-line"><span>Product 2</span><span>Price</span></div>
                <div class="profile-order-line"><span>Product 3</span><span>Price</span></div>
            </div>
            <div class="profile-order-footer">
                <button class="profile-total-btn">Total price</button>
            </div>
        </div>

        <div class="profile-order-card">
            <div class="profile-order-header">
                <img src="../../Image/customer/placeholder.jpg" class="profile-shop-icon" alt="Shop">
                <div class="profile-shop-address">Address of the Shop</div>
            </div>
            <div class="profile-order-lines">
                <div class="profile-order-line"><span>Product 1</span><span>Price</span></div>
                <div class="profile-order-line"><span>Product 2</span><span>Price</span></div>
                <div class="profile-order-line"><span>Product 3</span><span>Price</span></div>
            </div>
            <div class="profile-order-footer">
                <button class="profile-total-btn">Total price</button>
            </div>
        </div>

        <div class="profile-order-card">
            <div class="profile-order-header">
                <img src="../../Image/customer/placeholder.jpg" class="profile-shop-icon" alt="Shop">
                <div class="profile-shop-address">Address of the Shop</div>
            </div>
            <div class="profile-order-lines">
                <div class="profile-order-line"><span>Product 1</span><span>Price</span></div>
                <div class="profile-order-line"><span>Product 2</span><span>Price</span></div>
                <div class="profile-order-line"><span>Product 3</span><span>Price</span></div>
            </div>
            <div class="profile-order-footer">
                <button class="profile-total-btn">Total price</button>
            </div>
        </div>

        <div class="profile-order-card">
            <div class="profile-order-header">
                <img src="../../Image/customer/placeholder.jpg" class="profile-shop-icon" alt="Shop">
                <div class="profile-shop-address">Address of the Shop</div>
            </div>
            <div class="profile-order-lines">
                <div class="profile-order-line"><span>Product 1</span><span>Price</span></div>
                <div class="profile-order-line"><span>Product 2</span><span>Price</span></div>
                <div class="profile-order-line"><span>Product 3</span><span>Price</span></div>
            </div>
            <div class="profile-order-footer">
                <button class="profile-total-btn">Total price</button>
            </div>
        </div>

        <div class="profile-order-card">
            <div class="profile-order-header">
                <img src="../../Image/customer/placeholder.jpg" class="profile-shop-icon" alt="Shop">
                <div class="profile-shop-address">Address of the Shop</div>
            </div>
            <div class="profile-order-lines">
                <div class="profile-order-line"><span>Product 1</span><span>Price</span></div>
                <div class="profile-order-line"><span>Product 2</span><span>Price</span></div>
                <div class="profile-order-line"><span>Product 3</span><span>Price</span></div>
            </div>
            <div class="profile-order-footer">
                <button class="profile-total-btn">Total price</button>
            </div>
        </div>

    </section>

</main>

<script src="../../Javascript/Profile.js"></script>

</body>
</html>
