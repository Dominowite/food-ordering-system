<?php
include 'includes/db.php';
include 'includes/functions.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['table_id'], $data['items']) && is_array($data['items'])) {
    $table_id = intval($data['table_id']);

    $stmt = $pdo->prepare("SELECT id FROM tables WHERE table_number = ?");
    $stmt->execute([$table_id]);
    $table = $stmt->fetch();

    if ($table) {
        $table_id = $table['id'];
        $pdo->beginTransaction();
        try {
            $status = 'pending';
            $stmt = $pdo->prepare("INSERT INTO orders (table_id, status) VALUES (?, ?)");
            $stmt->execute([$table_id, $status]);
            $order_id = $pdo->lastInsertId();

            foreach ($data['items'] as $item) {
                if (isset($item['id'], $item['quantity']) && intval($item['quantity']) > 0) {
                    $menu_id = intval($item['id']);
                    $quantity = intval($item['quantity']);

                    // Verify that the menu item exists and is available
                    $menuStmt = $pdo->prepare("SELECT id FROM menus WHERE id = ? AND status_id = (SELECT id FROM menu_statuses WHERE status_name = 'Available')");
                    $menuStmt->execute([$menu_id]);
                    if ($menuStmt->fetch()) {
                        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantity) VALUES (?, ?, ?)");
                        $stmt->execute([$order_id, $menu_id, $quantity]);
                    } else {
                        throw new Exception("Invalid menu item ID or item not available");
                    }
                } else {
                    throw new Exception("Invalid item data");
                }
            }
            $pdo->commit();
            echo json_encode(['success' => true, 'order_id' => $order_id]);
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
