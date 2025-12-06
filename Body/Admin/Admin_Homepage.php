<?php
    session_start();

    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
        header("Location: ../../Main/Index.php");
        exit();
    }

    require_once "../../Connnection/Connection.php";

    $username = $_SESSION["username"] ?? "Admin";

    $defaultMetrics = [
        "total_orders"     => 0,
        "total_delivered"  => 0,
        "total_canceled"   => 0,
        "total_revenue"    => 0,
        "open_orders"      => 0
    ];

    $metrics = [];

    foreach ($defaultMetrics as $key => $defaultValue) {
        $stmt = $conn->prepare("SELECT metric_value FROM dashboard_stats WHERE metric_key = :metric_key");
        $stmt->bindParam(":metric_key", $key, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $metrics[$key] = (int)$row["metric_value"];
        } else {
            $insert = $conn->prepare("INSERT INTO dashboard_stats (metric_key, metric_value) VALUES (:metric_key, :metric_value)");
            $insert->bindParam(":metric_key", $key, PDO::PARAM_STR);
            $insert->bindParam(":metric_value", $defaultValue, PDO::PARAM_INT);
            $insert->execute();
            $metrics[$key] = $defaultValue;
        }
    }

    $totalOrders    = $metrics["total_orders"];
    $totalDelivered = $metrics["total_delivered"];
    $totalCanceled  = $metrics["total_canceled"];
    $totalRevenue   = $metrics["total_revenue"];
    $openOrders     = $metrics["open_orders"];

    $allMonths = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

    $defaultMonthlyOrders = [
        "Jan" => ["total_orders" => 40, "delivered" => 400, "canceled" => 15],
        "Feb" => ["total_orders" => 45, "delivered" => 500, "canceled" => 18],
        "Mar" => ["total_orders" => 50, "delivered" => 600, "canceled" => 20],
        "Apr" => ["total_orders" => 55, "delivered" => 700, "canceled" => 25],
        "May" => ["total_orders" => 60, "delivered" => 800, "canceled" => 30],
        "Jun" => ["total_orders" => 65, "delivered" => 900, "canceled" => 32],
        "Jul" => ["total_orders" => 70, "delivered" => 1000, "canceled" => 35],
        "Aug" => ["total_orders" => 75, "delivered" => 1100, "canceled" => 38],
        "Sep" => ["total_orders" => 80, "delivered" => 1200, "canceled" => 40],
        "Oct" => ["total_orders" => 82, "delivered" => 1300, "canceled" => 43],
        "Nov" => ["total_orders" => 85, "delivered" => 1400, "canceled" => 45],
        "Dec" => ["total_orders" => 87, "delivered" => 1500, "canceled" => 50],
    ];

    $monthlyData = [];

    foreach ($allMonths as $m) {
        $stmt = $conn->prepare("
        SELECT total_orders, delivered, canceled
        FROM dashboard_monthly_orders
        WHERE month_label = :month_label
    ");
        $stmt->bindParam(":month_label", $m, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $rowTotalOrders = (int)$row["total_orders"];
            $rowDelivered   = (int)$row["delivered"];
            $rowCanceled    = (int)$row["canceled"];
        } else {
            $def = $defaultMonthlyOrders[$m];

            $insert = $conn->prepare("
            INSERT INTO dashboard_monthly_orders (month_label, total_orders, delivered, canceled)
            VALUES (:month_label, :total_orders, :delivered, :canceled)
        ");
            $insert->bindParam(":month_label", $m, PDO::PARAM_STR);
            $insert->bindParam(":total_orders", $def["total_orders"], PDO::PARAM_INT);
            $insert->bindParam(":delivered", $def["delivered"], PDO::PARAM_INT);
            $insert->bindParam(":canceled", $def["canceled"], PDO::PARAM_INT);
            $insert->execute();

            $rowTotalOrders = $def["total_orders"];
            $rowDelivered   = $def["delivered"];
            $rowCanceled    = $def["canceled"];
        }

        $monthlyData[] = [
            "month"        => $m,
            "total_orders" => $rowTotalOrders,
            "delivered"    => $rowDelivered,
            "canceled"     => $rowCanceled
        ];
    }

    try {
        $conn->exec("
        CREATE TABLE IF NOT EXISTS dashboard_monthly_revenue (
            id INT AUTO_INCREMENT PRIMARY KEY,
            month_label VARCHAR(10) NOT NULL UNIQUE,
            revenue INT NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    } catch (PDOException $e) {
    }

    $defaultMonthlyRevenue = [
        "Jan" => 50,
        "Feb" => 58,
        "Mar" => 64,
        "Apr" => 69,
        "May" => 76,
        "Jun" => 82,
        "Jul" => 89,
        "Aug" => 96,
        "Sep" => 102,
        "Oct" => 109,
        "Nov" => 115,
        "Dec" => 122
    ];

    $monthlyRevenue = [];

    foreach ($allMonths as $m) {
        $stmt = $conn->prepare("
        SELECT revenue
        FROM dashboard_monthly_revenue
        WHERE month_label = :month_label
    ");
        $stmt->bindParam(":month_label", $m, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $rev = (int)$row["revenue"];
        } else {
            $def = $defaultMonthlyRevenue[$m];

            $insert = $conn->prepare("
            INSERT INTO dashboard_monthly_revenue (month_label, revenue)
            VALUES (:month_label, :revenue)
        ");
            $insert->bindParam(":month_label", $m, PDO::PARAM_STR);
            $insert->bindParam(":revenue", $def, PDO::PARAM_INT);
            $insert->execute();

            $rev = $def;
        }

        $monthlyRevenue[] = [
            "month"   => $m,
            "revenue" => $rev
        ];
    }
?>
<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Admin Dashboard</title>
            <link rel="stylesheet" href="../../Css/Admin/Admin_Homepage.css">
        </head>
        <body class="admin-body">
            <div class="admin-layout">

                <aside class="admin-sidebar">
                    <div class="admin-logo-box">
                        <div class="admin-logo-img-wrap">
                            <img src="../../Image/Admin/logo.png" class="admin-logo-img" alt="E-Baon Logo">
                        </div>
                        <div class="admin-logo-text-group">
                            <div class="admin-logo-text-main">E-Baon</div>
                            <div class="admin-logo-text-sub">Admin</div>
                        </div>
                    </div>
                    <nav class="admin-nav">
                        <button type="button" class="admin-sidebar-item active" data-panel="panel-dashboard">Dashboard</button>
                        <button type="button" class="admin-sidebar-item" data-panel="panel-orders">Total Orders Management</button>
                        <button type="button" class="admin-sidebar-item" data-panel="panel-delivered">Total Delivered Management</button>
                        <button type="button" class="admin-sidebar-item" data-panel="panel-canceled">Total Canceled Management</button>
                        <button type="button" class="admin-sidebar-item" data-panel="panel-open-orders">Orders Being Process Management</button>
                        <button type="button" class="admin-sidebar-item" data-panel="panel-revenue">Total Profit Management</button>
                        <button type="button" class="admin-sidebar-item" data-panel="panel-monthly">Monthly Performance Management</button>
                        <button type="button" class="admin-sidebar-item" data-panel="panel-monthly-revenue">Revenue Management</button>
                    </nav>
                    <div class="admin-sidebar-footer">
                        <a href="../../Main/Logout.php" class="admin-logout-link">Logout</a>
                    </div>
                </aside>

                <div class="admin-main">

                    <header class="admin-header">
                        <div class="admin-search-box">
                            <input type="text" placeholder="Search here">
                        </div>
                        <div class="admin-header-right">
                            <div class="admin-user">
                                <span class="admin-user-name"><?php echo htmlspecialchars($username); ?></span>
                                <span class="admin-user-role">Administrator</span>
                            </div>
                        </div>
                    </header>

                    <div class="admin-content">

                        <section id="panel-dashboard" class="admin-panel active">
                            <div class="admin-panel-title-row">
                                <div>
                                    <h1 class="admin-page-title">Dashboard</h1>
                                    <p class="admin-page-subtitle">Overview of orders and revenue</p>
                                </div>
                            </div>

                            <div class="admin-cards-row">
                                <div class="admin-card">
                                    <div class="admin-card-label">Total Orders</div>
                                    <div class="admin-card-value" id="metric-total-orders"><?php echo $totalOrders; ?></div>
                                </div>
                                <div class="admin-card">
                                    <div class="admin-card-label">Total Delivered</div>
                                    <div class="admin-card-value" id="metric-total-delivered"><?php echo $totalDelivered; ?></div>
                                </div>
                                <div class="admin-card">
                                    <div class="admin-card-label">Total Canceled</div>
                                    <div class="admin-card-value" id="metric-total-canceled"><?php echo $totalCanceled; ?></div>
                                </div>
                                <div class="admin-card">
                                    <div class="admin-card-label">Total Profit (₱)</div>
                                    <div class="admin-card-value" id="metric-total-revenue"><?php echo $totalRevenue; ?></div>
                                </div>
                                <div class="admin-card">
                                    <div class="admin-card-label">Orders Being Process</div>
                                    <div class="admin-card-value" id="metric-open-orders"><?php echo $openOrders; ?></div>
                                </div>
                            </div>

                            <div class="admin-charts-row">
                                <div class="admin-chart-card admin-chart-wide">
                                    <div class="admin-chart-title">Monthly Performance</div>
                                    <canvas id="ordersBarChart"></canvas>
                                </div>
                                <div class="admin-chart-card">
                                    <div class="admin-chart-title">Overall Statistics</div>
                                    <canvas id="ordersPieChart"></canvas>
                                </div>
                                <div class="admin-chart-card">
                                    <div class="admin-chart-title">Revenue</div>
                                    <canvas id="revenueLineChart"></canvas>
                                </div>
                            </div>
                        </section>

                        <section id="panel-orders" class="admin-panel">
                            <h2 class="admin-panel-heading">Edit Total Orders Management</h2>
                            <form id="form-total-orders" class="admin-form">
                                <label for="input-total-orders" class="admin-form-label">Total Orders (overall)</label>
                                <input type="number" id="input-total-orders" name="metric_value" min="0" class="admin-form-input" value="<?php echo $totalOrders; ?>">
                                <button type="submit" class="admin-form-button">Save</button>
                            </form>
                        </section>

                        <section id="panel-delivered" class="admin-panel">
                            <h2 class="admin-panel-heading">Edit Total Delivered Management</h2>
                            <form id="form-total-delivered" class="admin-form">
                                <label for="input-total-delivered" class="admin-form-label">Total Delivered (overall)</label>
                                <input type="number" id="input-total-delivered" name="metric_value" min="0" class="admin-form-input" value="<?php echo $totalDelivered; ?>">
                                <button type="submit" class="admin-form-button">Save</button>
                            </form>
                        </section>

                        <section id="panel-canceled" class="admin-panel">
                            <h2 class="admin-panel-heading">Edit Total Canceled Management</h2>
                            <form id="form-total-canceled" class="admin-form">
                                <label for="input-total-canceled" class="admin-form-label">Total Canceled (overall)</label>
                                <input type="number" id="input-total-canceled" name="metric_value" min="0" class="admin-form-input" value="<?php echo $totalCanceled; ?>">
                                <button type="submit" class="admin-form-button">Save</button>
                            </form>
                        </section>

                        <section id="panel-open-orders" class="admin-panel">
                            <h2 class="admin-panel-heading">Edit Orders Being Process Management</h2>
                            <form id="form-open-orders" class="admin-form">
                                <label for="input-open-orders" class="admin-form-label">Orders Being Process (overall)</label>
                                <input
                                    type="number"
                                    id="input-open-orders"
                                    name="metric_value"
                                    min="0"
                                    class="admin-form-input"
                                    value="<?php echo $openOrders; ?>">
                                <button type="submit" class="admin-form-button">Save</button>
                            </form>
                        </section>

                        <section id="panel-revenue" class="admin-panel">
                            <h2 class="admin-panel-heading">Edit Total Profit Management</h2>
                            <form id="form-total-revenue" class="admin-form">
                                <label for="input-total-revenue" class="admin-form-label">Total Profit (₱ overall)</label>
                                <input type="number" id="input-total-revenue" name="metric_value" min="0" class="admin-form-input" value="<?php echo $totalRevenue; ?>">
                                <button type="submit" class="admin-form-button">Save</button>
                            </form>
                        </section>

                        <section id="panel-monthly" class="admin-panel">
                            <h2 class="admin-panel-heading">Edit Monthly Performance Management</h2>
                            <form id="form-monthly-orders" class="admin-form-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Total Orders</th>
                                            <th>Delivered</th>
                                            <th>Canceled</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($monthlyData as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row["month"]); ?></td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        name="total_orders[<?php echo htmlspecialchars($row["month"]); ?>]"
                                                        value="<?php echo (int)$row["total_orders"]; ?>"
                                                        min="0"
                                                    >
                                                </td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        name="delivered[<?php echo htmlspecialchars($row["month"]); ?>]"
                                                        value="<?php echo (int)$row["delivered"]; ?>"
                                                        min="0"
                                                    >
                                                </td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        name="canceled[<?php echo htmlspecialchars($row["month"]); ?>]"
                                                        value="<?php echo (int)$row["canceled"]; ?>"
                                                        min="0"
                                                    >
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <button type="submit" class="admin-form-button">Save</button>
                            </form>
                        </section>

                        <section id="panel-monthly-revenue" class="admin-panel">
                            <h2 class="admin-panel-heading">Edit Revenue Management</h2>
                            <form id="form-monthly-revenue" class="admin-form-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Revenue (₱)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($monthlyRevenue as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row["month"]); ?></td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        name="revenue[<?php echo htmlspecialchars($row["month"]); ?>]"
                                                        value="<?php echo (int)$row["revenue"]; ?>"
                                                        min="0"
                                                    >
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <button type="submit" class="admin-form-button">Save</button>
                            </form>
                        </section>

                    </div>
                </div>
            </div>

            <script>
                var monthlyOrdersData  = <?php echo json_encode($monthlyData); ?>;
                var monthlyRevenueData = <?php echo json_encode($monthlyRevenue); ?>;
            </script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="../../Javascript/Admin/Admin_Homepage.js"></script>
        </body>
</html>
