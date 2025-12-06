<?php
    session_start();

    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
        header("Location: ../../Main/Index.php");
        exit();
    }

    $username   = $_SESSION["username"] ?? "Customer";
    $customerId = (int)($_SESSION["user_id"] ?? 0);

    require_once "../../Connnection/Connection.php";

    $orders = [];
    $itemsByOrder = [];
    $activeOrder = null;
    $menuOrdersPayload = [];

    try {
        $stmt = $conn->prepare("
        SELECT *
        FROM orders
        WHERE customer_id = :cid
        ORDER BY created_at DESC
        LIMIT 6
    ");
        $stmt->bindParam(":cid", $customerId, PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($orders)) {
            $ids = array_column($orders, "id");
            $in  = implode(",", array_fill(0, count($ids), "?"));

            $stmtItems = $conn->prepare("
            SELECT oi.*, mi.image_path
            FROM order_items oi
            LEFT JOIN menu_items mi ON oi.product_id = mi.id
            WHERE oi.order_id IN ($in)
        ");
            foreach ($ids as $i => $oid) {
                $stmtItems->bindValue($i + 1, $oid, PDO::PARAM_INT);
            }
            $stmtItems->execute();
            while ($row = $stmtItems->fetch(PDO::FETCH_ASSOC)) {
                $oid = (int)$row["order_id"];
                if (!isset($itemsByOrder[$oid])) {
                    $itemsByOrder[$oid] = [];
                }
                $itemsByOrder[$oid][] = $row;
            }
        }

        foreach ($orders as $o) {
            if ($o["status"] === "delivering" || $o["status"] === "preparing") {
                $activeOrder = $o;
                break;
            }
        }

        if (!$activeOrder && !empty($orders)) {
            $activeOrder = $orders[0];
        }

        foreach ($orders as $o) {
            $oid = (int)$o["id"];
            $entry = [
                "id"           => $oid,
                "status"       => $o["status"],
                "total"        => (float)$o["total"],
                "from_address" => $o["from_address"],
                "to_address"   => $o["to_address"],
                "items"        => []
            ];
            if (isset($itemsByOrder[$oid])) {
                foreach ($itemsByOrder[$oid] as $it) {
                    $entry["items"][] = [
                        "product_name" => $it["product_name"],
                        "quantity"     => (int)$it["quantity"],
                        "price"        => (float)$it["price"],
                        "image_path"   => $it["image_path"] ?? ""
                    ];
                }
            }
            $menuOrdersPayload[] = $entry;
        }
    } catch (PDOException $e) {
        $orders = [];
        $itemsByOrder = [];
        $activeOrder = null;
        $menuOrdersPayload = [];
    }
?>
<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Menu – Orders Overview</title>
            <link rel="stylesheet" href="../../Css/Customer/Customer_Homepage.css">
            <link rel="stylesheet" href="../../Css/Customer/Menu_Homepage.css">

            <script>
                window.MENU_ORDERS_DATA = <?php
                    echo json_encode($menuOrdersPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                ?>;
                window.MENU_ACTIVE_ORDER_ID = <?php
                    echo $activeOrder ? (int)$activeOrder["id"] : "null";
                ?>;
            </script>

            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

            <script src="../../Javascript/Customer/Menu_Homepage.js" defer></script>
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
                        class="customer-sidebar-item"
                        data-action="home"
                    >
                        <span class="customer-sidebar-icon">🏠</span>
                        <span class="customer-sidebar-label">Home</span>
                    </button>

                    <button
                        type="button"
                        class="customer-sidebar-item customer-sidebar-item-active"
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

                <main class="customer-main menu-main">

                    <div class="menu-main-layout">

                        <section class="menu-left">

                            <header class="menu-header">
                                <div class="menu-header-text">
                                    <h1 class="menu-title">Food Orders</h1>
                                    <p class="menu-subtitle">Track your recent orders and their status</p>
                                </div>
                                <div class="menu-search">
                                    <input
                                        type="text"
                                        class="menu-search-input"
                                        id="menu-search-input"
                                        placeholder="Search order"
                                    >
                                    <button type="button" class="menu-search-button">🔍</button>
                                </div>
                            </header>

                            <div class="menu-orders-grid" id="menu-orders-grid">
                                <?php if (empty($orders)): ?>
                                    <p>No orders yet. Place an order from the Home page.</p>
                                <?php else: ?>
                                    <?php foreach ($orders as $index => $order): ?>
                                        <?php
                                        $oid         = (int)$order["id"];
                                        $status      = $order["status"];
                                        $created     = $order["created_at"];
                                        $subtotal    = (float)$order["subtotal"];
                                        $deliveryFee = (float)$order["delivery_fee"];
                                        $total       = (float)$order["total"];
                                        $items       = $itemsByOrder[$oid] ?? [];
                                        $cardClass   = "";
                                        $statusLabel = "";
                                        if ($status === "completed") {
                                            $cardClass = "menu-order-card-completed";
                                            $statusLabel = "Completed";
                                        } elseif ($status === "delivering") {
                                            $cardClass = "menu-order-card-active";
                                            $statusLabel = "Delivering to you";
                                        } else {
                                            $cardClass = "";
                                            $statusLabel = "Order being prepared";
                                        }
                                        ?>
                                        <article
                                            class="menu-order-card <?php echo $cardClass; ?>"
                                            data-order-id="<?php echo $oid; ?>"
                                            data-from-address="<?php echo htmlspecialchars($order["from_address"], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-to-address="<?php echo htmlspecialchars($order["to_address"], ENT_QUOTES, 'UTF-8'); ?>"
                                        >
                                            <div class="menu-order-header-line">
                                                <div class="menu-order-id">Order #<?php echo $oid; ?></div>
                                                <div class="menu-order-date"><?php echo htmlspecialchars($created); ?></div>
                                            </div>
                                            <div class="menu-order-restaurant">Fast Food Resto</div>
                                            <div class="menu-order-rating-row">
                                                <span class="menu-order-stars">★ 5.0</span>
                                                <span class="menu-order-reviews">1k+ Reviews</span>
                                            </div>
                                            <div class="menu-order-meta-row">
                                                <div class="menu-order-meta-label">Delivery Time</div>
                                                <div class="menu-order-meta-value">10 Min</div>
                                            </div>
                                            <div class="menu-order-meta-row">
                                                <div class="menu-order-meta-label">Distance</div>
                                                <div class="menu-order-meta-value">2.5 Km</div>
                                            </div>
                                            <div class="menu-order-menu-title">Order Menu</div>

                                            <?php
                                            $shown = 0;
                                            foreach ($items as $it):
                                                $shown++;
                                                if ($shown > 2) {
                                                    break;
                                                }
                                                $pname  = $it["product_name"];
                                                $pqty   = (int)$it["quantity"];
                                                $pprice = (float)$it["price"];
                                                $img    = $it["image_path"] ?? "";
                                            ?>
                                                <div class="menu-order-menu-line">
                                                    <div class="menu-order-menu-left">
                                                        <div
                                                            class="menu-order-thumb"
                                                            style="background-image:url('<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>');"
                                                        ></div>
                                                        <div class="menu-order-menu-text">
                                                            <div class="menu-order-menu-name"><?php echo htmlspecialchars($pname); ?></div>
                                                            <div class="menu-order-menu-qty">x<?php echo $pqty; ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="menu-order-menu-price">+₱<?php echo number_format($pprice, 2); ?></div>
                                                </div>
                                            <?php endforeach; ?>

                                            <div class="menu-order-total-row">
                                                <span>Total</span>
                                                <span class="menu-order-total-amount">₱<?php echo number_format($total, 2); ?></span>
                                            </div>
                                            <button
                                                type="button"
                                                class="menu-order-status-btn <?php echo $status === 'completed'
                                                    ? 'menu-order-status-completed'
                                                    : ($status === 'delivering'
                                                        ? 'menu-order-status-delivering'
                                                        : 'menu-order-status-preparing'); ?>"
                                            >
                                                <?php echo $statusLabel; ?>
                                            </button>
                                        </article>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                        </section>

                        <aside class="menu-right">
                            <div class="menu-tracker-card">
                                <div class="menu-tracker-header">
                                    <div class="menu-tracker-title">Order Tracker</div>
                                    <div class="menu-map-status" id="menu-map-status">Loading...</div>
                                </div>
                                <?php if ($activeOrder): ?>
                                    <div
                                        id="menu-map"
                                        class="menu-tracker-map"
                                        data-from="<?php echo htmlspecialchars($activeOrder["from_address"], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-to="<?php echo htmlspecialchars($activeOrder["to_address"], ENT_QUOTES, 'UTF-8'); ?>"
                                    ></div>
                                    <div class="menu-address-block">
                                        <div class="menu-address-label">Your Address</div>
                                        <div class="menu-address-value" id="menu-address-value">
                                            <?php echo htmlspecialchars($activeOrder["to_address"], ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                        <p class="menu-address-note">
                                            Latest active order. Route is drawn on the map based on your delivery address.
                                        </p>
                                    </div>

                                    <?php
                                    $activeItems = $itemsByOrder[(int)$activeOrder["id"]] ?? [];
                                    ?>
                                    <div class="menu-order-side-list">
                                        <div class="menu-order-side-title">Order Menu</div>
                                        <?php foreach ($activeItems as $it): ?>
                                            <div class="menu-order-side-item">
                                                <div class="menu-order-side-left">
                                                    <div
                                                        class="menu-order-side-thumb"
                                                        style="background-image:url('<?php echo htmlspecialchars($it["image_path"] ?? "", ENT_QUOTES, 'UTF-8'); ?>');"
                                                    ></div>
                                                    <div class="menu-order-side-text">
                                                        <div class="menu-order-side-name"><?php echo htmlspecialchars($it["product_name"]); ?></div>
                                                        <div class="menu-order-side-qty">x<?php echo (int)$it["quantity"]; ?></div>
                                                    </div>
                                                </div>
                                                <div class="menu-order-side-price">
                                                    +₱<?php echo number_format((float)$it["price"], 2); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                        <div class="menu-order-total-row" style="margin-top:10px;">
                                            <span>Total</span>
                                            <span class="menu-order-total-amount">
                                                ₱<?php echo number_format((float)$activeOrder["total"], 2); ?>
                                            </span>
                                        </div>
                                        <button
                                            type="button"
                                            class="menu-order-status-btn menu-order-status-delivering"
                                            style="margin-top:10px;"
                                        >
                                            <?php echo $activeOrder["status"] === "completed" ? "Completed" : "Delivering to you"; ?>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <div class="menu-tracker-map" id="menu-map"></div>
                                    <div class="menu-address-block">
                                        <div class="menu-address-label">Your Address</div>
                                        <div class="menu-address-value" id="menu-address-value">No active order</div>
                                        <p class="menu-address-note">
                                            Place an order from the Home page to see your route here.
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </aside>

                    </div>

                </main>

            </div>
        </body>
</html>
