<?php 
session_start();
include '../Connection/connection.php';

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
    <link rel="shortcut icon" href="../images/e-baon-logo.png">
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

<!-- LEFT SIDEBAR -->
<aside class="admin-sidebar noselect" id="sidebar">
    <!-- Logo at top -->
    <img class="admin-logo-box noselect" src="../images/e-baon-logo.png" alt="E-Baon Logo">

    <!-- MENU ITEMS -->
    <nav class="admin-menu noselect">

        <a href="../Main/Admin.php"
        class="admin-menu-item"
        data-tooltip="Dashboard">
            <span class="admin-menu-item-icon">🏠</span>
            <span class="admin-menu-item-text">Dashboard</span>
        </a>

    </nav>

<!-- LOGOUT BUTTON -->
<div class="admin-bottom-bar">
    <a href="Logout.php" class="admin-logout">LOGOUT</a>
</div>

</aside>

<div style="top: 30px; margin-top: -30px; margin-bottom: -100px;" class="admin-container">

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

        <div class="customer-category-row" id="categoryRow">

        <button class="category-circle-btn" data-category="all">
            <img src="../images/Shop-Logos/all.png" class="category-circle-img">
            <span class="category-label">All</span>
        </button>

        <button class="category-circle-btn" data-category="jollibee">
            <img src="../images/Shop-Logos/jollibee.png" class="category-circle-img">
            <span class="category-label">Jollibee</span>
        </button>

        <button class="category-circle-btn" data-category="mcdonalds">
            <img src="../images/Shop-Logos/mcdonalds.png" class="category-circle-img">
            <span class="category-label">McDonald's</span>
        </button>

        <button class="category-circle-btn" data-category="kfc">
            <img src="../images/Shop-Logos/kfc.png" class="category-circle-img">
            <span class="category-label">KFC</span>
        </button>

        <button class="category-circle-btn" data-category="burger-king">
            <img src="../images/Shop-Logos/burger-king.png" class="category-circle-img">
            <span class="category-label">Burger King</span>
        </button>

        <button class="category-circle-btn" data-category="starbucks">
            <img src="../images/Shop-Logos/starbucks.png" class="category-circle-img">
            <span class="category-label">Starbucks</span>
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

// Always get the latest state
function getSidebarState() {
    return localStorage.getItem("customerSidebarState") || "collapsed";
}

function setSidebarState(state) {
    localStorage.setItem("customerSidebarState", state);
}

// Apply saved state to UI
function applySidebarState() {
    const state = getSidebarState();

    if (window.innerWidth >= MOBILE_BREAKPOINT) {
        // Desktop
        sidebar.classList.toggle("collapsed", state !== "expanded");
        sidebar.classList.remove("expanded-modal");
        backdrop.classList.remove("show");
    } else {
        // Mobile
        sidebar.classList.toggle("expanded-modal", state === "expanded");
        sidebar.classList.remove("collapsed");
        backdrop.classList.toggle("show", state === "expanded");
    }

    // Ensure sidebar appears correctly after layout
    requestAnimationFrame(() => {
        sidebar.classList.add("visible");
    });
}

// Initial render
applySidebarState();

// Toggle button
function toggleSidebar() {
    const state = getSidebarState();

    if (window.innerWidth < MOBILE_BREAKPOINT) {
        // Mobile modal drawer
        const newState = state === "expanded" ? "collapsed" : "expanded";
        sidebar.classList.toggle("expanded-modal", newState === "expanded");
        backdrop.classList.toggle("show", newState === "expanded");
        setSidebarState(newState);
    } else {
        // Desktop collapse/expand
        const newState = state === "expanded" ? "collapsed" : "expanded";
        sidebar.classList.toggle("collapsed", newState === "collapsed");
        setSidebarState(newState);
    }
}

// Backdrop click (mobile only)
backdrop.addEventListener("click", () => {
    sidebar.classList.remove("expanded-modal");
    backdrop.classList.remove("show");
    setSidebarState("collapsed");
});

// Close mobile sidebar when clicking outside
document.addEventListener("click", (event) => {
    if (window.innerWidth >= MOBILE_BREAKPOINT) return;

    const clickedInsideSidebar = sidebar.contains(event.target);
    const isToggle = event.target.classList.contains("sidebar-toggle");

    if (!clickedInsideSidebar && !isToggle) {
        sidebar.classList.remove("expanded-modal");
        backdrop.classList.remove("show");
        setSidebarState("collapsed");
    }
});

// Reapply on resize
window.addEventListener("resize", applySidebarState);
</script>
<script>
// Filtering logic
document.querySelectorAll('.category-circle-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const selected = btn.dataset.category;

        const cards = document.querySelectorAll('.shop-card');

        // Step 1: Fade out all cards
        cards.forEach(c => c.classList.add('hide'));

        setTimeout(() => {
            cards.forEach(card => {
                const shop = card.dataset.shop;

                if (selected === 'all' || shop === selected) {
                    card.classList.remove('hide');
                    card.classList.add('show');
                } else {
                    card.classList.remove('show');
                    card.classList.add('hide');
                }
            });
        }, 300); // Matches CSS transition
    });
});
</script>

</body>
</html>