<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

// ตรวจสอบการเข้าสู่ระบบของครัวหรือแอดมิน
if (!isset($_SESSION['kitchen_logged_in']) && !isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$status = isset($_GET['status']) ? $_GET['status'] : '';

if ($order_id > 0 && in_array($status, ['pending', 'preparing', 'completed'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
}

header('Location: kitchen_dashboard.php');
exit();
