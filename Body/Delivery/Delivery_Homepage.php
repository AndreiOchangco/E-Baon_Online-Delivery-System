<?php
    session_start();

    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "delivery") {
        header("Location: ../../Main/Index.php");
        exit();
    }

    require_once "../../Connnection/Connection.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "update_status") {
        header("Content-Type: application/json; charset=utf-8");
        $orderId = (int)($_POST["order_id"] ?? 0);
        $status  = trim($_POST["status"] ?? "");
        $allowed = ["preparing", "delivering", "completed"];

        if ($orderId <= 0 || !in_array($status, $allowed, true)) {
            echo json_encode(["success" => false, "message" => "Invalid data"]);
            exit();
        }

        try {
            $stmt = $conn->prepare("UPDATE orders SET status = :status WHERE id = :id");
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->bindParam(":id", $orderId, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode(["success" => true]);
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Database error"]);
        }

        exit();
    }

    $username     = $_SESSION["username"] ?? "Delivery";
    $orders       = [];
    $itemsByOrder = [];

    function statusText($status)
    {
        if ($status === "completed") {
            return "Completed";
        }
        if ($status === "delivering") {
            return "Delivering to you";
        }
        return "Order being prepared";
    }

    try {
        $stmt = $conn->prepare("
        SELECT *
        FROM orders
        ORDER BY created_at DESC
        LIMIT 30
    ");
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
            ORDER BY oi.order_id ASC, oi.id ASC
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
    } catch (PDOException $e) {
        $orders       = [];
        $itemsByOrder = [];
    }
?>
<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Delivery Orders</title>
            <link rel="stylesheet" href="../../Css/Delivery/Delivery_Homepage.css">
            <script src="../../Javascript/Delivery/Delivery_Homepage.js" defer></script>
        </head>
        <body class="delivery-body">

            <div class="delivery-shell">

                <aside class="delivery-sidebar">
                    <div class="delivery-sidebar-logo">
                        <div class="delivery-logo-img-wrap">
                            <img src="../../Image/Admin/logo.png" class="delivery-logo-img" alt="E-Baon Logo">
                        </div>
                        <div class="delivery-logo-text-group">
                            <div class="delivery-logo-text-main">E-Baon</div>
                            <div class="delivery-logo-text-sub">Delivery</div>
                        </div>
                    </div>

                    <nav class="delivery-nav">
                        <button type="button" class="delivery-nav-item delivery-nav-item-active">
                            <span class="delivery-nav-label">Dashboard</span>
                        </button>
                        <button type="button" class="delivery-nav-item">
                            <span class="delivery-nav-label">Profile</span>
                        </button>
                    </nav>

                    <a href="../../Main/Logout.php" class="delivery-sidebar-logout-link">Logout</a>
                </aside>

                <main class="delivery-main">
                    <header class="delivery-header">
                        <div class="delivery-header-title">Orders</div>
                        <div class="delivery-header-user">
                            <div class="delivery-header-user-name">
                                <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <a href="../../Main/Logout.php" class="delivery-logout-link">Logout</a>
                        </div>
                    </header>

                    <section class="delivery-content-layout">
                        <div class="delivery-list-panel">
                            <div class="delivery-panel-title">Order in</div>

                            <div class="delivery-tabs">
                                <button type="button" class="delivery-tab delivery-tab-active" data-filter="all">All</button>
                                <button type="button" class="delivery-tab" data-filter="preparing">Preparing</button>
                                <button type="button" class="delivery-tab" data-filter="delivering">Delivering</button>
                                <button type="button" class="delivery-tab" data-filter="completed">Delivered</button>
                            </div>

                            <div class="delivery-order-list" id="delivery-order-list">
                                <?php if (empty($orders)): ?>
                                    <div class="delivery-empty">No orders found.</div>
                                <?php else: ?>
                                    <?php foreach ($orders as $order): ?>
                                        <?php
                                        $oid         = (int)$order["id"];
                                        $created     = $order["created_at"];
                                        $total       = (float)$order["total"];
                                        $status      = $order["status"];
                                        $statusLabel = statusText($status);
                                        ?>
                                        <button
                                            type="button"
                                            class="delivery-order-row"
                                            data-order-id="<?php echo $oid; ?>"
                                            data-status="<?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>"
                                        >
                                            <div class="delivery-order-row-main">
                                                <div class="delivery-order-row-id">Order #<?php echo $oid; ?></div>
                                                <div class="delivery-order-row-date">
                                                    <?php echo htmlspecialchars($created); ?>
                                                </div>
                                            </div>
                                            <div class="delivery-order-row-right">
                                                <div class="delivery-order-row-amount">
                                                    ₱<?php echo number_format($total, 2); ?>
                                                </div>
                                                <div class="delivery-order-row-status">
                                                    <?php echo $statusLabel; ?>
                                                </div>
                                            </div>
                                        </button>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="delivery-detail-panel">
                            <div class="delivery-panel-title">Order Details</div>

                            <div class="delivery-detail-empty" id="delivery-detail-empty">
                                Select an order on the left to see its details.
                            </div>

                            <div class="delivery-detail-card" id="delivery-detail-card">
                                <div class="delivery-detail-header">
                                    <div>
                                        <div class="delivery-detail-orderid" id="detail-order-id"></div>
                                        <div class="delivery-detail-date" id="detail-order-date"></div>
                                    </div>
                                    <div class="delivery-detail-userpill">
                                        <div class="delivery-logo-img-wrap">
                                            <img src="../../Image/Admin/logo.png" class="delivery-logo-img" alt="E-Baon Logo">
                                        </div>
                                        <div class="delivery-logo-text-group">
                                            <div class="delivery-logo-text-main">E-Baon</div>
                                            <div class="delivery-logo-text-sub">Delivery Rider</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="delivery-detail-meta-grid">
                                    <div class="delivery-detail-meta-block">
                                        <div class="delivery-detail-meta-label">Delivery Address</div>
                                        <div class="delivery-detail-meta-value" id="detail-address"></div>
                                    </div>
                                    <div class="delivery-detail-meta-block">
                                        <div class="delivery-detail-meta-label">Estimation Time</div>
                                        <div class="delivery-detail-meta-value">10 Min</div>
                                    </div>
                                    <div class="delivery-detail-meta-block">
                                        <div class="delivery-detail-meta-label">Distance</div>
                                        <div class="delivery-detail-meta-value">2.5 Km</div>
                                    </div>
                                    <div class="delivery-detail-meta-block">
                                        <div class="delivery-detail-meta-label">Payment</div>
                                        <div class="delivery-detail-meta-value">Cash on Delivery</div>
                                    </div>
                                    <div class="delivery-detail-meta-block">
                                        <div class="delivery-detail-meta-label">Payment Status</div>
                                        <div class="delivery-detail-meta-value" id="detail-payment-status"></div>
                                    </div>
                                    <div class="delivery-detail-meta-block">
                                        <div class="delivery-detail-meta-label">Order Status</div>
                                        <div class="delivery-detail-meta-value" id="detail-order-status-text"></div>
                                    </div>
                                </div>

                                <div class="delivery-detail-items" id="detail-items"></div>

                                <div class="delivery-detail-total-row">
                                    <span>Total</span>
                                    <span id="detail-total"></span>
                                </div>

                                <div class="delivery-detail-print-row">
                                    <button type="button" id="delivery-print-btn" class="delivery-print-btn">
                                        Print receipt
                                    </button>
                                </div>

                                <div class="delivery-detail-status-controls">
                                    <button type="button" class="delivery-status-action-btn" data-status="preparing">
                                        Set: Order being prepared
                                    </button>
                                    <button type="button" class="delivery-status-action-btn" data-status="delivering">
                                        Set: Delivering to you
                                    </button>
                                    <button type="button" class="delivery-status-action-btn" data-status="completed">
                                        Set: Completed
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>
                </main>

            </div>

            <?php
                $frontOrders = [];
                foreach ($orders as $o) {
                    $oid = (int)$o["id"];
                    $frontOrders[] = [
                        "id"         => $oid,
                        "created_at" => $o["created_at"],
                        "status"     => $o["status"],
                        "to_address" => $o["to_address"] ?? "",
                        "total"      => (float)$o["total"],
                        "items"      => array_map(function ($it) {
                            return [
                                "name"       => $it["product_name"],
                                "qty"        => (int)$it["quantity"],
                                "price"      => (float)$it["price"],
                                "image_path" => $it["image_path"] ?? ""
                            ];
                        }, $itemsByOrder[$oid] ?? [])
                    ];
                }
            ?>
            <script>
                window.DELIVERY_ORDERS_DATA = <?php echo json_encode($frontOrders, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
            </script>

            <script>
                (function () {
                    var statusButtons = document.querySelectorAll(".delivery-status-action-btn");

                    function setActiveStatusButton(clicked) {
                        statusButtons.forEach(function (btn) {
                            btn.classList.remove("delivery-status-action-active");
                        });
                        clicked.classList.add("delivery-status-action-active");
                    }

                    statusButtons.forEach(function (btn) {
                        btn.addEventListener("click", function () {
                            setActiveStatusButton(btn);
                        });
                    });
                })();
            </script>

        </body>
</html>
