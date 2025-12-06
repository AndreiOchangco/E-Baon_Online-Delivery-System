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

    $allowedKeys = [
        "total_orders",
        "total_delivered",
        "total_canceled",
        "total_revenue",
        "open_orders"
    ];

    $metricKey      = $_POST["metric_key"]   ?? "";
    $metricValueRaw = $_POST["metric_value"] ?? "";

    if (!in_array($metricKey, $allowedKeys, true)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid metric"]);
        exit();
    }

    if (!is_numeric($metricValueRaw)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Metric value must be numeric"]);
        exit();
    }

    $metricValue = (int)$metricValueRaw;
    if ($metricValue < 0) {
        $metricValue = 0;
    }

    $sql = "
    INSERT INTO dashboard_stats (metric_key, metric_value)
    VALUES (:metric_key, :metric_value)
    ON DUPLICATE KEY UPDATE metric_value = VALUES(metric_value)
";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":metric_key", $metricKey, PDO::PARAM_STR);
    $stmt->bindParam(":metric_value", $metricValue, PDO::PARAM_INT);

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Failed to save"]);
        exit();
    }

    echo json_encode([
        "success"      => true,
        "metric_key"   => $metricKey,
        "metric_value" => $metricValue
    ]);
?>