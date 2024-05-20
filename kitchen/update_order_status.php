<?php
session_start();
include '../includes/db.php';

// ตรวจสอบว่าเซสชันสำหรับครัวหรือแอดมินได้ถูกตั้งค่าแล้วหรือไม่
if (!isset($_SESSION['kitchen_logged_in']) && !isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// ตรวจสอบว่ามีการส่ง order_id มาหรือไม่
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    // ตั้งค่า $status เป็น 'pending' หาก $_GET['status'] ไม่มีค่าหรือเป็น null
    $status = $_GET['status'] ?? 'pending';

    try {
        // อัพเดตสถานะของคำสั่งซื้อในฐานข้อมูล
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        if (!$stmt->execute([$status, $order_id])) {
            throw new Exception("Failed to update order status.");
        }

        // ตรวจสอบว่ามีคำสั่งซื้อใดถูกอัพเดตหรือไม่
        if ($stmt->rowCount() === 0) {
            throw new Exception("No order was updated, possibly the order ID does not exist.");
        }

        // เปลี่ยนเส้นทางกลับไปยังแดชบอร์ด
        header('Location: dashboard.php');
        exit();
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // ข้อความแสดงผลหากไม่มี order_id ถูกส่งมา
    die("Order ID is required.");
}
?>
