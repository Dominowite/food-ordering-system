<?php
session_start();
include '../../includes/db.php';
include '../functions.php';

// ตรวจสอบการเข้าสู่ระบบแอดมิน
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../login.php');
    exit();
}

// ดึงข้อมูลเมนูอาหารจากฐานข้อมูล
$menus = $pdo->query("SELECT * FROM menus")->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการเมนูอาหาร</title>
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <!-- แถบเมนูด้านข้าง -->
    <sidebar class="sidebar">
        <?php include '../layout/sidebar.php'; ?>
    </sidebar>

    <div class="main-content">
        <div class="container mt-3">
            <h1 class="text-center">จัดการเมนูอาหาร</h1>
            <div class="row mt-4">
                <div class="col-md-12">
                    <a href="add_menu.php" class="btn btn-success mb-3">เพิ่มเมนูอาหาร</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ชื่อเมนู</th>
                                <th>คำอธิบาย</th>
                                <th>ราคา</th>
                                <th>รูปภาพ</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menus as $menu): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($menu['name']); ?></td>
                                    <td><?php echo htmlspecialchars($menu['description']); ?></td>
                                    <td><?php echo htmlspecialchars($menu['price']); ?> บาท</td>
                                    <td><img src="../../upload/img/<?php echo htmlspecialchars($menu['image']); ?>" alt="Image" width="100"></td>
                                    <td>
                                        <a href="edit_menu.php?id=<?php echo $menu['id']; ?>" class="btn btn-primary btn-sm">แก้ไข</a>
                                        <a href="delete_menu.php?id=<?php echo $menu['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบเมนูนี้?');">ลบ</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>

</body>
</html>
