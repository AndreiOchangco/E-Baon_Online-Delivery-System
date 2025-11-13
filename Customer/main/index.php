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
  <link rel="stylesheet" href="../css/main.css">
</head>
<body>
  <header>
    <div class="logo">E-Baon Delivery</div>
    <div class="welcome">Hello, <?php echo htmlspecialchars($username); ?>!</div>
    <a href="logout.php" class="logout-btn">Logout</a>
  </header>

  <div class="container">
    <div class="form-box">
      <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
      <p>You are logged into the Online Delivery System.</p>
    </div>
  </div>
</body>
</html>