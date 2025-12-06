<?php
    session_start();

    header("Content-Type: application/json");

    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
        echo json_encode(["success" => false, "message" => "Unauthorized"]);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(["success" => false, "message" => "Invalid request"]);
        exit();
    }

    if (!isset($_POST["data"])) {
        echo json_encode(["success" => false, "message" => "No data received"]);
        exit();
    }

    $data = json_decode($_POST["data"], true);
    if (!is_array($data)) {
        echo json_encode(["success" => false, "message" => "Invalid JSON payload"]);
        exit();
    }

    require_once "../../Connnection/Connection.php";

    $updatedMonths = [];

    try {
        foreach ($data as $row) {
            if (!isset($row["month"])) {
                continue;
            }

            $monthLabel = $row["month"];
            $revenue = isset($row["revenue"]) ? (int)$row["revenue"] : 0;

            $check = $conn->prepare("SELECT id FROM dashboard_monthly_revenue WHERE month_label = :month_label");
            $check->bindParam(":month_label", $monthLabel, PDO::PARAM_STR);
            $check->execute();
            $existing = $check->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $update = $conn->prepare("
                UPDATE dashboard_monthly_revenue
                SET revenue = :revenue
                WHERE month_label = :month_label
            ");
                $update->bindParam(":revenue", $revenue, PDO::PARAM_INT);
                $update->bindParam(":month_label", $monthLabel, PDO::PARAM_STR);
                $update->execute();
            } else {
                $insert = $conn->prepare("
                INSERT INTO dashboard_monthly_revenue (month_label, revenue)
                VALUES (:month_label, :revenue)
            ");
                $insert->bindParam(":month_label", $monthLabel, PDO::PARAM_STR);
                $insert->bindParam(":revenue", $revenue, PDO::PARAM_INT);
                $insert->execute();
            }

            $updatedMonths[] = [
                "month"   => $monthLabel,
                "revenue" => $revenue
            ];
        }

        echo json_encode([
            "success" => true,
            "months"  => $updatedMonths
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Database error: " . $e->getMessage()
        ]);
    }
?>   