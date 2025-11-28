<?php
session_start();
require "../Connection/Connection.php";

$forgot_error = $_SESSION["forgot_error"] ?? "";
$forgot_success = $_SESSION["forgot_success"] ?? "";
unset($_SESSION["forgot_error"], $_SESSION["forgot_success"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $new_password = trim($_POST["new_password"] ?? "");

    if ($username === "" || $new_password === "") {
        $_SESSION["forgot_error"] = "Please fill in all fields.";
        header("Location: Forgot.php");
        exit();
    }

    try {
        $check_sql = "SELECT id FROM users WHERE username = :username LIMIT 1";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bindParam(":username", $username);
        $check_stmt->execute();
        $user = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION["forgot_error"] = "No username found.";
            header("Location: Forgot.php");
            exit();
        }

        $update_sql = "UPDATE users SET password = :password WHERE username = :username";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bindParam(":password", $new_password);
        $update_stmt->bindParam(":username", $username);
        $update_stmt->execute();

        $_SESSION["forgot_success"] = "Password has been updated.";
        header("Location: Forgot.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION["forgot_error"] = "Database error.";
        header("Location: Forgot.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Baon Forgot Password</title>
    <link rel="stylesheet" href="../Css/Forgot.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="logo">logo</div>

<div class="card">
    <h2>Forgot Password</h2>

    <form action="Forgot.php" method="POST">
        <div class="field">
            <span>🙎🏻‍♂️</span>
            <input type="text" name="username" placeholder="user" required>
        </div>

        <div class="field password-field">
            <span>🔒</span>
            <input type="password" id="forgot_password" name="new_password" placeholder="new password" required>
            <span class="password-icon" id="toggleForgotPassword">
                <i id="forgotEyeIcon" class="fa-solid fa-eye-slash"></i>
            </span>
        </div>

        <button class="btn-login" type="submit">Update Password</button>
    </form>

    <div class="bottom-text">
        Remembered your password?
        <a href="Index.php" class="link-green">Login</a>
    </div>

    <?php if ($forgot_success): ?>
        <div id="forgotSuccessModal" class="modal">
            <div class="modal-content">
                <p><?php echo htmlspecialchars($forgot_success); ?></p>
                <button id="forgotCloseModal">OK</button>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($forgot_error): ?>
        <div id="forgotErrorModal" class="modal">
            <div class="modal-content">
                <p><?php echo htmlspecialchars($forgot_error); ?></p>
                <button id="forgotErrorClose">OK</button>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="../Javascript/Forgot.js"></script>
</body>
</html>
