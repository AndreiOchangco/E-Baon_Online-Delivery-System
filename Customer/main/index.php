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
  <style>
    /* Basic Reset */
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body { font-family: Arial, sans-serif; background: #f7f7f7; }

    /* Header Bar */
    header {
      width: 100%;
      background-color: #007bff;
      color: #fff;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    header .logo {
      font-size: 1.5rem;
      font-weight: bold;
    }

    header .welcome {
      font-size: 1rem;
      margin-right: 10px;
    }

    header .logout-btn {
      padding: 8px 15px;
      background-color: #fff;
      color: #007bff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      font-weight: bold;
    }

    header .logout-btn:hover {
      background-color: #e6e6e6;
    }

    /* Container for main content */
    .container {
      max-width: 900px;
      margin: 80px auto 20px; /* leave space for header */
      padding: 0 20px;
    }

    .form-box {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
    }

    /* Responsive adjustments */
    @media (max-width: 600px) {
      header {
        flex-direction: column;
        align-items: flex-start;
      }
      header .welcome {
        margin: 10px 0;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">
        <h3>E-Baon Delivery</h3>
    </div>
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