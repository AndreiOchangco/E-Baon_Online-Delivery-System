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
    <link rel="shortcut icon" href="../images/e-baon-logo.png">
    <link rel="stylesheet" href="../Css/Admin.css">
</head>

<body class="admin-body">
    <header class="admin-head">
        <!-- Hamburger on the left -->
        <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Centered header text -->
        <div class="admin-head-text">
            <h3>E-Baon</h3>
            <p>Admin</p>
        </div>
    </header>

    <div class="admin-container">

        <!-- LEFT SIDEBAR -->
        <aside class="admin-sidebar" id="sidebar">
            <!-- Logo at top -->
            <img class="admin-logo-box" src="../images/e-baon-logo-outline.png" alt="E-Baon Logo">

            <!-- MENU ITEMS -->
            <nav class="admin-menu">
                <a href="../Body/Admin/Admin_Dashboard.php" class="admin-menu-item" data-tooltip="Dashboard">
                    <span class="admin-menu-item-icon">📊</span>
                    <span class="admin-menu-item-text">Dashboard</span>
                </a>

                <a href="../Body/Admin/Admin_ManageOrder.php" class="admin-menu-item" data-tooltip="Manage Orders">
                    <span class="admin-menu-item-icon">📋</span>
                    <span class="admin-menu-item-text">Manage Order</span>
                </a>

                <a href="../Body/Admin/Admin_ManageProduct.php" class="admin-menu-item" data-tooltip="Manage Products">
                    <span class="admin-menu-item-icon">📦</span>
                    <span class="admin-menu-item-text">Manage Product</span>
                </a>

                <a href="../Body/Admin/Admin_ManageCustomer.php" class="admin-menu-item" data-tooltip="Customers">
                    <span class="admin-menu-item-icon">🙎🏻‍♂️</span>
                    <span class="admin-menu-item-text">Manage Customer</span>
                </a>

                <a href="../Body/Admin/Admin_ManageDelivery.php" class="admin-menu-item" data-tooltip="Delivery">
                    <span class="admin-menu-item-icon">🛵</span>
                    <span class="admin-menu-item-text">Manage Delivery D.</span>
                </a>

                <a href="../Body/Admin/Admin_ManageAdminAcc.php" class="admin-menu-item" data-tooltip="Admin Accounts">
                    <span class="admin-menu-item-icon">⚙️</span>
                    <span class="admin-menu-item-text">Manage Admin Acc.</span>
                </a>
            </nav>

            <!-- LOGOUT BUTTON -->
            <div class="admin-bottom-bar">
                <a href="Logout.php" class="admin-logout">LOGOUT</a>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="admin-main-content">
            <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
            <p>Use the navigation on the left to manage the E-Baon system.</p>

            <hr><br>
            <p>This is your admin dashboard main panel.</p>
        </main>
    </div>

<!-- SMART COLLAPSE SCRIPT -->
<script>
const sidebar = document.getElementById('sidebar');
const MOBILE_BREAKPOINT = 768;
let userForcedCollapse = false;

// Toggle sidebar manually
function toggleSidebar() {
    if (window.innerWidth < MOBILE_BREAKPOINT) {
        sidebar.classList.toggle('show');
    } else {
        userForcedCollapse = true;
        sidebar.classList.toggle('collapsed');
    }
}

// Close sidebar if clicking outside (mobile only)
document.addEventListener('click', function(e) {
    if (window.innerWidth >= MOBILE_BREAKPOINT) return;
    if (!sidebar.contains(e.target) && !e.target.classList.contains('sidebar-toggle')) {
        sidebar.classList.remove('show');
    }
});

// Optional: prevent scrolling content behind sidebar when open
const observer = new MutationObserver(() => {
    if (sidebar.classList.contains('show')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});
observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
</script>

</body>
</html>