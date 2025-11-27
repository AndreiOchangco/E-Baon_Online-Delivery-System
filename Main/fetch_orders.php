<?php
session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
    http_response_code(403);
    exit();
}

// Database connection
$host = 'localhost';
$db   = 'e_baon';
$user = '';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    exit();
}

// Prepare monthly order count
$monthlyOrders = array_fill(1, 12, 0);
$currentYear = date('Y');

$stmt = $pdo->prepare("SELECT MONTH(created_at) AS month, COUNT(id) AS total
                       FROM orders
                       WHERE YEAR(created_at) = :year
                       GROUP BY MONTH(created_at)");
$stmt->execute(['year' => $currentYear]);

while ($row = $stmt->fetch()) {
    $monthlyOrders[(int)$row['month']] = (int)$row['total'];
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode(array_values($monthlyOrders));