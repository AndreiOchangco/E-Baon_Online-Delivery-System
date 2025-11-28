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
    <title>Manage Order | Admin</title>
    <link rel="shortcut icon" href="../../images/e-baon-logo.png">
    <link rel="stylesheet" href="../../Css/Admin.css">
    <link rel="stylesheet" href="../../Css/Admin_css/Admin_ManageOrder.css">
</head>

<body class="admin-body">
    <header class="admin-head">
        <div class="admin-head-text">
            <h3>E-Baon</h3>
            <p>Admin</p>
        </div>

        <!-- Sidebar Toggle -->
        <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
    </header>

    <div class="admin-container">

        <!-- LEFT SIDEBAR -->
        <aside class="admin-sidebar" id="sidebar">
            <!-- Logo at top -->
            <img class="admin-logo-box" src="../../images/e-baon-logo.png" alt="E-Baon Logo">

            <!-- MENU ITEMS -->
            <nav class="admin-menu">

                <a href="../../Main/Admin.php"
                class="admin-menu-item">
                    <span class="admin-menu-item-icon">📊</span>
                    <span class="admin-menu-item-text">Dashboard</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageOrder.php"
                class="admin-menu-item">
                    <span class="admin-menu-item-icon">📋</span>
                    <span class="admin-menu-item-text">Manage Order</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageProduct.php"
                class="admin-menu-item">
                    <span class="admin-menu-item-icon">📦</span>
                    <span class="admin-menu-item-text">Manage Product</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageCustomer.php"
                class="admin-menu-item">
                    <span class="admin-menu-item-icon">🙎🏻‍♂️</span>
                    <span class="admin-menu-item-text">Manage Customer</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageDelivery.php"
                class="admin-menu-item">
                    <span class="admin-menu-item-icon">🛵</span>
                    <span class="admin-menu-item-text">Manage Delivery D.</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageAdminAcc.php"
                class="admin-menu-item">
                    <span class="admin-menu-item-icon">⚙️</span>
                    <span class="admin-menu-item-text">Manage Admin Acc.</span>
                </a>

            </nav>

        <!-- LOGOUT BUTTON -->
        <div class="admin-bottom-bar">
            <a href="../../Main/Logout.php" class="admin-logout">LOGOUT</a>
        </div>

        </aside>


        <!-- MAIN CONTENT AREA -->
        <main class="admin-main-content">
            <div class="order-wrapper">
                <div class="order-card">

                    <div class="order-search-row">
                        <input type="text" placeholder="Search Order Name">
                        <button class="order-search-btn">🔍</button>
                    </div>

                    <table class="order-table">
                        <thead>
                            <tr>
                                <th style="width: auto; text-align: center;">ID</th>
                                <th style="width: auto; text-align: center;">Name</th>
                                <th style="width: auto; text-align: center;">Quantity</th>
                                <th style="width: auto; text-align: center;">Price</th>
                                <th style="width: auto; text-align: center;">Status</th>
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
        return;
    }

    const isCollapsed = sidebar.classList.contains('collapsed');

    // Start transition: hide text/logo
    sidebar.classList.add('transitioning');
    sidebar.classList.remove('expanded-ready');

    if (isCollapsed) {
        // EXPANDING
        sidebar.classList.remove('collapsed');
        sidebar.classList.add('transitioning'); // hide menu text only

        // Restore text/logo after transition
        setTimeout(() => {
            sidebar.classList.remove('transitioning');
        }, 300); // match CSS transition duration
    } else {
        // COLLAPSING
        sidebar.classList.add('collapsed');
        sidebar.classList.add('transitioning'); // hide menu text only

        // End transition after animation
        setTimeout(() => {
            sidebar.classList.remove('transitioning');
        }, 300);
    }
}

// Close sidebar if clicking outside (mobile only)
document.addEventListener('click', function(e) {
    if (window.innerWidth >= MOBILE_BREAKPOINT) return;
    if (!sidebar.contains(e.target) && !e.target.classList.contains('sidebar-toggle')) {
        sidebar.classList.remove('show');
    }
});

// Prevent scrolling content behind sidebar when open
const observer = new MutationObserver(() => {
    if (sidebar.classList.contains('show')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});
observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../../Javascript/Chart.js"></script>

</body>
</html>