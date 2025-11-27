<?php
session_start();
require "../Connection/Connection.php";

$login_error = $_SESSION["login_error"] ?? "";
unset($_SESSION["login_error"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Baon Login</title>
    <link rel="shortcut icon" href="../images/e-baon-logo.png">
    <link rel="stylesheet" href="../Css/Index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="auth-container">
    <img class="logo-img" src="../images/e-baon-logo.png" alt="">

    <div class="card">
        <form action="Login.php" method="POST">
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
                    <input type="password" id="login_password" name="password" placeholder="password" required>
                    <span class="password-icon" id="toggleEye">
                        <i id="eyeIcon" class="fa-solid fa-eye-slash"></i>
                    </span>
                </div>
            </div>


            <button class="btn-login" type="submit">LOGIN</button>
        </form>

        <div class="bottom-text" id="bottomText">
            Don't have an account?
            <a href="Register.php" class="link-green" id="btLink">Register</a>
        </div>
    </div>
</div>

<div id="loginModal" class="modal">
    <div class="modal-content">
        <p id="loginModalText"></p>
        <button id="loginModalBtn">OK</button>
    </div>
</div>

<script>
    var phpLoginError = "<?php echo htmlspecialchars($login_error, ENT_QUOTES, 'UTF-8'); ?>";
</script>
<script src="../Javascript/Index.js"></script>
</body>
</html>
