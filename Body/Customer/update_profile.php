<?php
    session_start();
    require_once "../../Connnection/Connection.php";

    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, password=? WHERE id=?");
    $stmt->execute([
        $data["fullname"],
        $data["email"],
        $data["password"],
        $_SESSION["user_id"]
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Profile Updated"
    ]);
?>