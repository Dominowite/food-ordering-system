<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

// ตรวจสอบการเข้าสู่ระบบของผู้ดูแลระบบ
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin/login.php');
    exit();
}

// ลบโต๊ะ
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM tables WHERE id = ?");
$stmt->execute([$id]);

header('Location: manage_tables.php');
exit();
?>
