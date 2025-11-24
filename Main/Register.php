<?php
session_start();
require "../Connection/Connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_username = trim($_POST["new_username"] ?? "");
    $new_password = trim($_POST["new_password"] ?? "");
    $new_role     = $_POST["new_role"] ?? "";

    if ($new_username === "" || $new_password === "" || $new_role === "") {
        $_SESSION["register_error"] = "Please fill in all fields.";
        header("Location: Register.php");
        exit();
    }

    $allowed_roles = ["admin", "customer", "delivery"];
    if (!in_array($new_role, $allowed_roles, true)) {
        $_SESSION["register_error"] = "Invalid role.";
        header("Location: Register.php");
        exit();
    }

    try {
        $check_sql  = "SELECT id FROM users WHERE username = :username LIMIT 1";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bindParam(":username", $new_username);
        $check_stmt->execute();

        if ($check_stmt->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION["register_error"] = "Username already exists.";
            header("Location: Register.php");
            exit();
        }

        $insert_sql  = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bindParam(":username", $new_username);
        $insert_stmt->bindParam(":password", $new_password);
        $insert_stmt->bindParam(":role", $new_role);
        $insert_stmt->execute();

        $_SESSION["register_success"] = "Your account has been registered!";
        header("Location: Index.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION["register_error"] = "Database error.";
        header("Location: Register.php");
        exit();
    }
}

$reg_error   = $_SESSION["register_error"] ?? "";
$reg_success = $_SESSION["register_success"] ?? "";
unset($_SESSION["register_error"], $_SESSION["register_success"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Baon Register</title>
    <link rel="stylesheet" href="../Css/Register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="auth-container">
    <img class="logo-img" src="../images/e-baon-logo.png" alt="">

    <div class="card">
        <form action="Register.php" method="POST">
            <div class="field">
                <span>🙎🏻‍♂️</span>
                <input type="text" name="new_username" placeholder="add new username" required>
            </div>

            <div class="field password-field">
                <span>🔒</span>
                <input type="password" id="reg_password" name="new_password" placeholder="add new password" required>
                <span class="password-icon" id="toggleRegPassword">
                    <i id="regEyeIcon" class="fa-solid fa-eye-slash"></i>
                </span>
            </div>

            <div class="field">
                <span>⚙️</span>
                <select name="new_role" class="select-role" id="roleSelect" required>
                    <option disabled selected>role</option>
                    <option value="customer">customer</option>
                    <option value="delivery">delivery ID</option>
                </select>
                <i class="fa-solid fa-chevron-down select-arrow" id="roleArrow"></i>
            </div>

            <button class="btn-login" type="submit">Register</button>
        </form>

        <div class="small-text">
            Go back to
            <a href="Index.php" class="link-green">Login</a>
            page
        </div>

        <?php if ($reg_success): ?>
            <div id="successModal" class="modal">
                <div class="modal-content">
                    <p><?php echo htmlspecialchars($reg_success); ?></p>
                    <button id="closeModal">OK</button>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($reg_error): ?>
            <div id="errorModal" class="modal">
                <div class="modal-content">
                    <p><?php echo htmlspecialchars($reg_error); ?></p>
                    <button id="closeErrorModal">OK</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="../Javascript/Register.js"></script>
</body>
</html>
