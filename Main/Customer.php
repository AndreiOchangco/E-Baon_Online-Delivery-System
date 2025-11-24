<?php
session_start();
if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
    header("Location: Index.php");
    exit();
}
$username = $_SESSION["username"] ?? "Customer";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Page</title>
    <link rel="stylesheet" href="../Css/Customer.css">
</head>
<body>

<div class="card">
    <div class="logo">logo</div>
    <h2>Customer Page</h2>
    <p>Welcome, <?php echo htmlspecialchars($username); ?></p>
    <a href="Logout.php" class="btn-login">LOGOUT</a>
</div>

</body>
</html>
