<?php
session_start();
include '../includes/db.php';
include 'functions.php';

// ตรวจสอบการเข้าสู่ระบบแอดมิน
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// ตัวอย่างการดึงข้อมูลสรุปจากฐานข้อมูล
$menuCount = $pdo->query("SELECT COUNT(*) FROM menus")->fetchColumn();
$orderCount = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ดแอดมิน</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- แถบเมนูด้านข้าง -->
    <sidebar class="sidebar">
        <?php include 'layout/sidebar.php' ?>
    </sidebar>

    <div class="main-content">
        <div class="container mt-3">
            <h1 class="text-center">แดชบอร์ดแอดมิน</h1>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">จำนวนเมนูอาหาร</h5>
                            <p class="card-text"><?php echo $menuCount; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">คำสั่งซื้อที่รอดำเนินการ</h5>
                            <p class="card-text"><?php echo $orderCount; ?> คำสั่งซื้อที่ยังไม่ได้ดำเนินการ</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <a href="manage_menus.php" class="btn btn-primary">จัดการเมนูอาหาร</a>
                    <a href="view_orders.php" class="btn btn-danger">ดูคำสั่งซื้อ</a>
                </div>
            </div>
        </div>
    </div>

    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>

</body>
</html>
