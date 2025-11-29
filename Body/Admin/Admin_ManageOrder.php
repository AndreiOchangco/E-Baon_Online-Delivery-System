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
    <link rel="stylesheet" href="../Css/DisableStyles.css">
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
                class="admin-menu-item"
                data-tooltip="Dashboard">
                    <span class="admin-menu-item-icon">📊</span>
                    <span class="admin-menu-item-text">Dashboard</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageOrder.php"
                class="admin-menu-item"
                data-tooltip="Manage Order">
                    <span class="admin-menu-item-icon">📋</span>
                    <span class="admin-menu-item-text">Manage Order</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageProduct.php"
                class="admin-menu-item"
                data-tooltip="Manage Product">
                    <span class="admin-menu-item-icon">📦</span>
                    <span class="admin-menu-item-text">Manage Product</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageCustomer.php"
                class="admin-menu-item"
                data-tooltip="Manage Customer">
                    <span class="admin-menu-item-icon">🙎🏻‍♂️</span>
                    <span class="admin-menu-item-text">Manage Customer</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageDelivery.php"
                class="admin-menu-item"
                data-tooltip="Manage Delivery">
                    <span class="admin-menu-item-icon">🛵</span>
                    <span class="admin-menu-item-text">Manage Delivery D.</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageAdminAcc.php"
                class="admin-menu-item"
                data-tooltip="Manage Admin">
                    <span class="admin-menu-item-icon">⚙️</span>
                    <span class="admin-menu-item-text">Manage Admin Acc.</span>
                </a>

            </nav>

        <!-- LOGOUT BUTTON -->
        <div class="admin-bottom-bar">
            <a href="../../Main/Admin.php" class="admin-logout">Back to Dashboard</a>
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
        </main>

    </div>

<!-- SMART COLLAPSE SCRIPT WITH LOCAL STORAGE -->
<script>
const sidebar = document.getElementById('sidebar');
const MOBILE_BREAKPOINT = 768;

/* 1. Disable ALL sidebar animations during initial load */
sidebar.classList.add("no-anim");

/* 2. Apply saved state BEFORE anything renders */
const savedState = localStorage.getItem("adminSidebarState");
if (savedState === "collapsed") {
    sidebar.classList.add("collapsed");
} else {
    sidebar.classList.remove("collapsed");
}

/* 3. Enable animations after load and after the logo finishes layout */
window.addEventListener("load", () => {
    // Give browser a moment to finalize layout to stop flicker
    setTimeout(() => {
        sidebar.classList.remove("no-anim");
        sidebar.classList.add("expanded-ready");
    }, 120);
});

/* SIDEBAR TOGGLE FUNCTION */
function toggleSidebar() {
    if (window.innerWidth < MOBILE_BREAKPOINT) {
        sidebar.classList.toggle("show");
        return;
    }

    const isCollapsed = sidebar.classList.contains("collapsed");

    sidebar.classList.add("transitioning");
    sidebar.classList.remove("expanded-ready");

    if (isCollapsed) {
        /* EXPANDING */
        sidebar.classList.remove("collapsed");

        setTimeout(() => {
            sidebar.classList.remove("transitioning");
            sidebar.classList.add("expanded-ready");
        }, 300);

        localStorage.setItem("adminSidebarState", "expanded");

    } else {
        /* COLLAPSING */
        sidebar.classList.add("collapsed");

        setTimeout(() => {
            sidebar.classList.remove("transitioning");
        }, 300);

        localStorage.setItem("adminSidebarState", "collapsed");
    }
}

/* CLOSE SIDEBAR ON MOBILE CLICK OUTSIDE */
document.addEventListener("click", function(e) {
    if (window.innerWidth >= MOBILE_BREAKPOINT) return;
    if (!sidebar.contains(e.target) && !e.target.classList.contains("sidebar-toggle")) {
        sidebar.classList.remove("show");
    }
});

/* PREVENT BACKGROUND SCROLL WHEN MOBILE SIDEBAR IS OPEN */
const observer = new MutationObserver(() => {
    if (sidebar.classList.contains("show")) {
        document.body.style.overflow = "hidden";
    } else {
        document.body.style.overflow = "";
    }
});
observer.observe(sidebar, { attributes: true, attributeFilter: ["class"] });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../../Javascript/Chart.js"></script>

</body>
</html>