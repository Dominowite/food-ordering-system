<?php
include 'includes/db.php';
$order_id = $_GET['order_id'] ?? 0; // ตรวจสอบว่ามี order_id มากับ URL หรือไม่

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if ($order) {
    // แสดงรายละเอียดของคำสั่งซื้อ
    echo "<h1>Order Details</h1>";
    echo "<p>Order ID: " . htmlspecialchars($order['id']) . "</p>";
    echo "<p>Status: " . htmlspecialchars($order['status']) . "</p>";
    // ดึงรายการอื่นๆ ตามต้องการ
} else {
    echo "<p>Order not found or invalid order ID.</p>";
}
?>
