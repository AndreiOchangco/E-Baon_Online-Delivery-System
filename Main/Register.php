<?php
    session_start();
    require_once "../Connnection/Connection.php";

    $message = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $full_name = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = trim($_POST["password"] ?? "");
        $confirm = trim($_POST["confirm"] ?? "");

        if ($full_name === "" || $email === "" || $password === "" || $confirm === "") {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email.";
        } elseif ($password !== $confirm) {
            $error = "Passwords do not match.";
        } else {
            try {
                $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'customer')");
                $stmt->execute([$full_name, $email, $password]);
                $message = "Account created successfully. You can now sign in.";
            } catch (PDOException $e) {
                if ($e->getCode() === "23000") {
                    $error = "Email is already registered.";
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            }
        }
    }
?>
<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Create Account</title>
            <link rel="stylesheet" href="../Css/Main/Register.css">
        </head>
        <body class="auth-body">
            <div class="auth-page">
                <div class="auth-card">
                    <div class="auth-left">
                        <h1 class="auth-title">Create an Account</h1>

                        <?php if ($message !== ""): ?>
                            <div class="auth-success"><?php echo htmlspecialchars($message); ?></div>
                        <?php endif; ?>

                        <?php if ($error !== ""): ?>
                            <div class="auth-error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form class="auth-form" method="post" action="">
                            <label class="auth-label" for="full_name">Full Name</label>
                            <input
                                class="auth-input"
                                type="text"
                                id="full_name"
                                name="full_name"
                                placeholder="Enter Full Name"
                                required
                            >

                            <label class="auth-label" for="email">Email</label>
                            <input
                                class="auth-input"
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Enter Email"
                                required
                            >

                            <label class="auth-label" for="password">Password</label>
                            <input
                                class="auth-input"
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Enter Password"
                                required
                            >

                            <label class="auth-label" for="confirm">Confirm Password</label>
                            <input
                                class="auth-input"
                                type="password"
                                id="confirm"
                                name="confirm"
                                placeholder="Confirm Password"
                                required
                            >

                            <button class="auth-submit" type="submit">Create Account</button>
                        </form>

                        <p class="auth-footer-text">
                            Already have an account?
                            <a class="auth-link" href="Index.php">Sign in</a>
                        </p>
                    </div>

                    <div class="auth-right">
                        <div class="auth-illustration">
                            <img src="../Image/Admin/logo.png" class="logo">
                        </div>
                    </div>
                </div>
            </div>
            <script src="../Javascript/Main/Register.js"></script>
        </body>
</html>
