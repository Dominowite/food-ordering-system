<?php
include '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $table_id = $data['table_id'];
    $status = $data['status'];

    if (!isset($_SESSION['staff_logged_in'])) {
        echo json_encode(['success' => false, 'message' => 'คุณไม่ได้เข้าสู่ระบบ']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("UPDATE tables SET status = ? WHERE id = ?");
        $stmt->execute([$status, $table_id]);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
}
?>
