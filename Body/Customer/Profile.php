<?php
session_start();
include '../../Connection/connection.php'; // Adjust path

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
    header("Location: ../../Main/Index.php");
    exit();
}

$userID = $_SESSION["user_id"];
$username = $_SESSION["username"] ?? "Customer";

// Fetch user info
$sqlUser = "SELECT username, user_age, user_sex FROM users WHERE id = ?";
$stmtUser = mysqli_prepare($conn, $sqlUser);
mysqli_stmt_bind_param($stmtUser, "i", $userID);
mysqli_stmt_execute($stmtUser);
mysqli_stmt_bind_result($stmtUser, $dbUsername, $dbAge, $dbSex);
mysqli_stmt_fetch($stmtUser);
mysqli_stmt_close($stmtUser);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="../../Css/Customer_css/Profile.css">
    <link rel="stylesheet" href="../../Css/DisableStyles.css">
    <link rel="stylesheet" href="../../Css/CustomerSidebar.css">
    <link rel="stylesheet" href="../../Css/Customer.css">
</head>
<body class="profile-body">

<header class="customer-head noselect">
    <div class="customer-head-text noselect">
        <h3>E-Baon</h3>
        <p>Customer</p>
    </div>

    <button class="sidebar-toggle noselect" onclick="toggleSidebar()">☰</button>
    <div class="customer-head-search">
        <span class="customer-search-icon">🔍</span>
        <input type="text" placeholder="Search for shop">
    </div>
</header>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- LEFT SIDEBAR -->
<aside class="admin-sidebar noselect" id="sidebar">
    <!-- Logo at top -->
    <img class="admin-logo-box noselect" src="../../images/e-baon-logo.png" alt="E-Baon Logo">

    <!-- MENU ITEMS -->
    <nav class="admin-menu noselect">

        <a href="../Main/Admin.php"
        class="admin-menu-item"
        data-tooltip="Dashboard">
            <span class="admin-menu-item-icon">📊</span>
            <span class="admin-menu-item-text">Dashboard</span>
        </a>

        <a href="../Body/Admin/Admin_ManageOrder.php"
        class="admin-menu-item"
        data-tooltip="Manage Order">
            <span class="admin-menu-item-icon">📋</span>
            <span class="admin-menu-item-text">Manage Order</span>
        </a>

        <a href="../Body/Admin/Admin_ManageProduct.php"
        class="admin-menu-item"
        data-tooltip="Manage Product">
            <span class="admin-menu-item-icon">📦</span>
            <span class="admin-menu-item-text">Manage Product</span>
        </a>

        <a href="../Body/Admin/Admin_ManageCustomer.php"
        class="admin-menu-item"
        data-tooltip="Manage Customer">
            <span class="admin-menu-item-icon">🙎🏻‍♂️</span>
            <span class="admin-menu-item-text">Manage Customer</span>
        </a>

        <a href="../Body/Admin/Admin_ManageDelivery.php"
        class="admin-menu-item"
        data-tooltip="Manage Delivery">
            <span class="admin-menu-item-icon">🛵</span>
            <span class="admin-menu-item-text">Manage Delivery D.</span>
        </a>

        <a href="../Body/Admin/Admin_ManageAdminAcc.php"
        class="admin-menu-item"
        data-tooltip="Manage Admin">
            <span class="admin-menu-item-icon">⚙️</span>
            <span class="admin-menu-item-text">Manage Admin Acc.</span>
        </a>

    </nav>

<!-- LOGOUT BUTTON -->
<div class="admin-bottom-bar">
    <a href="Logout.php" class="admin-logout">LOGOUT</a>
</div>

</aside>

<main class="profile-main">

    <section class="profile-top-card">
        <img src="../../images/customer/placeholder.jpg" class="profile-avatar" alt="Profile">
        <div class="profile-user-info">
            <div class="profile-name"><?php echo htmlspecialchars($dbUsername); ?></div>
            <div class="profile-age-sex">
                Age: <?php echo $dbAge; ?> | Sex: <?php echo $dbSex == 0 ? 'Male' : 'Female'; ?>
            </div>
            <div class="profile-view-link">View profile</div>
        </div>
    </section>

    <section class="profile-orders-row">
    <?php
    $sqlOrders = "SELECT o.orderQuantity, o.orderPrice, p.productName, p.shopName, p.product_image
                FROM orders o
                JOIN products p ON o.product_id = p.productID
                WHERE o.user_id = ?
                ORDER BY o.order_date DESC";

    $stmtOrders = mysqli_prepare($conn, $sqlOrders);
    mysqli_stmt_bind_param($stmtOrders, "i", $userID);
    mysqli_stmt_execute($stmtOrders);
    mysqli_stmt_bind_result($stmtOrders, $orderQuantity, $orderPrice, $productName, $shopName, $productImage);

    while (mysqli_stmt_fetch($stmtOrders)):
    ?>
        <div class="profile-order-card">
            <div class="profile-order-header">
                <img src="../../images/customer/placeholder.jpg" class="profile-shop-icon" alt="Shop">
                <div class="profile-shop-address"><?php echo htmlspecialchars($shopName); ?></div>
            </div>
            <div class="profile-order-lines">
                <div class="profile-order-line">
                    <span><?php echo htmlspecialchars($productName); ?></span>
                    <span>₱<?php echo number_format($orderPrice, 2); ?></span>
                </div>
            </div>
            <div class="profile-order-footer">
                <button class="profile-total-btn">Qty: <?php echo $orderQuantity; ?></button>
            </div>
        </div>
    <?php endwhile;
    mysqli_stmt_close($stmtOrders);
    ?>
    </section>

</main>

<footer class="customer-bottom-nav">
    <a href="../../Body/Customer/Cart.php" class="bottom-icon">🛒</a>
    <div class="bottom-status">No Order Yet</div>
    <a href="../../Body/Customer/Profile.php" class="bottom-icon">👤</a>
</footer>

<script src="../../Javascript/Profile.js"></script>
<script>
const sidebar = document.getElementById("sidebar");
const backdrop = document.getElementById("sidebarBackdrop");
const MOBILE_BREAKPOINT = 768;

const savedState = localStorage.getItem("customerSidebarState") || "collapsed";

function applySidebarState() {
    if (window.innerWidth >= MOBILE_BREAKPOINT) {
        sidebar.classList.toggle("collapsed", savedState !== "expanded");
        sidebar.classList.remove("expanded-modal");
        backdrop.classList.remove("show");
    } else {
        sidebar.classList.toggle("expanded-modal", savedState === "expanded");
        sidebar.classList.remove("collapsed");
        backdrop.classList.toggle("show", savedState === "expanded");
    }
    sidebar.classList.add("visible");
}

applySidebarState();

function toggleSidebar() {
    if (window.innerWidth < MOBILE_BREAKPOINT) {
        const open = sidebar.classList.contains("expanded-modal");
        sidebar.classList.toggle("expanded-modal", !open);
        backdrop.classList.toggle("show", !open);
        localStorage.setItem("customerSidebarState", !open ? "expanded" : "collapsed");
    } else {
        const isCollapsed = sidebar.classList.contains("collapsed");
        sidebar.classList.toggle("collapsed", !isCollapsed);
        localStorage.setItem("customerSidebarState", isCollapsed ? "expanded" : "collapsed");
    }
}

backdrop.addEventListener("click", () => {
    sidebar.classList.remove("expanded-modal");
    backdrop.classList.remove("show");
    localStorage.setItem("customerSidebarState", "collapsed");
});

document.addEventListener("click", (e) => {
    if (window.innerWidth >= MOBILE_BREAKPOINT) return;
    if (!sidebar.contains(e.target) && !e.target.classList.contains("sidebar-toggle")) {
        sidebar.classList.remove("expanded-modal");
        backdrop.classList.remove("show");
        localStorage.setItem("customerSidebarState", "collapsed");
    }
});

window.addEventListener("resize", applySidebarState);
</script>

</body>
</html>