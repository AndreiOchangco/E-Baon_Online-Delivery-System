<?php
    session_start();
    require_once "../Connnection/Connection.php";

    $message = "";
    $error = "";
    $email_value = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST["email"] ?? "");
        $new_password = trim($_POST["new_password"] ?? "");
        $confirm_password = trim($_POST["confirm_password"] ?? "");
        $email_value = $email;

        if ($email === "" || $new_password === "" || $confirm_password === "") {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email.";
        } elseif ($new_password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $error = "Email not found.";
            } else {
                $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $update->execute([$new_password, $email]);
                $message = "Password updated successfully. You can now sign in.";
            }
        }
    }
?>
<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Forgot Password</title>
            <link rel="stylesheet" href="../Css/Main/Forgot.css">
        </head>
        <body class="auth-body">
            <div class="auth-page">
                <div class="auth-card">
                    <div class="auth-left">
                        <h1 class="auth-title">Forgot Password</h1>

                        <?php if ($message !== ""): ?>
                            <div class="auth-success"><?php echo htmlspecialchars($message); ?></div>
                        <?php endif; ?>

                        <?php if ($error !== ""): ?>
                            <div class="auth-error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form class="auth-form" method="post" action="">
                            <label class="auth-label" for="email">Email</label>
                            <input
                                class="auth-input"
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Enter your registered email"
                                value="<?php echo htmlspecialchars($email_value); ?>"
                                required
                            >

                            <label class="auth-label" for="new_password">New Password</label>
                            <input
                                class="auth-input"
                                type="password"
                                id="new_password"
                                name="new_password"
                                placeholder="Enter new password"
                                required
                            >

                            <label class="auth-label" for="confirm_password">Confirm Password</label>
                            <input
                                class="auth-input"
                                type="password"
                                id="confirm_password"
                                name="confirm_password"
                                placeholder="Confirm new password"
                                required
                            >

                            <button class="auth-submit" type="submit">Reset Password</button>
                        </form>

                        <p class="auth-footer-text">
                            Remember your password?
                            <a class="auth-link" href="Index.php">Back to sign in</a>
                        </p>
                    </div>

                    <div class="auth-right">
                        <div class="auth-illustration">
                            <img src="../Image/Admin/logo.png" class="logo">
                        </div>
                    </div>
                </div>
            </div>
            <script src="../Javascript/Main/Forgot.js"></script>
        </body>
</html>
