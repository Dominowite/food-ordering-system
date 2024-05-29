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
    $table_number = intval($_POST['table_number']);
    $max_capacity = intval($_POST['max_capacity']);
    $status = $_POST['status'];

    try {
        // ตรวจสอบว่าหมายเลขโต๊ะไม่ซ้ำกัน
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tables WHERE table_number = ?");
        $stmt->execute([$table_number]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            throw new Exception('หมายเลขโต๊ะนี้มีอยู่แล้ว');
        }

        // สร้าง URL สำหรับ QR Code ที่ลิงก์ไปยังหน้าเลือกเมนู
        $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../index.php';
        $tableData = $baseUrl . '?table_number=' . $table_number;
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($tableData);

        // บันทึกข้อมูลโต๊ะในฐานข้อมูล
        $stmt = $pdo->prepare("INSERT INTO tables (table_number, max_capacity, status, qr_code) VALUES (?, ?, ?, ?)");
        $stmt->execute([$table_number, $max_capacity, $status, $qrUrl]);

        header('Location: manage_tables.php');
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
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
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
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
