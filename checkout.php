<?php
include 'includes/db.php';
include 'includes/functions.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['table_id'], $data['items'], $data['total'])) {
    $table_id = intval($data['table_id']);
    $total = floatval($data['total']);
    $items = $data['items'];

    try {
        $pdo->beginTransaction();

        // เพิ่มคำสั่งซื้อ
        $stmt = $pdo->prepare("INSERT INTO orders (table_id, total, status) VALUES (?, ?, 'pending')");
        $stmt->execute([$table_id, $total]);
        $order_id = $pdo->lastInsertId();

        // เพิ่มรายการคำสั่งซื้อ
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
        }

        $pdo->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid order data']);
}
