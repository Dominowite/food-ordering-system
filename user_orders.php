<?php
include 'includes/db.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();

    if ($order) {
        echo "<h1>Order Details</h1>";
        echo "<p>Order ID: " . $order['id'] . "</p>";
        echo "<p>Status: " . $order['status'] . "</p>";
        // แสดงรายละเอียดอื่นๆ ตามต้องการ
    } else {
        echo "<p>Order not found.</p>";
    }
} else {
    echo "<p>No order specified.</p>";
}
