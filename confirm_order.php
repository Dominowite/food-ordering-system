<?php
include 'includes/db.php';
include 'includes/functions.php';

// รับข้อมูลจาก client
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['table_id']) && isset($data['items'])) {
    $table_id = $data['table_id'];
    $items = $data['items'];

    // ตรวจสอบว่า table_id มีอยู่ในฐานข้อมูลหรือไม่
    $stmt = $pdo->prepare("SELECT id FROM tables WHERE table_number = ?");
    $stmt->execute([$table_id]);
    $table = $stmt->fetch();

    if ($table) {
        $table_id = $table['id'];

        // สร้างคำสั่งซื้อในตาราง orders
        $stmt = $pdo->prepare("INSERT INTO orders (table_id) VALUES (?)");
        $stmt->execute([$table_id]);
        $order_id = $pdo->lastInsertId();

        // เพิ่มรายการคำสั่งซื้อในตาราง order_items
        foreach ($items as $item) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$order_id, $item['id'], $item['quantity']]);
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Table ID ไม่ถูกต้อง']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
}
?>
