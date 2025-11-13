<?php
session_start();
if (!isset($_SESSION['user'])) {  // <-- match 'user', not 'userName'
    header("Location: login.html");
    exit();
}

$username = $_SESSION['user']; // store for display
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Homepage | E-Baon</title>
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>
  <div class="container">
    <div class="form-box">
      <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
      <p>You are logged into the Online Delivery System.</p>
      <a href="logout.php" class="btn" style="display:inline-block;margin-top:15px;">Logout</a>
    </div>
  </div>
</body>
</html>