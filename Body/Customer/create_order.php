<?php
    session_start();

    header("Content-Type: application/json");

    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized"]);
        exit();
    }

    require_once "../../Connnection/Connection.php";

    $raw  = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if (!$data || !isset($data["cart"]) || !is_array($data["cart"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid payload"]);
        exit();
    }

    $cart        = $data["cart"];
    $fromAddress = trim($data["from_address"] ?? "");
    $toAddress   = trim($data["to_address"] ?? "");

    if ($fromAddress === "" || $toAddress === "") {
        echo json_encode(["success" => false, "message" => "Missing address"]);
        exit();
    }

    if (empty($cart)) {
        echo json_encode(["success" => false, "message" => "Cart is empty"]);
        exit();
    }

    $customerId = (int)($_SESSION["user_id"]);

    try {
        $conn->beginTransaction();

        $subtotal = 0;
        foreach ($cart as $item) {
            $price    = isset($item["price"]) ? (float)$item["price"] : 0;
            $qty      = isset($item["quantity"]) ? (int)$item["quantity"] : 0;
            $subtotal += $price * $qty;
        }

        $deliveryFee = $subtotal > 0 ? 9.00 : 0.00;
        $total       = $subtotal + $deliveryFee;

        $status = "preparing";

        $stmt = $conn->prepare("
        INSERT INTO orders (customer_id, from_address, to_address, subtotal, delivery_fee, total, status)
        VALUES (:customer_id, :from_address, :to_address, :subtotal, :delivery_fee, :total, :status)
    ");
        $stmt->execute([
            ":customer_id"  => $customerId,
            ":from_address" => $fromAddress,
            ":to_address"   => $toAddress,
            ":subtotal"     => $subtotal,
            ":delivery_fee" => $deliveryFee,
            ":total"        => $total,
            ":status"       => $status
        ]);

        $orderId = (int)$conn->lastInsertId();

        $itemStmt = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, product_name, price, quantity)
        VALUES (:order_id, :product_id, :product_name, :price, :quantity)
    ");

        foreach ($cart as $item) {
            $pid   = (int)($item["id"] ?? 0);
            $name  = (string)($item["name"] ?? "");
            $price = isset($item["price"]) ? (float)$item["price"] : 0;
            $qty   = isset($item["quantity"]) ? (int)$item["quantity"] : 0;

            if ($pid <= 0 || $qty <= 0) {
                continue;
            }

            $itemStmt->execute([
                ":order_id"     => $orderId,
                ":product_id"   => $pid,
                ":product_name" => $name,
                ":price"        => $price,
                ":quantity"     => $qty
            ]);
        }

        $conn->commit();

        echo json_encode([
            "success"  => true,
            "order_id" => $orderId
        ]);
    } catch (PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Database error"]);
    }
?>