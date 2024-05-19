<?php
session_start();
include 'includes/db.php';

// ตรวจสอบการเข้าสู่ระบบของแอดมิน (หรือผู้ทดสอบ)
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin/login.php');
    exit();
}

// การเพิ่มคำสั่งซื้อใหม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $table_id = $_POST['table_id'];
    $items = json_encode([
        ['name' => 'อาหาร A', 'quantity' => 2],
        ['name' => 'อาหาร B', 'quantity' => 1]
    ]); // รายการอาหารที่ทดสอบ

    $stmt = $pdo->prepare("INSERT INTO orders (table_id, items, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$table_id, $items]);

    echo "เพิ่มคำสั่งซื้อใหม่สำเร็จ!";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทดสอบเพิ่มคำสั่งซื้อ</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">ทดสอบเพิ่มคำสั่งซื้อ</h1>
        <form action="test_add_order.php" method="POST">
            <div class="mb-3">
                <label for="table_id" class="form-label">หมายเลขโต๊ะ</label>
                <input type="number" class="form-control" id="table_id" name="table_id" required>
            </div>
            <button type="submit" class="btn btn-primary">เพิ่มคำสั่งซื้อ</button>
        </form>
    </div>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
