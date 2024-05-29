<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

if (!isset($_SESSION['kitchen_logged_in']) && !isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['order_id']) && isset($_GET['status'])) {
    $order_id = filter_var($_GET['order_id'], FILTER_VALIDATE_INT); // ตรวจสอบและกรอง order_id
    $status = filter_var($_GET['status'], FILTER_SANITIZE_STRING);  // กรอง status

    if (!$order_id || !$status) {
        die("รหัสคำสั่งซื้อหรือสถานะไม่ถูกต้อง");
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        if (!$stmt->execute([$status, $order_id])) {
            throw new Exception("การอัพเดตสถานะคำสั่งซื้อไม่สำเร็จ");
        }

        if ($stmt->rowCount() === 0) {
            throw new Exception("ไม่มีคำสั่งซื้อที่ถูกอัพเดต อาจเป็นเพราะรหัสคำสั่งซื้อไม่ถูกต้อง");
        }

        if ($status === 'completed') {
            $stmt = $pdo->prepare("INSERT INTO order_history (order_id, table_id, status, order_time, completion_time)
                                   SELECT id, table_id, status, order_time, NOW() FROM orders WHERE id = ?");
            if (!$stmt->execute([$order_id])) {
                throw new Exception("การคัดลอกคำสั่งซื้อไปยังประวัติคำสั่งซื้อไม่สำเร็จ");
            }
        }

        $pdo->commit();
        header('Location: dashboard.php?status=success');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("ข้อผิดพลาด: " . $e->getMessage());
    }
} else {
    die("ต้องระบุรหัสคำสั่งซื้อและสถานะ");
}
?>
