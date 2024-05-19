<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

// ตรวจสอบการเข้าสู่ระบบของแอดมิน
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin/login.php');
    exit();
}

// ดึงข้อมูลโต๊ะทั้งหมด
$tables = $pdo->query("SELECT * FROM tables ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการโต๊ะ</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .status-available {
            color: green;
        }
        .status-occupied {
            color: red;
        }
    </style>
</head>
<body>
    <?php include '../admin/layout/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center">จัดการโต๊ะ</h1>
        <div class="text-end mb-3">
            <a href="add_table.php" class="btn btn-primary">เพิ่มโต๊ะ</a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>หมายเลขโต๊ะ</th>
                    <th>ความจุสูงสุด</th>
                    <th>สถานะ</th>
                    <th>QR Code</th>
                    <th>การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tables as $table) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($table['table_number']); ?></td>
                        <td><?php echo htmlspecialchars($table['max_capacity']); ?></td>
                        <td>
                            <span class="<?php echo $table['status'] == 'available' ? 'status-available' : 'status-occupied'; ?>">
                                <?php echo htmlspecialchars($table['status']); ?>
                            </span>
                        </td>
                        <td>
                            <img src="<?php echo htmlspecialchars($table['qr_code']); ?>" alt="QR Code" width="50" height="50">
                        </td>
                        <td>
                            <a href="edit_table.php?id=<?php echo $table['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                            <a href="delete_table.php?id=<?php echo $table['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบโต๊ะนี้?');">ลบ</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
