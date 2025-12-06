<?php
    session_start();
    header("Content-Type: application/json");

    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Unauthorized"]);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Invalid method"]);
        exit();
    }

    require_once "../../Connnection/Connection.php";

    $dataJson = $_POST["data"] ?? "";
    if ($dataJson === "") {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Missing data"]);
        exit();
    }

    $data = json_decode($dataJson, true);
    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid JSON"]);
        exit();
    }

    $sql = "
    INSERT INTO dashboard_monthly_orders (month_label, total_orders, delivered, canceled)
    VALUES (:month_label, :total_orders, :delivered, :canceled)
    ON DUPLICATE KEY UPDATE
        total_orders = VALUES(total_orders),
        delivered = VALUES(delivered),
        canceled = VALUES(canceled)
";

    $stmt = $conn->prepare($sql);

    $resultMonths = [];

    foreach ($data as $row) {
        if (!isset($row["month"])) {
            continue;
        }

        $month = $row["month"];

        $totalOrders = isset($row["total_orders"]) && is_numeric($row["total_orders"]) ? (int)$row["total_orders"] : 0;
        $delivered   = isset($row["delivered"]) && is_numeric($row["delivered"]) ? (int)$row["delivered"] : 0;
        $canceled    = isset($row["canceled"]) && is_numeric($row["canceled"]) ? (int)$row["canceled"] : 0;

        $stmt->bindParam(":month_label", $month, PDO::PARAM_STR);
        $stmt->bindParam(":total_orders", $totalOrders, PDO::PARAM_INT);
        $stmt->bindParam(":delivered", $delivered, PDO::PARAM_INT);
        $stmt->bindParam(":canceled", $canceled, PDO::PARAM_INT);
        $stmt->execute();

        $resultMonths[] = [
            "month"        => $month,
            "total_orders" => $totalOrders,
            "delivered"    => $delivered,
            "canceled"     => $canceled
        ];
    }

    echo json_encode([
        "success" => true,
        "months"  => $resultMonths
    ]);
?>