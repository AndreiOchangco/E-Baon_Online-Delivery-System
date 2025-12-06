<?php
    session_start();

    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "delivery") {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Unauthorized"]);
        exit();
    }

    require_once "../../Connnection/Connection.php";

    $raw  = file_get_contents("php://input");
    $data = json_decode($raw, true);

    $orderId = isset($data["order_id"]) ? (int)$data["order_id"] : 0;
    $status  = isset($data["status"]) ? trim($data["status"]) : "";

    $allowed = ["preparing", "delivering", "completed"];

    if ($orderId <= 0 || !in_array($status, $allowed, true)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid data"]);
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT status FROM orders WHERE id = :id");
        $stmt->bindParam(":id", $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Order not found"]);
            exit();
        }

        $stmtUpdate = $conn->prepare("
        UPDATE orders
        SET status = :status
        WHERE id = :id
    ");
        $stmtUpdate->bindParam(":status", $status, PDO::PARAM_STR);
        $stmtUpdate->bindParam(":id", $orderId, PDO::PARAM_INT);
        $stmtUpdate->execute();

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "success"        => false,
            "message"        => "Database error",
            "current_status" => $row["status"] ?? ""
        ]);
    }
?>