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
        $pdo->beginTransaction();

        // อัพเดตสถานะของคำสั่งซื้อในฐานข้อมูล
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        if (!$stmt->execute([$status, $order_id])) {
            throw new Exception("Failed to update order status.");
        }

        // ตรวจสอบว่ามีคำสั่งซื้อใดถูกอัพเดตหรือไม่
        if ($stmt->rowCount() === 0) {
            throw new Exception("No order was updated, possibly the order ID does not exist.");
        }

        // ถ้าสถานะเป็น 'completed' เก็บข้อมูลคำสั่งซื้อไปยังตาราง order_history
        if ($status === 'completed') {
            // คัดลอกคำสั่งซื้อที่เสร็จสิ้นไปยังตาราง order_history
            $stmt = $pdo->prepare("
                INSERT INTO order_history (order_id, table_id, status, order_time, completion_time)
                SELECT id, table_id, status, order_time, NOW() FROM orders WHERE id = ?
            ");
            if (!$stmt->execute([$order_id])) {
                throw new Exception("Failed to copy order to order_history: " . implode(", ", $stmt->errorInfo()));
            }
        }

        $pdo->commit();

        // เปลี่ยนเส้นทางกลับไปยังแดชบอร์ด
        header('Location: dashboard.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
} else {
    // ข้อความแสดงผลหากไม่มี order_id ถูกส่งมา
    die("Order ID is required.");
}
?>
