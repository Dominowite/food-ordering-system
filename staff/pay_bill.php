<?php
include '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $table_id = $data['table_id'];

    if (!isset($_SESSION['staff_logged_in'])) {
        echo json_encode(['success' => false, 'message' => 'คุณไม่ได้เข้าสู่ระบบ']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        // ดึงข้อมูลโต๊ะ
        $stmt = $pdo->prepare("SELECT table_number FROM tables WHERE id = ?");
        $stmt->execute([$table_id]);
        $table = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$table) {
            throw new Exception("ไม่พบโต๊ะที่ระบุ");
        }

        $table_number = $table['table_number'];

        // ดึงคำสั่งซื้อทั้งหมดที่ยังไม่ชำระสำหรับโต๊ะที่ระบุ
        $stmt = $pdo->prepare("
            SELECT o.id AS order_id, o.order_time, oi.menu_id, oi.quantity, m.price, (oi.quantity * m.price) AS item_total 
            FROM orders o 
            JOIN order_items oi ON o.id = oi.order_id 
            JOIN menus m ON oi.menu_id = m.id 
            WHERE o.table_id = ? AND o.payment_status = 'pending'
        ");
        $stmt->execute([$table_id]);
        $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($orderItems)) {
            throw new Exception("ไม่พบคำสั่งซื้อที่ยังไม่ชำระสำหรับโต๊ะนี้");
        }

        // คำนวณยอดรวมของคำสั่งซื้อทั้งหมดสำหรับโต๊ะนี้
        $total_amount = array_sum(array_column($orderItems, 'item_total'));

        // เพิ่มข้อมูลในตาราง receipts
        $stmt = $pdo->prepare("INSERT INTO receipts (table_number, total_amount) VALUES (?, ?)");
        $stmt->execute([$table_number, $total_amount]);
        $receipt_id = $pdo->lastInsertId();

        // เพิ่มรายการคำสั่งซื้อในตาราง receipt_items
        foreach ($orderItems as $item) {
            $stmt = $pdo->prepare("INSERT INTO receipt_items (receipt_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$receipt_id, $item['menu_id'], $item['quantity'], $item['price']]);
        }

        // ย้ายข้อมูลคำสั่งซื้อไปที่ order_history
        $stmt = $pdo->prepare("INSERT INTO order_history (order_id, table_id, status, order_time, completion_time) VALUES (?, ?, ?, ?, NOW())");
        foreach ($orderItems as $item) {
            $stmt->execute([$item['order_id'], $table_id, 'completed', $item['order_time']]);
        }

        // ปรับสถานะการชำระเงิน
        $stmt = $pdo->prepare("UPDATE orders SET payment_status = 'paid' WHERE table_id = ? AND payment_status = 'pending'");
        $stmt->execute([$table_id]);

        // ปิดการตรวจสอบ foreign key ชั่วคราว
        $pdo->exec("SET FOREIGN_KEY_CHECKS=0");

        // ลบข้อมูลใน order_items และ orders
        $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id IN (SELECT id FROM orders WHERE table_id = ? AND payment_status = 'paid')");
        $stmt->execute([$table_id]);

        $stmt = $pdo->prepare("DELETE FROM orders WHERE table_id = ? AND payment_status = 'paid'");
        $stmt->execute([$table_id]);

        // เปิดการตรวจสอบ foreign key อีกครั้ง
        $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

        $pdo->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
}
?>
