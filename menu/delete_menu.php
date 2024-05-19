<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

// ตรวจสอบการเข้าสู่ระบบของแอดมิน
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// การลบเมนู
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("DELETE FROM menus WHERE id = ?");
$stmt->execute([$id]);

header('Location: manage_menus.php');
exit();
?>
