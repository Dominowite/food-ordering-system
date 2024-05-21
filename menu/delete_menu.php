<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

// ตรวจสอบการเข้าสู่ระบบของแอดมิน
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// ตรวจสอบพารามิเตอร์ id
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // ตรวจสอบว่ามีเมนูที่ต้องการลบอยู่จริงหรือไม่
    $stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ?");
    $stmt->execute([$id]);
    $menu = $stmt->fetch();

    if ($menu) {
        // เริ่มต้น transaction
        $pdo->beginTransaction();
        try {
            // ลบข้อมูลที่เกี่ยวข้องในตาราง order_items ก่อน
            $stmt = $pdo->prepare("DELETE FROM order_items WHERE menu_id = ?");
            $stmt->execute([$id]);

            // ลบเมนู
            $stmt = $pdo->prepare("DELETE FROM menus WHERE id = ?");
            $stmt->execute([$id]);

            // คอมมิต transaction
            $pdo->commit();

            $_SESSION['success_message'] = "เมนูถูกลบเรียบร้อยแล้ว";
        } catch (Exception $e) {
            // ยกเลิก transaction ในกรณีเกิดข้อผิดพลาด
            $pdo->rollBack();
            $_SESSION['error_message'] = "การลบเมนูไม่สำเร็จ: " . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = "ไม่พบเมนูที่ต้องการลบ";
    }
} else {
    $_SESSION['error_message'] = "รหัสเมนูไม่ถูกต้อง";
}

header('Location: manage_menus.php');
exit();
?>
