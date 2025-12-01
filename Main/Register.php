<?php
session_start();
require "../Connection/connection.php"; // mysqli connection

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

    // Check if username exists
    $check_sql  = "SELECT id FROM users WHERE username = ? LIMIT 1";
    $stmt_check = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt_check, "s", $new_username);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $_SESSION["register_error"] = "Username already exists.";
        mysqli_stmt_close($stmt_check);
        header("Location: Register.php");
        exit();
    }
    mysqli_stmt_close($stmt_check);

    // Insert new user
    $insert_sql  = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt_insert = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($stmt_insert, "sss", $new_username, $new_password, $new_role);

    if (mysqli_stmt_execute($stmt_insert)) {
        $_SESSION["register_success"] = "Your account has been registered!";
        mysqli_stmt_close($stmt_insert);
        header("Location: Index.php");
        exit();
    } else {
        $_SESSION["register_error"] = "Database error: " . mysqli_error($conn);
        mysqli_stmt_close($stmt_insert);
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
    <title>Register | E-Baon</title>
     <link rel="shortcut icon" href="../images/e-baon-logo.png">
    <link rel="stylesheet" href="../Css/Register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="auth-container">
    <img class="logo-img" src="../images/e-baon-logo.png" alt="">

    <div class="card">
        <form action="Register.php" method="POST">
            <label class="auth-label" for="new_username">Username</label>
            <div class="field">
                <span>🙎🏻‍♂️</span>
                <input type="text" id="new_username" name="new_username" placeholder="add new username" required>
            </div>

            <label class="auth-label" for="reg_password">Password</label>
            <div class="field password-field">
                <span>🔒</span>
                <input type="password" id="reg_password" name="new_password" placeholder="add new password" required>
                <span class="password-icon" id="toggleRegPassword">
                    <i id="regEyeIcon" class="fa-solid fa-eye-slash"></i>
                </span>
            </div>

            <label class="auth-label" for="roleSelect">Role</label>
            <div class="field">
                <span>⚙️</span>
                <select name="new_role" class="select-role" id="roleSelect" required>
                    <option disabled selected>Select role</option>
                    <option value="customer">customer</option>
                    <option value="delivery">delivery</option>
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
