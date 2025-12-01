<?php 
session_start();
include '../Connection/connection.php'; // Adjust path

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
    header("Location: ../../Main/Index.php");
    exit();
}

$userID = $_SESSION["user_id"];
$username = $_SESSION["username"] ?? "Customer";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home | E-Baon</title>
    <link rel="stylesheet" href="../Css/Customer.css">
    <link rel="stylesheet" href="../Css/DisableStyles.css">
    <link rel="stylesheet" href="../Css/CustomerSidebar.css">
</head>
<body class="customer-body">

<header class="admin-head noselect">
    <div class="admin-head-text noselect">
        <h3>E-Baon</h3>
        <p>Admin</p>
    </div>

    <!-- Sidebar Toggle -->
    <button class="sidebar-toggle noselect" onclick="toggleSidebar()">☰</button>
    <div class="customer-head-search">
        <span class="customer-search-icon">🔍</span>
        <input type="text" placeholder="Search for shop">
    </div>
</header>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<div style="top: 30px; margin-top: -30px; margin-bottom: -100px;" class="admin-container">

        <!-- LEFT SIDEBAR -->
        <aside class="admin-sidebar noselect" id="sidebar">
            <!-- Logo at top -->
            <img class="admin-logo-box noselect" src="../images/e-baon-logo.png" alt="E-Baon Logo">

            <!-- MENU ITEMS -->
            <nav class="admin-menu noselect">

                <a href="../Main/Admin.php"
                class="admin-menu-item"
                data-tooltip="Dashboard">
                    <span class="admin-menu-item-icon">📊</span>
                    <span class="admin-menu-item-text">Dashboard</span>
                </a>

            </nav>

        <!-- LOGOUT BUTTON -->
        <div class="admin-bottom-bar">
            <a href="Logout.php" class="admin-logout">LOGOUT</a>
        </div>

        </aside>


        <!-- MAIN CONTENT AREA -->
        <main class="admin-main-content">
            <div class="customer-promo-row">
                <div class="promo-banner-box">
                    <img src="../images/customer/placeholder.jpg" class="promo-banner" alt="Promo banner">
                    <div class="promo-banner-text">Placeholder Text</div>
                </div>
            </div>

        <div class="customer-category-header">
            <span class="customer-category-label">Category</span>
            <span class="customer-category-line"></span>
        </div>

        <div class="customer-category-row">
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
            <button class="category-circle-btn">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="">
            </button>
        </div>

        <section class="customer-shop-row">

            <div class="shop-column">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-card">
                    <div class="shop-picture">
                        <img src="../images/customer/placeholder.jpg" class="shop-img" alt="Product 1">
                    </div>
                    <div class="shop-info">Name of the product / price</div>
                    <button class="shop-add-btn">Add</button>
                </div>
            </div>

            <div class="shop-column">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-card">
                    <div class="shop-picture">
                        <img src="../images/customer/placeholder.jpg" class="shop-img" alt="Product 2">
                    </div>
                    <div class="shop-info">Name of the product / price</div>
                    <button class="shop-add-btn">Add</button>
                </div>
            </div>

            <div class="shop-column">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-card">
                    <div class="shop-picture">
                        <img src="../images/customer/placeholder.jpg" class="shop-img" alt="Product 3">
                    </div>
                    <div class="shop-info">Name of the product / price</div>
                    <button class="shop-add-btn">Add</button>
                </div>
            </div>

            <div class="shop-column">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-card">
                    <div class="shop-picture">
                        <img src="../images/customer/placeholder.jpg" class="shop-img" alt="Product 4">
                    </div>
                    <div class="shop-info">Name of the product / price</div>
                    <button class="shop-add-btn">Add</button>
                </div>
            </div>

            <div class="shop-column">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-card">
                    <div class="shop-picture">
                        <img src="../images/customer/placeholder.jpg" class="shop-img" alt="Product 5">
                    </div>
                    <div class="shop-info">Name of the product / price</div>
                    <button class="shop-add-btn">Add</button>
                </div>
            </div>

            <div class="shop-column">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-card">
                    <div class="shop-picture">
                        <img src="../images/customer/placeholder.jpg" class="shop-img" alt="Product 6">
                    </div>
                    <div class="shop-info">Name of the product / price</div>
                    <button class="shop-add-btn">Add</button>
                </div>
            </div>

            <div class="shop-column">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-card">
                    <div class="shop-picture">
                        <img src="../images/customer/placeholder.jpg" class="shop-img" alt="Product 7">
                    </div>
                    <div class="shop-info">Name of the product / price</div>
                    <button class="shop-add-btn">Add</button>
                </div>
            </div>

            <div class="shop-column">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-card">
                    <div class="shop-picture">
                        <img src="../images/customer/placeholder.jpg" class="shop-img" alt="Product 8">
                    </div>
                    <div class="shop-info">Name of the product / price</div>
                    <button class="shop-add-btn">Add</button>
                </div>
            </div>

            <div class="shop-column">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-card">
                    <div class="shop-picture">
                        <img src="../images/customer/placeholder.jpg" class="shop-img" alt="Product 9">
                    </div>
                    <div class="shop-info">Name of the product / price</div>
                    <button class="shop-add-btn">Add</button>
                </div>
            </div>

            <div class="shop-column">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-card">
                    <div class="shop-picture">
                        <img src="../images/customer/placeholder.jpg" class="shop-img" alt="Product 10">
                    </div>
                    <div class="shop-info">Name of the product / price</div>
                    <button class="shop-add-btn">Add</button>
                </div>
            </div>

            <div class="shop-column">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-card">
                    <div class="shop-picture">
                        <img src="../images/customer/placeholder.jpg" class="shop-img" alt="Product 11">
                    </div>
                    <div class="shop-info">Name of the product / price</div>
                    <button class="shop-add-btn">Add</button>
                </div>
            </div>

            <div class="shop-column">
                <div class="shop-name">Name of the Shop</div>
                <div class="shop-card">
                    <div class="shop-picture">
                        <img src="../images/customer/placeholder.jpg" class="shop-img" alt="Product 12">
                    </div>
                    <div class="shop-info">Name of the product / price</div>
                    <button class="shop-add-btn">Add</button>
                </div>
            </div>
        </section>
        </main>

        <footer class="customer-bottom-nav">
            <a href="../Body/Customer/Cart.php" class="bottom-icon" style="text-decoration:none;">🛒</a>
            <div class="bottom-status">No Order Yet</div>
            <a href="../Body/Customer/Profile.php" class="bottom-icon" style="text-decoration:none;">👤</a>
        </footer>
    </div>


<div class="modal" id="addModal">
    <div class="modal-content">
        <p>Item added to cart</p>
        <button id="addModalOk">OK</button>
    </div>
</div>

<script>
const sidebar = document.getElementById("sidebar");
const backdrop = document.getElementById("sidebarBackdrop");
const MOBILE_BREAKPOINT = 768;

// Load saved state
const savedState = localStorage.getItem("customerSidebarState") || "collapsed";

function applySidebarState() {
    if (window.innerWidth >= MOBILE_BREAKPOINT) {
        // Desktop
        sidebar.classList.toggle("collapsed", savedState !== "expanded");
        sidebar.classList.remove("expanded-modal");
        backdrop.classList.remove("show");
    } else {
        // Mobile
        sidebar.classList.toggle("expanded-modal", savedState === "expanded");
        sidebar.classList.remove("collapsed");
        backdrop.classList.toggle("show", savedState === "expanded");
    }

    // Show sidebar after correct state is applied
    sidebar.classList.add("visible");
}

// Apply immediately on page load
applySidebarState();

// Toggle sidebar
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

// Mobile backdrop click
backdrop.addEventListener("click", () => {
    sidebar.classList.remove("expanded-modal");
    backdrop.classList.remove("show");
    localStorage.setItem("customerSidebarState", "collapsed");
});

// Click outside sidebar (mobile only)
document.addEventListener("click", (e) => {
    if (window.innerWidth >= MOBILE_BREAKPOINT) return;
    if (!sidebar.contains(e.target) && !e.target.classList.contains("sidebar-toggle")) {
        sidebar.classList.remove("expanded-modal");
        backdrop.classList.remove("show");
        localStorage.setItem("customerSidebarState", "collapsed");
    }
});

// Reapply on resize
window.addEventListener("resize", applySidebarState);
</script>

</body>
</html>
