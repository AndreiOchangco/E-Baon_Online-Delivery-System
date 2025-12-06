<?php
    session_start();
    require_once "../Connnection/Connection.php";

    if (isset($_SESSION["role"])) {
        if ($_SESSION["role"] === "admin") {
            header("Location: ../Body/Admin/Admin_Homepage.php");
            exit();
        }
        if ($_SESSION["role"] === "customer") {
            header("Location: ../Body/Customer/Customer_Homepage.php");
            exit();
        }
        if ($_SESSION["role"] === "delivery") {
            header("Location: ../Body/Delivery/Delivery_Homepage.php");
            exit();
        }
    }

    $error = "";
    $email_value = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST["email"] ?? "");
        $password = trim($_POST["password"] ?? "");
        $email_value = $email;

        if ($email === "" || $password === "") {
            $error = "Please enter email and password.";
        } else {
            $stmt = $conn->prepare("SELECT id, full_name, email, password, role FROM users WHERE email = ? AND password = ? LIMIT 1");
            $stmt->execute([$email, $password]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["user_name"] = $row["full_name"];
                $_SESSION["email"] = $row["email"];
                $_SESSION["role"] = $row["role"];

                if ($row["role"] === "admin") {
                    header("Location: ../Body/Admin/Admin_Homepage.php");
                    exit();
                }
                if ($row["role"] === "customer") {
                    header("Location: ../Body/Customer/Customer_Homepage.php");
                    exit();
                }
                if ($row["role"] === "delivery") {
                    header("Location: ../Body/Delivery/Delivery_Homepage.php");
                    exit();
                }

                header("Location: ../Body/Customer/Customer_Homepage.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        }
    }
?>
<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Sign In</title>
            <link rel="stylesheet" href="../Css/Main/Index.css">
        </head>
        <body class="auth-body">
            <div class="auth-page">
                <div class="auth-card">
                    <div class="auth-left">
                        <h1 class="auth-title">Sign In to your Account</h1>

                        <button class="auth-google-btn" type="button">
                            <span class="auth-google-icon">G</span>
                            <span>Sign in with Google</span>
                        </button>

                        <div class="auth-divider">
                            <span class="auth-divider-line"></span>
                            <span class="auth-divider-text">or sign in with Email</span>
                            <span class="auth-divider-line"></span>
                        </div>

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
                                placeholder="Enter Email"
                                value="<?php echo htmlspecialchars($email_value); ?>"
                                required
                            >

                            <div class="auth-password-row">
                                <label class="auth-label" for="password">Password</label>
                                <a class="auth-link" href="Forgot.php">Forgot password?</a>
                            </div>

                            <div class="auth-password-box">
                                <input
                                    class="auth-input auth-password-input"
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Enter Password"
                                    required
                                >
                                <button type="button" class="auth-password-toggle">Show</button>
                            </div>

                            <label class="auth-remember">
                                <input type="checkbox" id="rememberMe">
                                <span>Remember me</span>
                            </label>

                            <button class="auth-submit" type="submit">Sign In</button>
                        </form>

                        <p class="auth-footer-text">
                            Not registered yet?
                            <a class="auth-link" href="Register.php">Create an account</a>
                        </p>
                    </div>

                    <div class="auth-right">
                        <div class="auth-illustration">
                            <img src="../Image/Admin/logo.png" class="logo">
                        </div>
                    </div>
                </div>
            </div>
            <script src="../Javascript/Main/Index.js"></script>
        </body>
</html>
