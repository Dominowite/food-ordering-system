<?php
include 'includes/db.php';
include 'includes/functions.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['table_id']) || !isset($data['items']) || !is_array($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit();
}

$table_id = intval($data['table_id']);

// ตรวจสอบว่า table_id ถูกต้อง
$stmt = $pdo->prepare("SELECT id FROM tables WHERE id = ?");
$stmt->execute([$table_id]);
$table = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$table) {
    echo json_encode(['success' => false, 'message' => 'Invalid table ID']);
    exit();
}

// Process order
try {
    $pdo->beginTransaction();

    // สร้างคำสั่งซื้อใหม่
    $stmt = $pdo->prepare("INSERT INTO orders (table_id, status, payment_status) VALUES (?, 'pending', 'pending')");
    $stmt->execute([$table_id]);
    $order_id = $pdo->lastInsertId();

    // เพิ่มรายการอาหารในคำสั่งซื้อ
    foreach ($data['items'] as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$order_id, $item['id'], $item['quantity']]);
    }

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการยืนยันคำสั่งซื้อ: ' . $e->getMessage()]);
}
?>
