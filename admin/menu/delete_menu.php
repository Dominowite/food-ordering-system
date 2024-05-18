<?php
session_start();
include '../../includes/db.php';
include '../functions.php';

// ตรวจสอบการเข้าสู่ระบบแอดมิน
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ลบเมนูอาหารจากฐานข้อมูล
    $stmt = $pdo->prepare("DELETE FROM menus WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: manage_menus.php');
    exit();
}
?>
