<?php
include 'includes/db.php';
include 'includes/functions.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['table_id']) && isset($data['items']) && is_array($data['items'])) {
    $table_id = $data['table_id'];

    $stmt = $pdo->prepare("SELECT id FROM tables WHERE table_number = ?");
    $stmt->execute([$table_id]);
    $table = $stmt->fetch();

    if ($table) {
        $table_id = $table['id'];

        $pdo->beginTransaction();
        try {
            // ตรวจสอบค่า status ที่จะถูกตั้งค่า
            $status = 'pending'; // ตั้งค่าเริ่มต้นเป็น 'pending'
            $stmt = $pdo->prepare("INSERT INTO orders (table_id, status) VALUES (?, ?)");
            $stmt->execute([$table_id, $status]);
            $order_id = $pdo->lastInsertId();

            foreach ($data['items'] as $item) {
                if (isset($item['id'], $item['quantity']) && $item['quantity'] > 0) {
                    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantity) VALUES (?, ?, ?)");
                    $stmt->execute([$order_id, $item['id'], $item['quantity']]);
                } else {
                    throw new Exception("Invalid item data");
                }
            }
            $pdo->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log('Database error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid table ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Incomplete data']);
}
?>
