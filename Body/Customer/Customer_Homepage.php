<?php
    session_start();

    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
        header("Location: ../../Main/Index.php");
        exit();
    }

    $username = $_SESSION["username"] ?? "Customer";

    require_once "../../Connnection/Connection.php";

    $menuItems = [];
    try {
        $stmt = $conn->prepare("
        SELECT id, name, category, price, image_path
        FROM menu_items
        WHERE is_active <> 0
        ORDER BY id ASC
    ");
        $stmt->execute();
        $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $menuItems = [];
    }
?>
<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Customer Homepage</title>
            <link rel="stylesheet" href="../../Css/Customer/Customer_Homepage.css">
            <script src="../../Javascript/Customer/Customer_Homepage.js" defer></script>
        </head>
        <body class="customer-body">
            <div class="customer-shell">

                <aside class="customer-sidebar">
                    <div class="customer-sidebar-logo">
                        <div class="customer-logo-img-wrap">
                            <img src="../../Image/Admin/logo.png" class="customer-logo-img" alt="E-Baon Logo">
                        </div>
                        <div class="customer-logo-text-group">
                            <div class="customer-logo-text-main">E-Baon</div>
                            <div class="customer-logo-text-sub">Customer</div>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="customer-sidebar-item customer-sidebar-item-active"
                        data-action="home"
                    >
                        <span class="customer-sidebar-icon">🏠</span>
                        <span class="customer-sidebar-label">Home</span>
                    </button>

                    <button
                        type="button"
                        class="customer-sidebar-item"
                        data-action="menu"
                    >
                        <span class="customer-sidebar-icon">🍽️</span>
                        <span class="customer-sidebar-label">Menu</span>
                    </button>

                    <button
                        type="button"
                        class="customer-sidebar-item"
                        data-action="profile"
                    >
                        <span class="customer-sidebar-icon">🙎🏻‍♂️</span>
                        <span class="customer-sidebar-label">My Profile</span>
                    </button>

                    <button
                        type="button"
                        class="customer-sidebar-item customer-sidebar-item-active1"
                        data-action="logout"
                    >
                        <span class="customer-sidebar-icon">↩</span>
                        <span class="customer-sidebar-label">Logout</span>
                    </button>
                </aside>

                <main class="customer-main">
                    <div class="customer-topbar">
                        <div class="customer-search-wrap">
                            <input
                                type="text"
                                id="customer-search-input"
                                class="customer-search-input"
                                placeholder="What would you like to eat?"
                            >
                            <button type="button" class="customer-search-menu-btn">☰</button>
                        </div>
                    </div>

                    <section class="customer-promo-section">
                        <div class="customer-promo-slider" id="customer-promo-slider">
                            <div class="customer-promo-track">
                                <div class="customer-promo-slide">
                                    <div class="customer-promo-right customer-promo-image-one"></div>
                                    <div class="customer-promo-left">
                                        <div class="customer-promo-tag">LIMITED TIME</div>
                                        <div class="customer-promo-title">Combo Deals</div>
                                        <div class="customer-promo-subtitle">Drinks and snacks</div>
                                    </div>
                                </div>

                                <div class="customer-promo-slide">
                                    <div class="customer-promo-right customer-promo-image-two"></div>
                                    <div class="customer-promo-left">
                                        <div class="customer-promo-tag">NEW MENU</div>
                                        <div class="customer-promo-title">Fresh Bowls</div>
                                        <div class="customer-promo-subtitle">Healthy and tasty</div>
                                    </div>
                                </div>

                                <div class="customer-promo-slide">
                                    <div class="customer-promo-right customer-promo-image-three"></div>
                                    <div class="customer-promo-left">
                                        <div class="customer-promo-tag">30% OFF</div>
                                        <div class="customer-promo-title">Fitness Meal</div>
                                        <div class="customer-promo-subtitle">Limited time offer</div>
                                    </div>
                                </div>
                            </div>

                            <div class="customer-promo-dots">
                                <button
                                    type="button"
                                    class="customer-promo-dot customer-promo-dot-active"
                                    data-index="0"
                                ></button>
                                <button
                                    type="button"
                                    class="customer-promo-dot"
                                    data-index="1"
                                ></button>
                                <button
                                    type="button"
                                    class="customer-promo-dot"
                                    data-index="2"
                                ></button>
                            </div>
                        </div>
                    </section>

                    <section class="customer-products-section" id="customer-products-section">
                        <div class="customer-products-header">
                            <div class="customer-section-title">Food Menu</div>
                            <div class="customer-section-subtitle">All items from the database</div>
                        </div>

                        <div class="customer-products-grid">
                            <?php if (!empty($menuItems)): ?>
                                <?php foreach ($menuItems as $item): ?>
                                    <?php
                                    $id        = (int)$item["id"];
                                    $name      = $item["name"] ?? "";
                                    $category  = $item["category"] ?? "";
                                    $price     = (float)($item["price"] ?? 0);
                                    $imagePath = $item["image_path"] ?? "";
                                    ?>
                                    <div
                                        class="customer-product-card"
                                        data-id="<?php echo $id; ?>"
                                        data-name="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-price="<?php echo number_format($price, 2, '.', ''); ?>"
                                        data-image="<?php echo htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8'); ?>"
                                    >
                                        <div class="customer-product-image-wrap">
                                            <img
                                                src="<?php echo htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8'); ?>"
                                                alt="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
                                                class="customer-product-image"
                                            >
                                            <button type="button" class="customer-product-favorite">♡</button>
                                        </div>

                                        <div class="customer-product-info">
                                            <div class="customer-product-name">
                                                <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
                                            </div>
                                            <div class="customer-product-category">
                                                <?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>
                                            </div>
                                            <div class="customer-product-bottom">
                                                <div class="customer-product-price">
                                                    ₱<?php echo number_format($price, 2); ?>
                                                </div>
                                                <button type="button" class="customer-add-btn">+</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No menu items found.</p>
                            <?php endif; ?>
                        </div>
                    </section>
                </main>

                <aside class="customer-cart" id="customer-cart">
                    <div class="customer-cart-header">
                        <div class="customer-cart-title">My Order</div>

                        <div class="customer-cart-address-wrap">
                            <div class="customer-cart-label">Delivery address</div>

                            <button
                                type="button"
                                class="customer-cart-address-btn"
                                id="customer-cart-address-toggle"
                            >
                                <span class="customer-cart-address-text">N/A</span>
                                <span class="customer-cart-address-arrow">▼</span>
                            </button>

                            <div class="customer-cart-meta-row">
                                <span>⏱ 40 mins</span>
                                <span>📍 5 km</span>
                            </div>

                            <div class="customer-address-form" id="customer-address-form" style="display:none;">
                                <div class="customer-address-group">
                                    <div class="customer-address-label">From address</div>
                                    <div class="customer-address-row">
                                        <select id="customer-from-region" class="customer-address-input">
                                            <option value="">Select region</option>
                                        </select>
                                        <select id="customer-from-province" class="customer-address-input">
                                            <option value="">Select province</option>
                                        </select>
                                        <select id="customer-from-city" class="customer-address-input">
                                            <option value="">Select city / municipality</option>
                                        </select>
                                        <select id="customer-from-barangay" class="customer-address-input">
                                            <option value="">Select barangay</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="customer-address-group">
                                    <div class="customer-address-label">To address</div>
                                    <div class="customer-address-row">
                                        <select id="customer-to-region" class="customer-address-input">
                                            <option value="">Select region</option>
                                        </select>
                                        <select id="customer-to-province" class="customer-address-input">
                                            <option value="">Select province</option>
                                        </select>
                                        <select id="customer-to-city" class="customer-address-input">
                                            <option value="">Select city / municipality</option>
                                        </select>
                                        <select id="customer-to-barangay" class="customer-address-input">
                                            <option value="">Select barangay</option>
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" id="customer-address-from">
                                <input type="hidden" id="customer-address-to">
                            </div>
                        </div>
                    </div>

                    <div class="customer-cart-items" id="customer-cart-items">
                    </div>

                    <div class="customer-cart-summary">
                        <div class="customer-cart-summary-row">
                            <span>Sub Total</span>
                            <span id="customer-cart-subtotal">₱0.00</span>
                        </div>
                        <div class="customer-cart-summary-row">
                            <span>Delivery Fee</span>
                            <span id="customer-cart-fee">₱0.00</span>
                        </div>
                        <div class="customer-cart-summary-divider"></div>
                        <div class="customer-cart-summary-row customer-cart-summary-total-row">
                            <span>Total</span>
                            <span id="customer-cart-total">₱0.00</span>
                        </div>
                        <button
                            type="button"
                            class="customer-cart-checkout-btn"
                            id="customer-cart-checkout-btn"
                        >
                            Check Out
                        </button>
                    </div>
                </aside>
            </div>

            <div class="customer-modal-overlay" id="customer-order-modal">
                <div class="customer-modal">
                    <div class="customer-modal-title">Your Order Has Been Placed!</div>
                    <button
                        type="button"
                        class="customer-modal-ok-btn"
                        id="customer-modal-ok-btn"
                    >
                        OK
                    </button>
                </div>
            </div>
        </body>
</html>
