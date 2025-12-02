<?php
session_start();
require "../Connection/connection.php"; // mysqli connection

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

    // Check if username exists
    $check_sql = "SELECT id FROM users WHERE username = ? LIMIT 1";
    $stmt_check = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt_check, "s", $username);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) === 0) {
        $_SESSION["forgot_error"] = "No username found.";
        mysqli_stmt_close($stmt_check);
        header("Location: Forgot.php");
        exit();
    }
    mysqli_stmt_close($stmt_check);

    // Update password
    $update_sql = "UPDATE users SET password = ? WHERE username = ?";
    $stmt_update = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt_update, "ss", $new_password, $username);

    if (mysqli_stmt_execute($stmt_update)) {
        $_SESSION["forgot_success"] = "Password has been updated.";
    } else {
        $_SESSION["forgot_error"] = "Database error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt_update);
    header("Location: Forgot.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Baon Forgot Password</title>
    <link rel="stylesheet" href="../Css/Forgot.css">
    <link rel="stylesheet" href="../Css/Index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="auth-container">
    <img class="logo-img" src="../images/e-baon-logo.png" alt="">

    <div class="card">
    <h2>Forgot Password</h2>

    <form action="Forgot.php" method="POST">
        <div>
            <label class="auth-label" for="name">Username</label>
            <div class="field">
                <span>🙎🏻‍♂️</span>
                <input type="text" name="username" placeholder="username" required>
            </div>
        </div>

        <div>
            <label class="auth-label" for="password">Password</label>
            <div class="field password-field">
                <span>🔒</span>
                <input type="password" id="forgot_password" name="new_password" placeholder="new password" required>
                <span class="password-icon" id="toggleForgotPassword">
                    <i id="forgotEyeIcon" class="fa-solid fa-eye-slash"></i>
                </span>
            </div>
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

<script>
    var phpLoginError = "<?php echo htmlspecialchars($login_error, ENT_QUOTES, 'UTF-8'); ?>";
</script>
<script src="../Javascript/Index.js"></script>

</body>
</html>