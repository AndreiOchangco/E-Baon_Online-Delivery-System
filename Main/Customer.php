<?php 
session_start();
include '../Connection/connection.php';

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
    header("Location: ../../Main/Index.php");
    exit();
}

// Fetch categories
$categoryQuery = "SELECT * FROM shops ORDER BY shopName ASC";
$categories = $conn->query($categoryQuery);

// Fetch products
$productQuery = "SELECT * FROM products ORDER BY productID ASC";
$products = $conn->query($productQuery);

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

<div style="top: 30px; margin-top: -30px; margin-bottom: -150px;" class="admin-container">

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

        <!-- Categories -->
        <div class="customer-category-row">
            <button class="category-circle-btn" data-category="all">
                <img src="../images/customer/placeholder.jpg" class="category-circle-img" alt="All">
                <div class="category-label">All</div>
            </button>
            <?php while($cat = $categories->fetch_assoc()): ?>
                <button class="category-circle-btn" data-category="<?= htmlspecialchars($cat['shopCategory']) ?>">
                    <img src="<?= htmlspecialchars($cat['shopImage']) ?>" class="category-circle-img" alt="<?= htmlspecialchars($cat['shopName']) ?>">
                    <div class="category-label"><?= htmlspecialchars($cat['shopName']) ?></div>
                </button>
            <?php endwhile; ?>
        </div>

        <!-- Products -->
        <section class="customer-shop-row">
            <?php while($prod = $products->fetch_assoc()): ?>
                <div class="shop-column">
                    <div class="shop-card" data-shop="<?= htmlspecialchars($prod['shopName']) ?>" data-category="<?= htmlspecialchars($prod['shopCategory']) ?>">
                        <div class="shop-name"><?= htmlspecialchars($prod['shopName']) ?></div>
                        <div class="shop-picture">
                            <img src="<?= htmlspecialchars($prod['product_image']) ?>" class="shop-img" alt="<?= htmlspecialchars($prod['productName']) ?>">
                            <div class="shop-info"><?= htmlspecialchars($prod['productName']) ?> / ₱<?= number_format($prod['productPrice'], 2) ?></div>
                        </div>
                        <button class="shop-add-btn">Add</button>
                    </div>
                </div>
            <?php endwhile; ?>
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

<!-- JS for category filtering -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const categoryButtons = document.querySelectorAll('.category-circle-btn');
    const shopCards = document.querySelectorAll('.shop-card');

    categoryButtons.forEach(button => {
        button.addEventListener('click', () => {
            const selectedCategory = button.getAttribute('data-category');

            shopCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');

                if (selectedCategory === 'all' || cardCategory === selectedCategory) {
                    card.parentElement.style.display = 'flex';
                    card.classList.add('show');
                    card.classList.remove('hide');
                } else {
                    card.parentElement.style.display = 'none';
                    card.classList.add('hide');
                    card.classList.remove('show');
                }
            });
        });
    });
});
</script>

</body>
</html>