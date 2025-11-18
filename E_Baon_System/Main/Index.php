<?php
session_start();
$error = $_SESSION['login_error'] ?? "";
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>E-Baon Login</title>
  <link rel="stylesheet" href="../Css/style.css">
</head>
<body>

<div class="card">
  <div class="logo">logo</div>

  <form action="Login.php" method="POST">
    <div class="field">
      <span>🙎🏻‍♂️</span>
      <input type="text" name="username" placeholder="user" required>
    </div>

    <div class="field">
      <span>🔒</span>
      <input type="password" name="password" placeholder="password" required>
    </div>

    <div class="field">
      <span>⚙️</span>
      <select name="role" required>
        <option disabled selected>role</option>
        <option value="admin">admin</option>
        <option value="customer">customer</option>
        <option value="delivery">delivery ID</option>
      </select>
    </div>

    <button class="btn-login" type="submit">LOGIN</button>
  </form>

  <div class="forgot">Forgot Password</div>

  <?php if ($error): ?>
    <p class="error"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>
</div>

</body>
</html>
