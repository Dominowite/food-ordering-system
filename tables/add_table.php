<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

// ตรวจสอบการเข้าสู่ระบบของแอดมิน
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin/login.php');
    exit();
}

// การเพิ่มโต๊ะ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $table_number = $_POST['table_number'];
    $max_capacity = $_POST['max_capacity'];
    $status = $_POST['status'];

    // สร้าง URL สำหรับ QR Code ที่ลิงก์ไปยังหน้าเลือกเมนู
    $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('/tables', '', dirname($_SERVER['PHP_SELF'])) . '/menu.php';
    $tableData = $baseUrl . '?table_id=' . $table_number;
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($tableData);

    // บันทึกข้อมูลโต๊ะในฐานข้อมูล
    $stmt = $pdo->prepare("INSERT INTO tables (table_number, max_capacity, status, qr_code) VALUES (?, ?, ?, ?)");
    $stmt->execute([$table_number, $max_capacity, $status, $qrUrl]);

    header('Location: manage_tables.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มโต๊ะ</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../admin/layout/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center">เพิ่มโต๊ะ</h1>
        <form action="add_table.php" method="POST">
            <div class="mb-3">
                <label for="table_number" class="form-label">หมายเลขโต๊ะ</label>
                <input type="number" class="form-control" id="table_number" name="table_number" required>
            </div>
            <div class="mb-3">
                <label for="max_capacity" class="form-label">ความจุสูงสุด</label>
                <input type="number" class="form-control" id="max_capacity" name="max_capacity" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">สถานะ</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="available">ว่าง</option>
                    <option value="occupied">ไม่ว่าง</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
        </form>
    </div>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
